<?php
/*
 * See license information at the package root in LICENSE.md
 */
namespace ion;

use Exception;
use Throwable;

class PhpHelperException extends Exception implements IPhpHelperException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}