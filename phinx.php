<?php
require 'config.php';

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => '_phinxlog',
            'default_environment' => 'env',
            'env' => [
                'adapter' => DB_TYPE,
                'host' => DB_HOST,
                'name' => DB_NAME,
                'user' => DB_USER,
                'pass' => DB_PASS,
                'port' => DB_PORT,
                'charset' => DB_CHARSET,
            ]
        ],
        'version_order' => 'creation'
    ];
