<?php

namespace Mini\model;

use Mini\core\Model;

final class Uploads extends Model
{
    const TABLE = 'uploads';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
