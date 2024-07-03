<?php

namespace App\Errors;

use AmoCRM\Exceptions\AmoCRMApiException;

class LeadsChunkError extends ErrorHandler {

    public function printError(AmoCRMApiException $e): void
    {
        parent::printError($e);
        $this->error .= PHP_EOL . 'Chunk Number: ' . $e->getDescription() . PHP_EOL;
        echo '<pre>' . $this->error . '</pre>';
    }
}