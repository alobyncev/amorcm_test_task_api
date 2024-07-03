<?php

namespace App;

require __DIR__ . '/vendor/autoload.php';

use App\Services\LeadService;

define('TOKEN_FILE', DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'token_info.json');

function index(): void
{
    $env = file_get_contents(__DIR__."/.env");
    $lines = explode("\n",$env);

    foreach($lines as $line) {
        preg_match("/([^#]+)=(.*)/", $line, $matches);

        if(isset($matches[2])) {
            putenv(trim($line));
        }
    }

    (new LeadService())->create();
}

index();