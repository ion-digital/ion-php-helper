<?php
/*
 * See license information at the package root in LICENSE.md
 */
namespace Ion;

use Exception;
use Throwable;
class PhpHelperException extends Exception implements PhpHelperExceptionInterface
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}