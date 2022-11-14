<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/fighterModel.php';

function handleCreatefighters(Request $request, Response $response, array $args) {
    
    $data = $request->getParsedBody();

    $data_string = "";
    $fighter_model = new fighterModel();
    foreach($data as $key => $fighter_info) {                    
        
        $fighter_id = $fighter_info["fighterId"];
        $fighterName = $fighter_info["Name"];
        $new_fighters_record = array(
            "fighterId" => $fighter_id,
            "Name" => $fighterName
        );
        $fighter_model->createfighter($new_fighters_record);
        /*$fighter_model->updatefighter(
            $existing_fighter_record;
            array("fighterId" => $fighterId);
        );*/
        
    
    }
    //$html = var_export($data, true);
    $response->getBody()->write("fighters created!");
    return $response;
}

// Callback for HTTP GET /fighters
//-- Supported filtering operation: by fighter name.
function handleGetAllfighters(Request $request, Response $response, array $args) {
    $fighters = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fighter_model = new fighterModel();

    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["name"])) {
        // Fetch the list of fighters matching the provided name.
        $fighters = $fighter_model->getWhereLikefighters($filter_params["name"]);
    } else {
        // No filtering by fighter name detected.
        $fighters = $fighter_model->getAllfighters();
    }    
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fighters, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetfighterById(Request $request, Response $response, array $args) {
    $fighter_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fighter_model = new fighterModel();

    // Retreive the fighter if from the request's URI.
    $fighter_id = $args["fighter_id"];
    if (isset($fighter_id)) {
        // Fetch the info about the specified fighter.
        $fighter_info = $fighter_model->getfighterById($fighter_id);
        if (!$fighter_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified fighter.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fighter_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleDeletefighterById(Request $request, Response $response, array $args) {
     // Retreive the fighter if from the request's URI.
    $fighter_id = $args["fighter_id"];
    // TODO: use the fighter model to delete 
    $fighter_model = new fighterModel();
    $fighter_model->deletefighterById($fighter_id); 
    $response->getBody()->write("Deleted ".$fighter_id);
    return $response;
}

function handleGetfighterByAlbum(Request $request, Response $response, array $args) {
    $fighter_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fighter_model = new fighterModel();

    // Retreive the fighter if from the request's URI.
    $fighter_id = $args["fighter_id"];
    if (isset($fighter_id)) {
        // Fetch the info about the specified fighter.
        $fighter_info = $fighter_model->getfighterByAlbum($fighter_id);
        if (!$fighter_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified fighter.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fighter_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetAlbumfighterByTrack(Request $request, Response $response, array $args) {
    $fighter_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $fighter_model = new fighterModel();

    // Retreive the fighter if from the request's URI.
    $fighter_id = $args["fighter_id"];
    $album_id = $args["album_id"];
    if (isset($fighter_id, $album_id)) {
        // Fetch the info about the specified fighter.
        $fighter_info = $fighter_model->getfighterByAlbumTrack($fighter_id, $album_id);
        if (!$fighter_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified fighter.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($fighter_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleUpdatefighters(Request $request, Response $response, array $args) {
    
    $data = $request->getParsedBody();

    $data_string = "";
    $fighter_model = new fighterModel();
    foreach($data as $key => $fighter_info) {                    
        
        $fighter_id = $fighter_info["fighterId"];
        $fighterName = $fighter_info["name"];
        $new_fighters_record = array(
            "fighterId" => $fighter_id,
            "name" => $fighterName
        );
        $fighter_model->updatefighter(
            $existing_fighter_record;
            array("fighterId" => $fighterId);
        );
        
    
    }
    //$html = var_export($data, true);
    $response->getBody()->write("fighters updated!");
    return $response;
}