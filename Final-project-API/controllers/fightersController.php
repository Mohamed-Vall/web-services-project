<?php

class FightersController extends WebServiceInvoker {

    private $request_options = Array(
        'headers' => Array('Accept' => 'application/json')
    );

    public function __construct() {
        parent::__construct($this->request_options);
    }

    /**
     * Fetches and parses a list of books from the Ice and Fire API.
     * 
     * @return array containing some information about books. 
     */
    function getUpcomingInfo() {   //Use Fights Table
        $events = Array();
        $resource_uri = "https://localhost/web-services-project/Final-Project-API/fights";
        $eventsData = $this->invoke($resource_uri);

        if (!empty($eventsData)) {
            // Parse the fetched list of books.   
            $eventsData = json_decode($eventsData, true);
            //var_dump($booksData);exit;

            $index = 0;
            // Parse the list of books and retreive some  
            // of the contained information.
            foreach ($eventsData as $key => $event) {
                $events[$index]["fightID"] = $event["fightID"];
                $events[$index]["fighter1ID"] = $event["fighter1ID"];
                $events[$index]["fighter2ID"] = $event["fighter2ID"];
                $events[$index]["odds"] = $event["odds"];
                $events[$index]["schedule"] = $event["schedule"];
                //
                $index++;
            }
        }
        return $events;
    }

    function getFinishedInfo() {  //Use Events Table
        $events = Array();
        $resource_uri = "https://localhost/web-services-project/Final-Project-API/events";
        $eventsData = $this->invoke($resource_uri);

        if (!empty($eventsData)) {
            // Parse the fetched list of books.   
            $eventsData = json_decode($eventsData, true);
            //var_dump($booksData);exit;

            $index = 0;
            // Parse the list of books and retreive some  
            // of the contained information.
            foreach ($eventsData as $key => $event) {
                $events[$index]["liveId"] = $event["liveId"];
                $events[$index]["fightId"] = $event["fightId"];
                $events[$index]["stats"] = $event["stats"];
                //
                $index++;
            }
        }
        return $events;
    }
}