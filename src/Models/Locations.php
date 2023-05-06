<?php

namespace App\Models;

class Locations extends Model
{
    static public function getLocations()
    {
        $sql = <<<SQL
SELECT town, latitude, longitude FROM locations;
SQL;
        return self::getConnection()->execute($sql);
    }
}