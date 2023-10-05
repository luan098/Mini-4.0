<?php

namespace Mini\model;

use Mini\core\Model;

final class SettingsEmail extends Model
{
    const TABLE = 'settings_email';

    function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
