<?php include(__DIR__ . "/../src/Loader.php");

use Blaze\Router;
use App\Controller\Home;

$router = new Router();

$router->get("/", [Home::class, "render"]);
$router->run();