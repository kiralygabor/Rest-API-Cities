<?php
 /**
 * @author Nagy Gergely, Király Gábor 
 **/
namespace App\Repositories;

class CityRepository extends BaseRepository
{
    function __construct($host = self::HOST, $user = self::USER, $password = self::PASSWORD, $db = self::DATABASE)
    {
        parent::__construct($host, $user, $password, $db);
        $this->tableName = 'cities';
    }

    public function getAllCity(): array {
        $query = $this->select() . " ORDER BY city";  
        $result = $this->mysqli->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findCityByCountyId(int $countyId): array {
        $query = $this->select() . " WHERE id_county = $countyId ORDER BY city";
        $result = $this->mysqli->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findByCityId(int $cityId): array {
        $query = $this->select() . " WHERE id = $cityId ORDER BY city";
        $result = $this->mysqli->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}