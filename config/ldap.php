<?php

return [
    'host' => env('LDAP_HOST', '127.0.0.1'),
    'port' => env('LDAP_PORT', 389),
    'bind_dn' => env('LDAP_BIND_DN', 'CN=ldap-reader,OU=Service Accounts,DC=kv,DC=net'),
    'password' => env('LDAP_PASSWORD'),
    'control_paged_result_size' => env('LDAP_PAGESIZE', 50),
    'base_dn' => env('LDAP_BASE_DN', 'OU=KV Users,DC=kv,DC=net'),
];
