<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\LDAPModel;
use Illuminate\Support\Facades\Validator;
use Exception;

class CreateADUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:create-user
        {--cn= : Common Name (required)}
        {--sn= : Surname (required)}
        {--givenname= : Given Name (first name)}
        {--title= : Job Title}
        {--description= : Description}
        {--displayname= : Display Name}
        {--department= : Department}
        {--company= : Company}
        {--employeenumber= : Employee Number}
        {--mailnickname= : Mail Nickname (required)}
        {--mail= : Email Address (required)}
        {--mobile= : Mobile Number}
        {--userprincipalname= : User Principal Name (required)}
        {--directreports=* : Direct Reports DNs (can specify multiple)}
        {--manager= : Manager DN}
        {--physicaldeliveryofficename= : Office Location}
        {--password= : Password (required)}
        {--thumbnailphoto= : Path to thumbnail photo}
        {--ou= : Organizational Unit (default: OU=KV Users,DC=kv,DC=net)}
        {--enable : Enable the account immediately}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Active Directory user with all specified attributes';

    /**
     * Default OU for new users
     */
    private const DEFAULT_OU = 'OU=KV Users,DC=kv,DC=net';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            // Validate input
            if (!$this->validateInput()) {
                return 1;
            }

            // Create user in AD
            $userDN = $this->createADUser();

            // Display success message
            $this->displaySuccess($userDN);

            return 0;

        } catch (Exception $e) {
            $this->error("Error creating user: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Validate required input parameters
     *
     * @return bool
     */
    private function validateInput(): bool
    {
        $rules = [
            'cn' => 'required|string|max:64',
            'sn' => 'required|string|max:64',
            'mailnickname' => 'required|string|max:20|regex:/^[a-zA-Z0-9._-]+$/',
            'mail' => 'required|email|max:256',
            'userprincipalname' => 'required|string|max:256|regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|string|min:8',
            'thumbnailphoto' => 'nullable|file|exists',
        ];

        $validator = Validator::make($this->options(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        // Additional validation for thumbnail photo
        if ($this->option('thumbnailphoto') && !file_exists($this->option('thumbnailphoto'))) {
            $this->error('Thumbnail photo file does not exist: ' . $this->option('thumbnailphoto'));
            return false;
        }

        return true;
    }

    /**
     * Create the Active Directory user
     *
     * @return string The DN of the created user
     * @throws Exception
     */
    private function createADUser(): string
    {
        $ldapModel = new LDAPModel();

        // Build DN
        $ou = $this->option('ou') ?: self::DEFAULT_OU;
        $dn = "CN={$this->option('cn')},{$ou}";

        // Prepare user attributes
        $attributes = $this->buildUserAttributes();

        // Create the user
        $this->info("Creating user: {$this->option('cn')}");

        if (!@ldap_add($ldapModel->getConnection(), $dn, $attributes)) {
            throw new Exception('Failed to create user: ' . ldap_error($ldapModel->getConnection()));
        }

        // Set password
        $this->info("Setting password...");
        $this->setUserPassword($ldapModel, $dn, $this->option('password'));

        // Enable account if requested
        if ($this->option('enable')) {
            $this->info("Enabling account...");
            $this->enableUserAccount($ldapModel, $dn);
        }

        return $dn;
    }

    /**
     * Build user attributes array
     *
     * @return array
     */
    private function buildUserAttributes(): array
    {
        $attributes = [
            'objectClass' => ['top', 'person', 'organizationalPerson', 'user'],
            'cn' => $this->option('cn'),
            'sn' => $this->option('sn'),
            'displayName' => $this->option('displayname') ?: $this->option('cn'),
            'userPrincipalName' => $this->option('userprincipalname'),
            'sAMAccountName' => $this->option('mailnickname'),
            'mail' => $this->option('mail'),
        ];

        // Add givenName (extract from CN if not provided)
        if ($this->option('givenname')) {
            $attributes['givenName'] = $this->option('givenname');
        } else {
            // Extract first name from CN
            $nameParts = explode(' ', $this->option('cn'));
            $attributes['givenName'] = $nameParts[0];
        }

        // Add optional attributes
        $optionalFields = [
            'title' => 'title',
            'description' => 'description',
            'department' => 'department',
            'company' => 'company',
            'employeenumber' => 'employeeNumber',
            'mobile' => 'mobile',
            'physicaldeliveryofficename' => 'physicalDeliveryOfficeName',
            'manager' => 'manager'
        ];

        foreach ($optionalFields as $option => $ldapAttribute) {
            if ($this->option($option)) {
                $attributes[$ldapAttribute] = $this->option($option);
            }
        }

        // Add direct reports if provided (multiple values)
        if ($this->option('directreports')) {
            $directReports = $this->option('directreports');
            if (is_array($directReports) && !empty($directReports)) {
                $attributes['directReports'] = $directReports;
            }
        }

        // Add thumbnail photo if provided
        if ($this->option('thumbnailphoto') && file_exists($this->option('thumbnailphoto'))) {
            $photoData = file_get_contents($this->option('thumbnailphoto'));
            if ($photoData !== false) {
                $attributes['thumbnailPhoto'] = $photoData;
                $this->info("Added thumbnail photo from: " . $this->option('thumbnailphoto'));
            }
        }

        return $attributes;
    }

    /**
     * Set user password
     *
     * @param LDAPModel $ldapModel
     * @param string $dn
     * @param string $password
     * @throws Exception
     */
    private function setUserPassword(LDAPModel $ldapModel, string $dn, string $password): void
    {
        $entry = [
            'unicodePwd' => iconv('UTF-8', 'UTF-16LE', '"' . $password . '"')
        ];

        if (!@ldap_mod_replace($ldapModel->getConnection(), $dn, $entry)) {
            throw new Exception('Failed to set password: ' . ldap_error($ldapModel->getConnection()));
        }
    }

    /**
     * Enable user account
     *
     * @param LDAPModel $ldapModel
     * @param string $dn
     * @throws Exception
     */
    private function enableUserAccount(LDAPModel $ldapModel, string $dn): void
    {
        $entry = [
            'userAccountControl' => 512 // ADS_UF_NORMAL_ACCOUNT
        ];

        if (!@ldap_mod_replace($ldapModel->getConnection(), $dn, $entry)) {
            throw new Exception('Failed to enable account: ' . ldap_error($ldapModel->getConnection()));
        }
    }

    /**
     * Display success message with user details
     *
     * @param string $dn
     */
    private function displaySuccess(string $dn): void
    {
        $this->info("✅ User created successfully!");
        $this->line("");
        $this->info("User Details:");
        $this->line("DN: {$dn}");
        $this->line("CN: {$this->option('cn')}");
        $this->line("Email: {$this->option('mail')}");
        $this->line("UPN: {$this->option('userprincipalname')}");
        $this->line("SAM Account: {$this->option('mailnickname')}");

        if ($this->option('title')) {
            $this->line("Title: {$this->option('title')}");
        }

        if ($this->option('department')) {
            $this->line("Department: {$this->option('department')}");
        }

        if ($this->option('directreports')) {
            $directReports = $this->option('directreports');
            $this->line("Direct Reports: " . implode(', ', $directReports));
        }

        if ($this->option('enable')) {
            $this->info("Account Status: Enabled");
        } else {
            $this->warn("Account Status: Disabled (use --enable to enable immediately)");
        }
    }
}
