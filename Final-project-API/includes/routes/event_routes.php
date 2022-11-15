<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/EventModel.php';

// Callback for HTTP GET /events
//-- Supported filtering operation: by events id.
function handleGetAllEvents(Request $request, Response $response, array $args) {
    $events = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $events_model = new EventModel();

    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["name"])) {
        // Fetch the list of events matching the provided id.
        $events = $events_model->getEventByFighterName($filter_params["name"]);
    } else {
        // No filtering by event id detected.
        $events = $events_model->getAll();
    }    
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($events, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetEventsById(Request $request, Response $response, array $args) {
    $events_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $events_model = new EventModel();

    // Retreive the events if from the request's URI.
    $liveId = $args["id"];
    $fighterId = $args["fighterId"];
    if (isset($liveId)) {
        // Fetch the info about the specified result.
        $events_info = $events_model->getEventById($liveId);
        if (isset($fighterId)){
            $events_info = $events_model->getEventByFighterId($fighterId);
            if (!$events_info){
                // No matches found?
                $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified event.");
                $response->getBody()->write($response_data);
                return $response->withStatus(HTTP_NOT_FOUND);
            }
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($events_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleDeleteEventsById(Request $request, Response $response, array $data){
    $events_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $events_model = new EventModel();
    
    $liveId = $args["liveId"];
    if (isset($liveId)) {
        // Fetch the info about the specified result.
        $events_info = $events_model->getEventById($liveId);
        if (!$events_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified event.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $events_model->deleteEvent($liveId);
        $response_data = "Deleted Fight events Succesfully";
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}