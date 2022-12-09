<?php

class FightersController extends WebServiceInvoker {

    private $request_options = Array(
        'headers' => Array('Accept' => 'application/json')
    );

    public function __construct() {
        parent::__construct($this->request_options);
    }

    /**
     * Fetches and parses a list of fights from the API.
     * 
     * @return array containing some information about fighters. 
     */
    function getExternalInfo() {   //Use Fights Table
        $events = Array();
        $resource_uri = "https://api.sportsdata.io/v3/mma/scores/json/Fighters?key=a541ac55a76a48d1add34c4639da10ec";
        $eventsData = $this->invoke($resource_uri);

        if (!empty($eventsData)) {
            // Parse the fetched list of fighters.   
            $eventsData = json_decode($eventsData, true);

            $index = 0;
            // Parse the list of fighters and retreive some of the contained information.
            foreach ($eventsData as $key => $event) {
                $events[$index]["FighterId"] = $event["FighterId"];
                $events[$index]["FirstName"] = $event["FirstName"];
                $events[$index]["LastName"] = $event["LastName"];
                $events[$index]["Nickname"] = $event["Nickname"];
                $events[$index]["WeightClass"] = $event["WeightClass"];
                $events[$index]["BirthDate"] = $event["BirthDate"];
                $events[$index]["Height"] = $event["Height"];
                $events[$index]["Weight"] = $event["Weight"];
                $events[$index]["Reach"] = $event["Reach"];
                $events[$index]["Wins"] = $event["Wins"];
                $events[$index]["Losses"] = $event["Losses"];
                $events[$index]["Draws"] = $event["Draws"];
                $events[$index]["NoContests"] = $event["NoContests"];
                $events[$index]["TechnicalKnockouts"] = $event["TechnicalKnockouts"];
                $events[$index]["TechnicalKnockoutLosses"] = $event["TechnicalKnockoutLosses"];
                $events[$index]["Submissions"] = $event["Submissions"];
                $events[$index]["SubmissionLosses"] = $event["SubmissionLosses"];
                $events[$index]["TitleWins"] = $event["TitleWins"];
                $events[$index]["TitleLosses"] = $event["TitleLosses"];
                $events[$index]["TitleDraws"] = $event["TitleDraws"];
                $events[$index]["CareerStats"] = $event["CareerStats"];
                $index++;
            }
        }
        return $events;
    }
}