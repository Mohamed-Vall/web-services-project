<?php
namespace app\controllers;

include "\\xampp\\htdocs\\vendor\\autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class ErrorPages {
	public function __construct() {
		// Create the logger
		$this->logger = new Logger('ErrorPages');
		
		// Add handlers
		$this->logger->pushHandler(new StreamHandler('error.log', Logger::WARNING));
		$this->logger->pushHandler(new FirePHPHandler());
	}
	public function badRequest($request, $response) {
		$this->logger->warning('400 Bad Request');
		return $response->withStatus(404)->write('Bad Request');
	}	
	public function unauthorized($request, $response) {
		$this->logger->error('401 Unauthorized');
		return $response->withStatus(500)->write('Unauthorized');
	}	
	public function notFound($request, $response) {
		$this->logger->warning('404 Page not found');
		return $response->withStatus(404)->write('Page not found');
	}
	public function internalServerError($request, $response) {
		$this->logger->error('500 Internal Server Error');
		return $response->withStatus(500)->write('Internal Server Error');
	}
}
