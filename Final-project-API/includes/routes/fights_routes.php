<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/FightsModel.php';

function handleCreatefight(Request $request, Response $response, array $args) {
    
    $data = $request->getParsedBody();

    $data_string = "";
    $fight_model = new FightsModel();
    foreach($data as $key => $fights_data) {                    
        
        $fightid = $fights_data["fightID"];
        $fighter1Name = $fights_data["fighter1ID"];
        $fighter2Name = $fights_data["fighter2ID"];
        $odds = $fights_data["odds"];
        $schedule = $fights_data["schedule"];
        $fight_array = array(
            "fightID" => $fightid,
            "fighter1ID" => $fighter1Name,
            "fighter2ID" => $fighter2Name,
            "odds" => $odds,
            "schedule" => $schedule
        );
        $fight_model->createFight($fight_array);

    }
    $response->getBody()->write("fights created!");
    return $response;
}

function handleGetFights(Request $request, Response $response, array $args) {
    $fights_data = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fight_model = new FightsModel();

    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["fighter1"])) {
        $fight = $fight_model->getFightByFighter($filter_params["fighter1"]);
    } elseif (isset($filter_params["fighter2"])) {
        $fight = $fight_model->getFightByFighter($filter_params["fighter1"]);
    } else {
        $fight = $fight_model->getAllFights();
    }    
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fight, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetfightById(Request $request, Response $response, array $args) {
    $fights_data = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fights_model = new FightsModel();

    $fight_id = $args["fightsid"];
    if (isset($fight_id)) {
        $fights_data = $fights_model->getFightById($fight_id);
        if (!$fights_data) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "This fight does not exist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fights_data, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleDeletefightById(Request $request, Response $response, array $args) {
   $fight_id = $args["fightsid"];
   $fight_model = new FightsModel();
   $fight_model->deleteFightById($fight_id); 
   $response->getBody()->write("Deleted ".$fight_id);
   return $response;
}

function handleGetfightByOdds(Request $request, Response $response, array $args) {
    $fights_data = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fight_model = new FightsModel();

    $fight_odds = $args["odds"];
    if (isset($fight_odds)) {
        // Fetch the info about the specified fight.
        $fights_data = $fight_model->getFightByOdds($fight_odds);
        if (!$fights_data) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "This fight does not exist");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fights_data, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetFightBySchedule(Request $request, Response $response, array $args) {
    $fights_data = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fight_model = new FightsModel();

    $fight_schedule = $args["schedule"];
    if (isset($fight_schedule)) {
        // Fetch the info about the specified fight.
        $fights_data = $fight_model->getFightBySchedule($fight_schedule);
        if (!$fights_data) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "This fight does not exist");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fights_data, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleUpdatefights(Request $request, Response $response, array $args) {
    
    $data = $request->getParsedBody();

    $fight_model = new FightsModel();
    foreach($data as $key => $fights_data) {                    
        
        $fight_model->fight_id = $fights_data["fightId"];
        $fight_model->fighter1 = $fights_data["fighter1"];
        $fight_model->fighter2 = $fights_data["fighter2"];
        $fight_model->odds = $fights_data["odds"];
        $fight_model->schedule = $fights_data["schedule"];
        $fight_model->update();        
    }
    $response->getBody()->write("fights updated!");
    return $response;
}
