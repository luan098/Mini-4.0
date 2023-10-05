<?php

namespace Mini\model;

use Mini\core\Model;

final class SettingsGeneral extends Model
{
    const TABLE = 'settings_general';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
