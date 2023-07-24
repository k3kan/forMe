<?php

namespace App\Models;

class Locations extends Model
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    static public function getLocations()
    {
        return self::getConnection()
            ->createQueryBuilder()
            ->select('town, latitude, longitude')
            ->from('locations')
            ->fetchAllAssociative();
    }
}