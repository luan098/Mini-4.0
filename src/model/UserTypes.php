<?php

namespace Mini\model;

use Mini\core\DTModel;

final class UserTypes extends DTModel
{
    const TABLE = 'user_types';
    const CUSTOMER = 1;
    const SELLER = 2;
    const MEDIATOR_TRANSPORTER = 3;
    const ADMINISTRATOR = 4;
    const SUPPORT_TEAM = 5;

    const TYPES = [
        self::CUSTOMER => 'Customer',
        self::SELLER => 'Seller',
        self::MEDIATOR_TRANSPORTER => 'Mediator (Transporter)',
        self::ADMINISTRATOR => 'Administrator',
        self::SUPPORT_TEAM => 'Support Team',
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
