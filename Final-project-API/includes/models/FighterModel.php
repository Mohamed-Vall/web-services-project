<?php

class FighterModel extends BaseModel {

    private $table_name = "Fighter";

    /**
     * A model class for the `fighter` database table.
     * It exposes operations that can be performed on fighters records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all fighters from the `fighter` table.
     * @return array A list of fighters. 
     */
    public function getAllFighters() {
        $sql = "SELECT * FROM fighter";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Get a list of fighters whose name matches or contains the provided value.       
     * @param string $fighterName 
     * @return array An array containing the matches found.
     */
    /*public function getWhereLikefighters($fighterName) {
        $sql = "SELECT * FROM fighter WHERE Name LIKE :name";
        $data = $this->run($sql, [":name" => $fighterName . "%"])->fetchAll();
        return $data;
    }

    /**
     * Retrieve an fighter by its id.
     * @param int $fighter_id the id of the fighter.
     * @return array an array containing information about a given fighter.
     *//*
    public function getfighterById($fighter_id) {
        $sql = "SELECT * FROM fighter WHERE fighterId = ?";
        $data = $this->run($sql, [$fighter_id])->fetch();
        return $data;
    }*/

    public function createFighter($data) {
        $data = $this->insert("fighter", $data);
        return $data;
    }

    public function deleteFighterById($fighter_id) {
        $stmt = $this->run("DELETE FROM $this->table_name WHERE fighterId = ?", [$fighter_id]);
        return $stmt->rowCount();
    }

    /*
    public function getfighterByAlbum($fighter_id) {
        $sql = "SELECT * FROM album WHERE fighterId LIKE :fighterid";
        $data = $this->run($sql, [":fighterid" => $fighter_id. "%"])->fetchAll();
        return $data;
    }

    public function getfighterByAlbumTrack($fighter_id, $album_id) {
        $sql = "SELECT DISTINCT * FROM album al INNER JOIN track tr WHERE al.fighterId = ? AND al.AlbumId = ? AND tr.AlbumId = ?"; 
        $data = $this->run($sql, [$fighter_id, $album_id, $album_id])->fetchAll();
        return $data;
    }

    public function updatefighter($fighter_id, $name) {
        $sql = "UPDATE fighter SET fighterId = :fighterid, Name = :name WHERE fighterId = :fighterId";
        $data = $this->run($sql, [":fighterid" => $fighter_id, ":name" => $name);
        return $data;
    }*/
}
