<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | This option enables logging all LDAP operations on all configured
    | connections such as bind requests and CRUD operations.
    |
    | Log entries will be created in your default logging stack.
    |
    | This option is extremely helpful for debugging connectivity issues.
    |
    */

    'logging' => env('LDAP_LOGGING', false),

    /*
    |--------------------------------------------------------------------------
    | Connections
    |--------------------------------------------------------------------------
    |
    | This array stores the connections that are added to Adldap. You can add
    | as many connections as you like.
    |
    | The key is the name of the connection you wish to use and the value is
    | an array of configuration settings.
    |
    */

    'connections' => [

        // here, in theory, we should leave `default` untouched and create a new connection
        // (and change `LDAP_CONNECTION` in `.env` accordingly)
        // but I wasn't able to make the underlying Adldap package work with any connection
        // other than `default`, so we will modify the default connection directly

        'default' => [
            'auto_connect' => env('LDAP_AUTO_CONNECT', false),

            'connection' => Adldap\Connections\Ldap::class,

            'settings' => [

                // replace this line:
                // 'schema' => Adldap\Schemas\ActiveDirectory::class,
                // with this:
                'schema' => env('LDAP_SCHEMA', '') == 'OpenLDAP' ?
                    Adldap\Schemas\OpenLDAP::class : (env('LDAP_SCHEMA', '') == 'FreeIPA' ?
                        Adldap\Schemas\FreeIPA::class :
                        Adldap\Schemas\ActiveDirectory::class),

                // remove the default values of these options:
                'hosts' => explode(' ', env('LDAP_HOSTS', '')),
                'base_dn' => env('LDAP_BASE_DN', ''),
                'username' => env('LDAP_ADMIN_USERNAME', ''),
                'password' => env('LDAP_ADMIN_PASSWORD', ''),

                // and talk to your LDAP administrator about these other options.
                // do not modify them here, use .env!
                'account_prefix' => env('LDAP_ACCOUNT_PREFIX', ''),
                'account_suffix' => env('LDAP_ACCOUNT_SUFFIX', ''),
                'port' => env('LDAP_PORT', 389),
                'timeout' => env('LDAP_TIMEOUT', 5),
                'follow_referrals' => env('LDAP_FOLLOW_REFERRALS', false),
                'use_ssl' => env('LDAP_USE_SSL', false),
                'use_tls' => env('LDAP_USE_TLS', false),

            ],
        ],
    ],
    'usernames' => [
        'ldap' => env('ADLDAP_USER_ATTRIBUTE', 'userprincipalname'), // was just 'userprincipalname'
        'eloquent' => 'username', // was 'email'
    ],
    
    'sync_attributes' => [
        // 'field_in_local_db' => 'attribute_in_ldap_server',
        'username' => 'uid', // was 'email' => 'userprincipalname',
        'name' => 'cn',
        'phone' => 'telephonenumber',
    ],
];
