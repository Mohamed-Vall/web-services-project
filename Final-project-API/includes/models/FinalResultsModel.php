<?php

class FinalResultsModel extends BaseModel {

    private $table_name = "finalresults";

    private $resultID;
    private $eventID;
    private $finalStats;
    private $results;

    /**
     * A model class for the `finalresults` database table.
     * It exposes operations that can be performed on results records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Retrieve all results from the `finalresults` table.
     * @return array A list of finalresults. 
     */
    public function getAll() {
        $sql = "SELECT * FROM finalresults";
        $data = $this->rows($sql);
        return $data;
    }

    /**
     * Retrieve an results by its id.
     * @param int $result_id the id of the results.
     * @return array an array containing information about a given results.
     */
    public function getResultById($result_id) {
        $sql = "SELECT * FROM finalresults WHERE resultID = ?";
        $data = $this->run($sql, [$result_id])->fetch();
        return $data;
    }
    
    public function getResultByEventId($event_id){
        $sql = "SELECT * FROM finalresults WHERE eventID = ?";
        $data = $this->run($sql, [$event_id])->fetch();
        return $data;
    }
    
    public function deleteResults($resultId){
        $sql = "DELETE FROM finalresults WHERE resultID = ?";
        $data = $this->run($sql, [$resultId])->fetch();
        return $data;
    }

}