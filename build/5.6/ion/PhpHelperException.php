<?php
/*
 * See license information at the package root in LICENSE.md
 */
namespace ion;

use Exception;
use \Exception as Throwable;

class PhpHelperException extends Exception implements IPhpHelperException
{
    /**
     * method
     * 
     * 
     * @return mixed
     */
    
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}