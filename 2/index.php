<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/core/db.php';

define('BASE_PATH', '/forexfalcon/2');
define('VIEWS_PATH', __DIR__.'/app/views');
define('LINKS_PATH', '/forexfalcon/2/public');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

require_once __DIR__ . '/router/web.php';
