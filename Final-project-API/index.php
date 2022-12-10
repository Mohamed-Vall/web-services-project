<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use GuzzleHttp\Client;

require_once './includes/app_constants.php';
require_once './includes/helpers/helper_functions.php';
require_once './includes/helpers/JWTManager.php';

require_once './includes/models/BaseModel.php';
require_once './includes/models/EventModel.php';
require_once './includes/models/FightsModel.php';
require_once './includes/models/FinalResultsModel.php';

//$client = new GuzzleHttp\Client(['verify' => false ]);

require_once './includes/helpers/WebServiceInvoker.php';
require_once './controllers/fightersController.php';
require __DIR__ . '/vendor/autoload.php';


//--Step 1) Instantiate App.
$app = AppFactory::create();
//upcomingCompositeResource();
//finishedCompositeResource();

$app->addBodyParsingMiddleware();

//-- Step 2) Add routing middleware.
$app->addRoutingMiddleware();
//-- Step 3) Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//-- Step 4)
// TODO: change the name of the sub directory here. You also need to change it in .htaccess

//$app->setBasePath("/web-services-project/Final-project-API/Final-Project-API"); #change this to subfolder if I need to change e.i. /.../music-api (has to match .htaccess)
$app->setBasePath("/web-services-project/Final-project-API"); #change this to subfolder if I need to change e.i. /.../music-api (has to match .htaccess)
$uri_api = "https://api.sportsdata.io/v3/mma/scores/json/Fighters?key=a541ac55a76a48d1add34c4639da10ec";
//-- Step 5) Include the files containing the definitions of the callbacks.
require_once './includes/routes/fighter_routes.php';
require_once './includes/routes/event_routes.php';
require_once './includes/routes/finalresult_routes.php';
require_once './includes/routes/fights_routes.php';
require_once './includes/routes/token_routes.php';
//-- Step 6)
// TODO: And here we define app routes.

//-- Send the request.
$response = $client->get($uri_api);

//echo $response->getBody()->getContents();exit;
//-- Send the request.
$data = $response->getBody()->getContents();
$fighters = json_decode($data, true);
$fighters_model = new FighterModel();
foreach ($fighters as $fighter) {    
    $new_fighter = Array(
        "name" => $fighter["FirstName"] . " ''" . $fighter["Nickname"] . "'' ".$fighter["LastName"],
        "weightClass" => $fighter["WeightClass"],
        "records" => $fighter["Wins"] . " - " .$fighter["Losses"]    
    );
    $fighters_model->createFighter($new_fighter);
    //echo $fighter["FirstName"] . " " . $fighter["LastName"] . "<br>";
}

$jwt_secret = JWTManager::getSecretKey();
$api_base_path = "/web-services-project/Final-project-API";
$app->add(new Tuupola\Middleware\JwtAuthentication([
            'secret' => $jwt_secret,
            'algorithm' => 'HS256',
            'secure' => false, // only for localhost for prod and test env set true            
            "path" => $api_base_path, // the base path of the API
            "attribute" => "decoded_token_data",
            "ignore" => ["$api_base_path/token", "$api_base_path/account"],
            "error" => function ($response, $arguments) {
                $data["status"] = "error";
                $data["message"] = $arguments["message"];
                $response->getBody()->write(
                        json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
                );
                return $response->withHeader("Content-Type", "application/json;charset=utf-8");
            }
        ]));


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

$app->get("/fights", "handleGetFights");
$app->get("/fights/{fightsid}", "handleGetfightById");
$app->post("/fights/create", "handleCreatefight");
$app->delete("/fights/delete/{fightsid}", "handleDeleteFightById");

//For comp resources
$app->get("/upcomingcomp", upcomingCompositeResource());
$app->get("/finishedcomp", finishedCompositeResource());

$app->post("/token", "handleGetToken");
$app->post("/account", "handleCreateUserAccount");

$app->get('/', ['verify' => true]);

// Run the app.
$app->run();

function upcomingCompositeResource() {
    $fighterEvent = Array();
    // Get books data from the Ice and Fire API.
    $externalEvent = new FightersController();
    $external = $externalEvent->getExternalInfo();
    // Get the list of artists.    
    $event_model = new FightsModel();
    $events = $event_model->getAllFights();

    // Combine the data sets.
    $fighterEvent["internal"] = $events;
    $fighterEvent["external"] = $external;
    $jsonData = json_encode($fighterEvent, JSON_INVALID_UTF8_SUBSTITUTE);
    echo $jsonData;
}

function finishedCompositeResource() {
    $fighterEvent = Array();
    // Get books data from the Ice and Fire API.
    $externalEvent = new FightersController();
    $external = $externalEvent->getExternalInfo();//$id);
    // Get the list of artists.    
    $event_model = new EventModel();        
    $events = $event_model->getAll();

    // Combine the data sets.
    $fighterEvent["internal"] = $events;
    $fighterEvent["external"] = $external;
    $jsonData = json_encode($fighterEvent, JSON_INVALID_UTF8_SUBSTITUTE);
    echo $jsonData;
}