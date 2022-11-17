<?php

class FighterModel extends BaseModel {

    private $table_name = "fighters";

    /**
     * A model class for the `fighters` database table.
     * It exposes operations that can be performed on fighters records.
     */
    function __construct() {
        parent::__construct();
    }

    public function getAllFighters() {
        $sql = "SELECT * FROM fighters";
        $data = $this->rows($sql);
        return $data;
    }

    public function getWhereLikeFighters($fighterName) {
        $sql = "SELECT * FROM fighters WHERE Name LIKE :name";
        $data = $this->run($sql, [":name" => $fighterName . "%"])->fetchAll();
        return $data;
    }

    public function createFighter($data) {
        $data = $this->insert("fighters", $data);
        return $data;
    }

    public function deleteFighterById($fighter_id) {
        $stmt = $this->run("DELETE FROM $this->table_name WHERE fighterId = ?", [$fighter_id]);
        return $stmt->rowCount();
    }

    public function updatefighter($fighter_id, $name) {
        $sql = "UPDATE fighters SET fighterId = :fighterid, Name = :name WHERE fighterId = :fighterId";
        $data = $this->run($sql, [":fighterid" => $fighter_id, ":name" => $name]);
        return $data;
    }

    public function getFightersById($fighter_id) {
        $sql = "SELECT * FROM fighters WHERE fighterId = ?";
        $data = $this->rows($sql, [$fighter_id]);
        return $data;
      }
}
