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
    function getUpcomingInfo($id) {
        $events = Array();
        $resource_uri = "https://api.sportsdata.io/v3/mma/stats/json/Fight/" + $id + "?key=a541ac55a76a48d1add34c4639da10ec";
        $eventsData = $this->invoke($resource_uri);

        if (!empty($eventsData)) {
            // Parse the fetched list of books.   
            $eventsData = json_decode($eventsData, true);
            //var_dump($booksData);exit;

            $index = 0;
            // Parse the list of books and retreive some  
            // of the contained information.
            foreach ($eventsData as $key => $event) {
                $books[$index]["name"] = $book["name"];
                $books[$index]["isbn"] = $book["isbn"];
                $books[$index]["authors"] = $book["authors"];
                $books[$index]["mediaType"] = $book["mediaType"];
                $books[$index]["country"] = $book["country"];
                $books[$index]["released"] = $book["released"];
                //
                $index++;
            }
        }
        return $events;
    }

    function getUpcomingInfo($id) {
        $events = Array();
        $resource_uri = "https://api.sportsdata.io/v3/mma/scores/json/Event/" + $id + "?key=a541ac55a76a48d1add34c4639da10ec";
        $eventsData = $this->invoke($resource_uri);

        if (!empty($eventsData)) {
            // Parse the fetched list of books.   
            $eventsData = json_decode($eventsData, true);
            //var_dump($booksData);exit;

            $index = 0;
            // Parse the list of books and retreive some  
            // of the contained information.
            foreach ($eventsData as $key => $event) {
                $books[$index]["name"] = $book["name"];
                $books[$index]["isbn"] = $book["isbn"];
                $books[$index]["authors"] = $book["authors"];
                $books[$index]["mediaType"] = $book["mediaType"];
                $books[$index]["country"] = $book["country"];
                $books[$index]["released"] = $book["released"];
                //
                $index++;
            }
        }
        return $events;
    }
