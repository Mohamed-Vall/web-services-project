<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/FinalResultsModel.php';
require_once __DIR__ . './../models/WSLoggingModel.php';
require_once __DIR__ . './../helpers/Paginator.php';

// Callback for HTTP GET /finalresults
//-- Supported filtering operation: by results id.
function handleGetAllResults(Request $request, Response $response, array $args) {
    $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        
    $results = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $result_model = new FinalResultsModel();

    $result_model->setPaginationOptions($input_page_number, $input_per_page);
    //----------------------------------------    
    $logging_model = new WSLoggingModel();
    //-- Get the decode JWT payload section. 
    $decoded_jwt = $request->getAttribute('decoded_token_data');
    $logging_model->logUserAction($decoded_jwt, "getListOfResults");
    //--------------------------------------
    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["id"])) {
        // Fetch the list of results matching the provided id.
        $results = $result_model->getResultsById($filter_params["id"]);
    } else {
        // No filtering by result id detected.
        $results = $result_model->getAll();
    }    
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($results, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleGetResultsById(Request $request, Response $response, array $args) {
    $results_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $result_model = new FinalResultsModel();

    // Retreive the results if from the request's URI.
    $resultID = $args["resultID"];
    if (isset($resultID)) {
        // Fetch the info about the specified result.
        $results_info = $result_model->getResultsById($resultID);
        if (!$results_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified fight.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($results_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleDeleteResultsById(Request $request, Response $response, array $args){
    $resultID = $args["resultID"];
    $result_model = new FinalResultsModel();
    $result_model->deleteResults($resultID);
    $response->getBody()->write("Deleted ".$resultID);
    return $response;
}