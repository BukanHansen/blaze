<?php

namespace Blaze\Interface;

interface RouterInterface
{
    public function __construct();
    public function get(string $uri, callable | array $callback);
    public function post(string $uri, callable | array $callback);
    public function delete(string $uri, callable | array $callback);
    public function put(string $uri, callable | array $callback);
    public function run(): void;
}
