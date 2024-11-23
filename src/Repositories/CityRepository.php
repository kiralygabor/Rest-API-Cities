<?php

namespace App\Repositories;
class CityRepository extends BaseRepository
{
    function __construct($host = self::HOST, $user = self::USER, $password = self::PASSWORD, $db = self::DATABASE)
    {
        parent::__construct($host, $user, $password, $db);
        $this->tableName = 'cities';
    }
}
