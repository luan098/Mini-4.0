<?php

namespace Mini\model;

use Mini\core\DTModel;

final class UserTypes extends DTModel
{
    const TABLE = 'user_types';
    const ADMINISTRATOR = 1;
    const SUPPORT = 2;
    const CUSTOMER = 3;

    const TYPES = [
        self::ADMINISTRATOR => 'Administrator',
        self::SUPPORT => 'Support Team',
        self::CUSTOMER => 'Customer',
    ];

    function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public static function isComprador($idTipo): bool
    {
        return $idTipo == self::CUSTOMER;
    }
}
