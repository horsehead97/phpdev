<?php
require __DIR__ . "/inc/bootstrap.php";
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

 
if ((isset($uri[2]) && $uri[2] != 'product') || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
 
require PROJECT_ROOT_PATH . "/Controller/Api/GetProductsController.php";
require PROJECT_ROOT_PATH . "/dev/ServerLogger.php";
$xml=file_get_contents('php://input');
ServerLogger::log($xml);
$objFeedController = new GetProductsController();
$objFeedController->cors();
$strMethodName = $uri[3].'Prod'; 
$objFeedController->{$strMethodName}();

?>
