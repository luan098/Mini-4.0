<?php

namespace Mini\model;

use Mini\core\Model;

final class UserTypes extends Model
{
    const TABLE = 'user_types';
    const CUSTOMER = 1;
    const SELLER = 2;
    const MEDIATOR_TRANSPORTER = 3;
    const SUPER_ADMINISTRATOR = 4;
    const SUPPORT_TEAM = 5;
    const ADMINISTRATOR = 6;

    const TYPES = [
        self::CUSTOMER => 'Customer',
        self::SELLER => 'Seller',
        self::MEDIATOR_TRANSPORTER => 'Mediator (Transporter)',
        self::SUPER_ADMINISTRATOR => 'Super Administrator',
        self::SUPPORT_TEAM => 'Support Team',
        self::ADMINISTRATOR => 'Administrator'
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
