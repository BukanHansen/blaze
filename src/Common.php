<?php

use Blaze\Render\View;

function view($path, array $data = [])
{
    $view = new View($path, $data);
    return $view->render();
}
