<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Config;

class LDAPModel extends Model
{

    protected $ldaphost;
    protected $ldapport;
    protected $ldapconn = null;

    protected $bind_rdn;
    protected $bind_password;
    protected $ldap_control_paged_result_count;

    function __construct() {
        //parent::__construct();
        $this->ldaphost = config('ldap.host');
        $this->ldapport = config('ldap.port');
        $this->bind_rdn = config('ldap.bind_dn');
        $this->bind_password = config('ldap.password');
        $this->ldap_control_paged_result_count = config('ldap.control_paged_result_size');
        $this->doConnect()->doBind();
    }
    function __destruct() {
        //print "Destroying " . __CLASS__ . "\n";
        $this->doUnbind()->doClose();
    }
    /**
     * execute ldap_connect function
     * @return $this
     */
    public function doConnect(){
        // $ldapconn = ldap_connect($this->ldaphost, $this->ldapport) or die ("Could not connect to $ldaphost");
        $this->ldapconn = @ldap_connect($this->ldaphost, $this->ldapport);

        $this->setOption(LDAP_OPT_PROTOCOL_VERSION, 3);
        $this->setOption(LDAP_OPT_REFERRALS, 0);
        $this->setOption(LDAP_OPT_SIZELIMIT, 1000);
        //@ldap_set_option($this->ldapconn, LDAP_OPT_NETWORK_TIMEOUT, 10);

        //@ldap_bind($this->ldapconn);

        return $this;
    }
    /**
     * execute ldap_set_option function
     * @return bool
     */
    public function setOption($option, $value){
        $set = @ldap_set_option($this->ldapconn, $option, $value);

        return $set;
    }
    /**
     * execute ldap_close function
     * @return $this
     */
    public function doClose(){
        @ldap_close($this->ldapconn);

        return $this;
    }
    /**
     * execute ldap_unbind function
     * @return $this
     */
    public function doUnbind(){
        @ldap_unbind($this->ldapconn);

        return $this;
    }
    /**
     * execute ldap_bind function
     * @return $this
     */
    public function doBind(){
        // binding to ldap server
        $ldapbind = @ldap_bind($this->ldapconn, $this->bind_rdn, $this->bind_password);

        return $this;
    }
    /**
     * execute ldap_bind function
     * @return boolean
     */
    public function isBind($bind_rdn, $bind_password){
        $ldapbind = false;
        $ldapbind = @ldap_bind($this->ldapconn, $bind_rdn, $bind_password);

        return $ldapbind;
    }

    public function doSearch($filter = '(cn=*)', $attributes = array(), $ldaptree = 'OU=KV Users,DC=kv,DC=net'){
        $results = array();

        @ldap_control_paged_result($this->ldapconn, $this->ldap_control_paged_result_count);
        //$searchResults = @ldap_search($this->ldapconn, $ldaptree, $filter, $attributes, false, 10);
        $searchResults = @ldap_search($this->ldapconn, $ldaptree, $filter, $attributes);
        //ldap_count_entries($this->ldapconn, $searchResults);
        $results = @ldap_get_entries($this->ldapconn, $searchResults);

        return $results;
    }
    /**
     * execute ldap_count_entries function
     * @return int
     */
    public function doCount($results){
        return @ldap_count_entries($this->ldapconn, $results);
    }
    /**
     * format ldap 'ldap_get_entries' to 2d Array
     * @return 2d array
     */
    public function formatEntries($entries) {
        //$count = (isset($entries["count"])) ? $entries["count"] : 0;
        if( (!is_array($entries)) ){
            return null;
        }
        $results = array();

        foreach ($entries as $inf) {
            if (is_array($inf)) {
                foreach ($inf as $key => $in) {
                    if ((@count($inf[$key]) - 1) > 0) {
                        if (is_array($in)) {
                            unset($inf[$key]["count"]);
                        }
                        //$temp_result[$key] = $inf[$key];
                        //$temp_result[$key] = array_shift( $inf[$key] );
                        $count = count( $inf[$key] , 0);
                        if( $count == 1 ){
                            $temp_result[$key] = array_shift( $inf[$key] );
                        }else{
                            $temp_result[$key] = $inf[$key];
                        }
                    }
                }
                $temp_result["dn"] = @explode(',', $inf["dn"]);
                array_push($results, $temp_result);
            }
        }
        /*$count = count($results, 0);
        if($count == 1){
            $results = array_shift($results);
        }*/

        return $results;
    }
    /**
     * get connection
     * @return ldap_connect object
     */
    public function getConnection(){
        return $this->ldapconn;
    }

}
