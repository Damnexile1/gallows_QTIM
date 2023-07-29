<?php
require_once 'Controller/WordController.php';

$controller = new WordController();
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


switch($uri) {

    case '/api/words':
        $controller->handle_words();
        break;

    case '/api/getWords':
        $controller->getWordsLen();
        break;

    case '/api/isFullWords':
        $controller->isFullWords();
        break;

    default:
        http_response_code(404);
        break;

}

