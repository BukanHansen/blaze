<?php

namespace Blaze;

use Blaze\Interface\RouterInterface;

class Router implements RouterInterface
{
    private $currentMethod = null;
    private $currentURI = null;
    private $routes = [];

    public function __construct() {}

    public function get(string $uri, callable | array $callback)
    {
        $uri = trim($uri, "/");
        $uri = preg_replace("/{[^}]}/", "(.+)", $uri);

        $this->routes[$uri] = ["GET", $callback];
    }

    public function post(string $uri, callable | array $callback)
    {
        $uri = trim($uri, "/");
        $uri = preg_replace("/{[^}]}/", "(.+)", $uri);

        $this->routes[$uri] = ["POST", $callback];
    }

    public function delete(string $uri, callable | array $callback)
    {
        $uri = trim($uri, "/");
        $uri = preg_replace("/{[^}]}/", "(.+)", $uri);

        $this->routes[$uri] = ["DELETE", $callback];
    }

    public function put(string $uri, callable | array $callback)
    {
        $uri = trim($uri, "/");
        $uri = preg_replace("/{[^}]}/", "(.+)", $uri);

        $this->routes[$uri] = ["PUT", $callback];
    }

    public function run(): void
    {
        $this->currentMethod = $_SERVER["REQUEST_METHOD"];
        $this->currentURI = trim($_SERVER["REQUEST_URI"], "/");

        $cb = null;
        $params = [];

        foreach ($this->routes as $route => $handler) {
            if ($handler[0] === $this->currentMethod) {
                if (preg_match("%^{$route}$%",  $this->currentURI, $matches) === 1) {

                    $cb = $handler[1];
                    unset($matches[0]);
                    $params = $matches;

                    break;
                }
            }
        }

        if (!$cb || !is_callable($cb)) {
            foreach ($this->routes as $route => $handler) {
                if ($this->currentURI === $route && $this->currentMethod === $handler[0]) {
                    $cb = $handler[1];
                }
            }
        }

        if (!$cb || $cb === null) {
            http_response_code(404);
            echo "404";
            return;
        }

        if (is_callable($cb)) {
            echo call_user_func($cb, ...$params);
        } elseif (is_array($cb)) {
            $newClass = new $cb[0]();
            echo $newClass->{$cb[1]}(...$params);
        }
        return;
    }
}
