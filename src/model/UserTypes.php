<?php

namespace Mini\model;

use Mini\core\DTModel;

final class UserTypes extends DTModel
{
    const TABLE = 'user_types';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
