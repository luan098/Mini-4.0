<?php

namespace Mini\model;

use Mini\core\Model;

final class Errors extends Model
{
    const TABLE = '_errors';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
