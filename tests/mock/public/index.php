<?php
/**
 * User: coderd
 * Date: 2017/6/28
 * Time: 15:12
 */

use Ra\App;

require __DIR__ . '/../../../vendor/autoload.php';

$uriPatterns = [
    '/json' => ['GET'],
];

$app = new App($uriPatterns, "tests\\mock\\App\\Resource\\");
$app->matchUriPattern()
    ->callResourceAction()
    ->respond();
