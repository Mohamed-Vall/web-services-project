<?php
//require_once("../database/connectionManager.php");

/**
 *
 */
class FightsModel extends BaseModel {

  private $table_name = "fights";
  /**
   * The ID of the fight
   * @var string
   */
  public $fightid;

  /**
   * The name of the first fighter
   * @var string
   */
  public $fighter1;

  /**
   * The name of the second fighter
   * @var string
   */
  public $fighter2;

  /**
   * The odds of who winning the fight
   * @var string
   */
  public $odds;

  /**
   * The fight schedule
   * @var string
   */
  public $schedule;

  private $connectionManager;
  private $dbConnection;

  function __construct()
  {
    parent::__construct();
    //$this->connectionManager = new ConnectionManager();
    //$this->dbConnection = $this->connectionManager->getConnection();
  }

  /*public function getWhereLikeFights($artistName) {
    $sql = "SELECT * FROM artist WHERE Name LIKE :name";
    $data = $this->run($sql, [":name" => $artistName . "%"])->fetchAll();
    return $data;
  }*/

/**
 * Retrieve an artist by its id.
 * @param int $artist_id the id of the artist.
 * @return array an array containing information about a given artist.
 */
  public function getFightById($fight_id) {
    $sql = "SELECT * FROM fights WHERE fightID = ?";
    $data = $this->rows($sql, [$fight_id]);
    return $data;
  }

  public function getAllFights() {
    $sql = "SELECT * FROM fights";
    $data = $this->rows($sql);
    return $data;
}

  public function createFight($data) {
    $data = $this->insert("fights", $data);
    return $data;
}

public function deleteFightById($fight_id) {
  $stmt = $this->run("DELETE FROM $this->table_name WHERE fightID = ?", [$fight_id]);
  return $stmt->rowCount();
}
  /*function getAllFights()
  {
    $query = "SELECT * FROM fights";

    $statement = $this->dbConnection->prepare($query);

    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }*/

  /*public function getAllFights() {
    $sql = "SELECT * FROM fights";
    $data = $this->rows($sql);
    return $data;
  }*/

  

  /*function getFightByID($fightid)
  {
    $query = "SELECT * FROM fights WHERE fightid = " . $fightid;

    $statement = $this->dbConnection->prepare($query);

    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  function insert() //PUT
  {
    $query = "INSERT INTO fights(fightid, fighter1, fighter2, odds, schedule) VALUES(:fightid, :fighter1, :fighter2, :odds, :schedule)";

    $statement = $this->dbConnection->prepare($query);
  }

  function update() //UPDATE
  {
    $query = "UPDATE fights SET fighter1 = :fighter1, fighter2 = :fighter2, odds = :odds, schedule = :schedule WHERE fightid = :fightid";

    $statement = $this->dbConnection->prepare($query);

    return $statement->execute(['fightid' => $this->fightid, 'fighter1' => $this->fighter1, 'fighter2' => $this->fighter2, 'odds' => $this->odds, 'schedule' => $this->schedule]);
  }

  function delete() //DELETE
  {
    $query = "DELETE FROM fights WHERE fightid = :fightid";

    $statement = $this->dbConnection->prepare($query);

    return $statement->execute(['fights' => $this->fights]);
  }

  function getFightByFighter($fighter)
  {
    $query = "SELECT * FROM fights WHERE fighter1 = :fighter OR fighter2 = :fighter";

    $statement = $this->dbConnection->prepare($query);

    $statement->execute(['fighter2' => $fighter, 'fighter1' => $fighter]);

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  function getFightByOdds($odds)
  {
    $query = "SELECT * FROM fights WHERE odds = :odds";

    $statement = $this->dbConnection->prepare($query);

    $statement->execute(['odds' => $odds]);

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  function getFightBySchedule($schedule)
  {
    $query = "SELECT * FROM fights WHERE schedule = :schedule";

    $statement = $this->dbConnection->prepare($query);

    $statement->execute(['schedule' => $schedule]);

    return $statement->fetch(PDO::FETCH_ASSOC);
  }*/

}
