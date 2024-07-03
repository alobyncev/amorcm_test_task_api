<?php

namespace App\Errors;

use AmoCRM\Exceptions\AmoCRMApiErrorResponseException;
use AmoCRM\Exceptions\AmoCRMApiException;

class ErrorHandler
{
    public string $error = '';
    public function printError(AmoCRMApiException $e): void
    {
        $errorTitle = $e->getTitle();
        $code = $e->getCode();
        $debugInfo = var_export($e->getLastRequestInfo(), true);

        $validationErrors = null;
        if ($e instanceof AmoCRMApiErrorResponseException) {
            $validationErrors = var_export($e->getValidationErrors(), true);
        }

        $this->error = <<<EOF
Error: $errorTitle
Code: $code
Debug: $debugInfo
EOF;

        if ($validationErrors !== null) {
            $this->error .= PHP_EOL . 'Validation-Errors: ' . $validationErrors . PHP_EOL;
        }
    }
}