<?php

class EventModel extends BaseModel {

    private $table_name = "events";

    private $liveId;
    private $fightId;
    private $stats;

    /**
     * A model class for the `events` database table.
     * It exposes operations that can be performed on events records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all events from the `events` table.
     * @return array A list of events. 
     */
    public function getAll() {
        $sql = "SELECT * FROM events";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Retrieve an events by its id.
     * @param int $live_id the id of the events.
     * @return array an array containing information about a given events.
     */
    public function getEventById($live_id) {
        $sql = "SELECT * FROM events WHERE liveId = ?";
        $data = $this->rows($sql, [$live_id]);
        return $data;
    }
    
    public function getEventByFighterId($live_id){
        $sql = "SELECT * FROM events WHERE fightId = ?";
        $data = $this->rows($sql, [$live_id]);
        return $data;
    }
    
    public function getEventByFighterName($fighterName){
        $sql = "SELECT * FROM events WHERE fighterId in 
                (SELECT fighterId FROM fighterId WHERE name LIKE :fighterName)";
        $data = $this->run($sql, [":fighterName" => $fighterName . "%"])->fetch();
        return $data;
    }
    
    public function deleteEvent($liveId){
        $sql = "DELETE FROM events WHERE liveId = ?";
        $data = $this->run($sql, [$liveId]);
        return $data->rowCount();
    }

}