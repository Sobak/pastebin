<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SpamAttemptException extends Exception
{
    public function report(): bool
    {
        return false;
    }

    public function render()
    {
        // Let's disguise it as a server crash and hope for the best
        throw new HttpException(500);
    }
}
