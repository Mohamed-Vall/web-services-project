<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require_once './includes/app_constants.php';
require_once './includes/helpers/helper_functions.php';
require_once './helpers/WebServiceInvoker.php';
require_once './controllers/fightersController.php';

//--Step 1) Instantiate App.
$app = AppFactory::create();
upcomingCompositeResource();
//finishedCompositeResource();

$app->addBodyParsingMiddleware();

//-- Step 2) Add routing middleware.
$app->addRoutingMiddleware();
//-- Step 3) Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//-- Step 4)
// TODO: change the name of the sub directory here. You also need to change it in .htaccess

$app->setBasePath("/web-services-project/Final-Project-API"); #change this to subfolder if I need to change e.i. /.../music-api (has to match .htaccess)

//-- Step 5) Include the files containing the definitions of the callbacks.
require_once './includes/routes/fighter_routes.php';
require_once './includes/routes/event_routes.php';
require_once './includes/routes/finalresult_routes.php';
require_once './includes/routes/fights_routes.php';

//-- Step 6)
// TODO: And here we define app routes.

$app->delete("/fighters/delete/{fighter_id}", "handleDeleteFighterById");
$app->post("/fighters/create", "handleCreateFighters");
$app->get("/fighters", "handleGetAllFighters");
$app->get("/fighters/{fighter_id}", "handleGetFighterById");


$app->delete("/results/delete/{resultID}", "handleDeleteResultsById");
$app->get("/results", "handleGetAllResults");
$app->get("/results/{resultID}", "handleGetResultsById");

$app->delete("/events/delete/{liveId}", "handleDeleteEventsById");
$app->get("/events", "handleGetAllResults");
$app->get("/events/{liveId}", "handleGetEventsById");
$app->get("/events/fight/{fighterId}", "handleGetEventsByFighterId");

$app->get("/fights/{fightsid}", "handleGetfightById");
$app->post("/fights/create", "handleCreatefight");
$app->delete("/fights/delete/{fightsid}", "handleDeleteFightById");

// Run the app.
$app->run();

function upcomingCompositeResource() {
    $fighterEvent = Array();
    // Get books data from the Ice and Fire API.
    $event = new FightersController();
    $fighters = $event->getBooksInfo();
    // Get the list of artists.    
    $event_model = new EventModel();        
    $events = $event_model->getAll();

    // Combine the data sets.
    $fighterEvent["fighter"] = $fighters;
    $fighterEvent["events"] = $events;
    $jsonData = json_encode($fighterEvent, JSON_INVALID_UTF8_SUBSTITUTE);
    echo $jsonData;
}

function finishedCompositeResource() {
    $fighterEvent = Array();
    // Get books data from the Ice and Fire API.
    $event = null;//new IceAndFireController();
    $fighters = $event->getBooksInfo();
    // Get the list of artists.    
    $event_model = new EventModel();        
    $events = $event_model->getAll();
    
    // Combine the data sets.
    $fighterEvent["fighter"] = $fighters;
    $fighterEvent["events"] = $events;
    $jsonData = json_encode($fighterEvent, JSON_INVALID_UTF8_SUBSTITUTE);
    echo $jsonData;
}