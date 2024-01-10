<?php

/*
 * See license information at the package root in LICENSE.md
 */

namespace Ion;

/**
 * Description of PhpHelper
 *
 * @author Justus
 */
use \ReflectionObject;
use \ReflectionProperty;
use \ReflectionMethod;
use \Exception;
use \Throwable;
use \Error;
use \Countable;
use \Serializable;
use \Closure;

class PhpHelper implements PhpHelperInterface {

    /**
     * Checks to see if an array is an associative array or not.
     *
     * @since 0.0.1
     * 
     * @param array $array The array to check.
     * @return bool Returns __true__ if the array is an associative array, __false__ if not.
     *
     */    
    
    public static function isAssociativeArray(array $array): bool {
        
        if (!is_array($array)) {
            return false;
        }

        if ([] === $array) {
            return false;
        }

        return (bool) (array_keys($array) !== range(0, count($array) - 1));
        //return (bool) (array_keys($array) !== array_values($array));
    }

    /**
     * Checks to see if a value is empty or not - additionally includes special handling for strings.
     *
     * @since 0.0.2
     * 
     * @param mixed $variable The variable to check.
     * @param bool $orWhiteSpaceIfString Return __true__ if _$value_ is a string and is empty or consists out of white-space.
     * @param bool $orEmptyIfArray Return __true__ if $value is an empty array.
     * @return bool Returns __true__ if the variable is empty, __false__ if not.
     *
     */
    
    public static function isEmpty(/* mixed */ $value, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true): bool {

        if (static::isArray($value) && count($value) === 0 && $orEmptyIfArray) {
            return true;
        }

        if (static::isArray($value) && count($value) === 0) {
            return false;
        }

        if (is_string($value) && $orWhiteSpaceIfString === true) {
            return (bool) empty(trim($value));
        }

        return (bool) empty($value);
    }

    /**
     * Checks to see if a class inherits another class.
     *
     * @since 0.0.9
     * 
     * @param string $childClassName The name of the class to be checked.
     * @param string $parentClassName The name of the class to validate as a parent.
     * @return bool Returns __true__ if the child class inherits the parent class, __false__ if not.
     *
     */    
    
    public static function inherits(string $childClassName, string $parentClassName): bool {

        return is_subclass_of($childClassName, $parentClassName, true);
    }
    
    /**
     * Checks to see if a variable is an object, or an object of a certain type.
     *
     * @since 0.0.9
     * 
     * @param mixed $variable The variable that needs to be checked.
     * @param string $className The name of the class to validate.
     * @param bool $parent If set to __true__, will validate if class to validate is a parent class - otherwise it will check if $variable is of type $className.
     * @param bool $class If set to __true__ and $parent is set to __true__, the specified class will be included in the check.
     * @return bool Returns __true__ if the variable is an object and if child class inherits the parent class (if $parentClassName is specified), __false__ if not.
     *
     */ 

    public static function isObject(/* mixed */ $variable, string $className = null, bool $parent = true, bool $class = true): bool {

        if (!is_object($variable)) {
            return false;
        }

        if ($className !== null && $parent === true) {

            if ($class === true) {
                return (is_a($variable, $className, true) || static::inherits(get_class($variable), $className));
            }

            return static::inherits(get_class($variable), $className);
        }

        if ($className !== null && $parent === false) {
            return get_class($variable) === $className;
        }

        return true;
    }
    
    /**
     * Checks to see if a variable is an array - and additionally will filter for either associative or flat arrays, both or neither.
     *
     * @since 0.3.4
     * 
     * @param mixed $variable The variable to check.
     * @param bool $isAssociative Include associative arrays in the result.
     * @return bool Returns __true__ if the array is an array that matches the parameters, __false__ if not.
     *
     */
    
    public static function isArray(/* mixed */ $variable, bool $isAssociative = true): bool {

        if (!is_array($variable)) {
            return false;
        }

        if (static::isAssociativeArray($variable) && !$isAssociative) {
            return false;
        }

        return true;
    }

    public static function isString(/* mixed */ $variable = null): bool {

        if ($variable === null) {
            return false;
        }

        return is_string($variable);
    }

    public static function isReal(/* mixed */ $variable = null): bool {

        if ($variable === null) {

            return false;
        }

        return static::isFloat($variable) || static::isDouble($variable);
    }

    public static function isFloat(/* mixed */ $variable = null): bool {

        return is_float($variable);
    }

    public static function isDouble(/* mixed */ $variable = null): bool {

        return is_double($variable);
    }

    public static function isNumeric(/* mixed */ $variable = null): bool {

        return (static::isInt($variable) || static::isReal($variable));
    }

    public static function hasDecimals(/* mixed */ $variable = null): bool {

        if (!static::isReal($variable)) {

            return false;
        }

        if ($variable === 0) {

            return false;
        }

        if (floor((float) $variable) !== (float) $variable) {

            return true;
        }


        return false;
    }

    public static function isInt(/* mixed */ $variable = null): bool {

        if ($variable === null) {
            return false;
        }

        return is_int($variable);
    }

    public static function isBool(/* mixed */ $variable = null): bool {

        if ($variable === null) {
            return false;
        }

        return is_bool($variable);
    }

    public static function isNull(/* mixed */ $variable = null): bool {

        return $variable === null;
    }

    public static function isCallable(/* mixed */ $variable = null): bool {

        return is_callable($variable);
    }

    public static function isType(string $typeString, /* mixed */ $variable = null): bool {

        switch (strtolower(trim($typeString))) {

            case 'null': {

                    return ($variable === null);
                }

            case 'array': {

                    return static::isArray($variable);
                }

            case 'int': {

                    return static::isInt($variable);
                }

            case 'real':
            case 'double':
            case 'float': {

                    return static::isReal($variable);
                }

            case 'string': {

                    return static::isString($variable);
                }

            case 'bool': {

                    return static::isBool($variable);
                }

            case 'object': {

                    return static::isObject($variable);
                }
        }

        return static::isObject($variable, $typeString, true, true);
    }

//    public static function suppress(callable $closure, bool $includeExceptions = false, bool $convertToExceptions = true, int $errorLevel = 0) {
//        
//        $prevErrorLevel = error_reporting();
//        error_reporting($errorLevel);
//             
//        $error = null;
//        
//        try {
//            
//            $result = $closure();
//            
//        } 
//        catch (Error $thrownError) {
//            
//            $error = [
//            
//                'type' => (int) E_ERROR,
//                'message' => (string) $thrownError->getMessage(),
//                'file' => (string) $thrownError->getFile(),
//                'line' => (int) $thrownError->getLine()
//                
//            ];
//        }
//        catch (Exception $exception) {
//
//            if(!$includeExceptions) {
//                
//                throw $exception;
//            }
//            
//        } finally {
//                        
//            error_reporting($prevErrorLevel);
//        }
//        
//        if($error === null) {
//            
//            $error = error_get_last();
//        }
//        
//        if($error !== null && $convertToExceptions) {
//            
//            error_clear_last();
//            
//            switch((int) $error['type']) {
//                
//                case E_NOTICE: {
//                    
//                    throw new NoticeException($error['message'], $error['file'], $error['line']);
//                }
//                
//                case E_WARNING: {
//                    
//                    throw new WarningException($error['message'], $error['file'], $error['line']);
//                }
//                
//                case E_ERROR: {
//                    
//                    throw new ErrorException($error['message'], $error['file'], $error['line']);
//                }
//            }
//            
//        }
//
//        return $result;
//    }
//    
    private static function toScalar(/* mixed */ $variable, callable $callBack, bool $allowDeserialization = false) {

        if (static::isObject($variable)) {

            return null;
        }
        
        if (static::isString($variable) && $allowDeserialization) {            
            
            $tmp = json_decode($variable, JSON_OBJECT_AS_ARRAY);

            if (!self::isEmpty($tmp)) {

                return self::toScalar($tmp, $callBack, false);
            }

            try {
                
                $tmp = self::unserialize($variable, true);
                
                if($tmp === null) {
                    
                    return $callBack($variable);
                }
                
                return self::toScalar($tmp, $callBack, false);
            
            } catch(PhpHelperException $exception) {
                
                return self::toScalar($variable, $callBack, false);
            }
        }

        // This must be last, to make sure we try to deserialize this first (if allowed).

        $tmp = $callBack($variable);

        if ($tmp !== null) {

            return $tmp;
        }

        return null;
    }

    public static function toArray(/* mixed */ $variable = null, bool $allowDeserialization = false, string $splitCharacterIfString = null): ?array {

        return static::toScalar($variable, function($variable) use ($splitCharacterIfString, $allowDeserialization) {
                                
            if($variable === null) {

                return null;
            }

            if (static::isArray($variable)) {

                return $variable;
            }

            if (static::isString($variable)) {

                if($variable === '') {
                    
                    return [];
                }
                
                if (strpos($variable, $splitCharacterIfString, 0) !== false && $splitCharacterIfString !== null) {

                    return explode($splitCharacterIfString, $variable);
                }
            }

            return [ $variable ];

        }, $allowDeserialization);
    }

    public static function toString(/* mixed */ $variable = null, bool $allowDeserialization = false, string $joinCharacterIfArray = null): ?string {

        return static::toScalar($variable, function($variable) use ($joinCharacterIfArray, $allowDeserialization) {

            if($variable === null) {

                return null;
            }

            if (static::isString($variable)) {

                return $variable;
            }

            if (static::isArray($variable)) {

                return implode(($joinCharacterIfArray === null ? '' : $joinCharacterIfArray), $variable);
            }

            if(static::isBool($variable)) {
                
                return ($variable ? '1' : '' );
            }
            
            return (string) $variable;

        }, $allowDeserialization);
    }

    public static function toFloat(/* mixed */ $variable = null, bool $allowDeserialization = false): ?float {

        return static::toScalar($variable, function($variable) use ($allowDeserialization) {
            
            if($variable === null) {

                return null;
            }
            
            if (static::isArray($variable)) {

                return null;
            }            

            if (static::isFloat($variable)) {

                return $variable;
            }
            
            if (static::isBool($variable)) {
                
                return ($variable ? 1.0 : 0.0);
            }

            return (float) $variable;

        }, $allowDeserialization);
    }

    public static function toInt(/* mixed */ $variable = null, bool $allowDeserialization = false): ?int {

        return static::toScalar($variable, function($variable) use ($allowDeserialization) {
            
            if($variable === null) {

                return null;
            }

            if (static::hasDecimals($variable)) {

                return null;
            }

            if (static::isInt($variable)) {

                return $variable;
            }
            
            if (static::isBool($variable)) {
                
                return ($variable ? 1 : 0);
            }

            if (static::isString($variable)) {

                if (static::hasDecimals(static::toFloat($variable, $allowDeserialization))) {

                    return null;
                }

            }

            return (int) $variable;

        }, $allowDeserialization);
    }

    public static function toBool(/* mixed */ $variable = null, bool $allowDeserialization = false, bool $checkElementsIfArray = false): ?bool {

        return static::toScalar($variable, function($variable) use ($allowDeserialization, $checkElementsIfArray) {

                    if($variable === null) {
                        
                        return null;
                    }
            
                    if (static::isBool($variable)) {

                        return (bool) $variable;
                    }
      
                    if(static::isCountable($variable)) {
                        
                        if(!$checkElementsIfArray) {
                            
                            if(static::count($variable) === 0) {

                                return false;
                            }               
                            
                            return true;
                        }
                        
                        $t = 0;
                        $f = 0;
                        
                        if(static::count($variable) === 0) {
                            
                            return null;
                        }

                        foreach($variable as $index => $value) {
                            
                            if(self::toBool($value, $allowDeserialization, $checkElementsIfArray) === true) {
                                
                                $t++;
                                continue;
                            }
                            
                            $f++;
                        }
                        
                        if($t === $f) {
                            
                            return null;
                        }
                        
                        return ($t > $f);
                    }

                    if (static::isString($variable)) {

                        if(static::isEmpty($variable, true)) {
                            
                            return false;
                        }
                        
                        switch (trim(strToLower($variable))) {

                            case 'enabled':
                            case 'enable':
                            case '1':
                            case 'true':
                            case 'on':
                            case 'yes':
                            case 'y': {

                                return true;
                            }

                            case 'disabled':
                            case 'disable':
                            case '0':
                            case 'false':
                            case 'off':
                            case 'no':
                            case 'n': {

                                return false;
                            }
                        }
                        
                        return (bool) $variable;
                    }
                    
                    if(static::isInt($variable) || static::isFloat($variable)) {
                        
                        if($variable > 0) {
                            
                            return true;
                        }
                        
                        return false;
                    }

                    return null;
                    
                }, $allowDeserialization);
    }
    
    /**
     * 
     * Return the non-static properties of an instantiated object.
     * 
     * @since 0.2.2
     * 
     * @param object $object The object for which to return the properties of.
     * @param bool $public Return public properties.
     * @param bool $protected Return protected properties.
     * @param bool $private Return private properties.
     * 
     * @return array Return the properties and their values as an associative array.
     */    

    public static function getObjectProperties(object $object, bool $public = true, bool $protected = false, bool $private = false): array {

        $reflector = new ReflectionObject($object);

        $propertyFilter = ($public ? ReflectionProperty::IS_PUBLIC : 0) | ($protected ? ReflectionProperty::IS_PROTECTED : 0) | ($private ? ReflectionProperty::IS_PRIVATE : 0);

        $result = [];

        foreach ($reflector->getProperties($propertyFilter) as $property) {
            $result[$property->getName()] = $property;
        }

        return $result;
    }
    
    /**
     * 
     * Return the non-static property names and values of an instantiated object.
     * 
     * @param object $object The object for which to return the properties of.
     * @param bool $public Return public properties.
     * @param bool $protected Return protected properties.
     * @param bool $private Return private properties.
     * 
     * @return array Return the properties and their values as an  associative array.
     */    

    public static function getObjectPropertyValues(object $object, bool $public = true, bool $protected = false, bool $private = false): array {

        $properties = static::getObjectProperties($object, $public, $protected, $private);

        $result = [];

        foreach ($properties as $name => $property) {

            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }

            $result[$name] = $property->getValue($object);
        }

        return $result;
    }
    
    /**
     * 
     * Return the non-static methods of an instantiated object.
     * 
     * @since 0.2.2
     * 
     * @param object $object The object for which to return the methods of.
     * @param bool $public Return public methods.
     * @param bool $protected Return protected methods.
     * @param bool $private Return private methods.
     * @param bool $abstract Return private methods.
     * @param bool $final Return private methods.
     * 
     * @return array Return the methods and their callables as an  associative array.
     */    

    public static function getObjectMethods(object $object, bool $public = true, bool $protected = false, bool $private = false, bool $abstract = true, bool $final = true): array {

        $reflector = new ReflectionObject($object);

        $methodFilter = ($public ? ReflectionMethod::IS_PUBLIC : 0) | ($protected ? ReflectionMethod::IS_PROTECTED : 0) | ($private ? ReflectionMethod::IS_PRIVATE : 0) | ($abstract ? ReflectionMethod::IS_ABSTRACT : 0) | ($final ? ReflectionMethod::IS_FINAL : 0);

        $result = [];

        foreach ($reflector->getMethods($methodFilter) as $method) {

            $result[$method->getName()] = $method;
        }

        return $result;
    }
    
    /**
     * 
     * Return a unique hash that represents the properties of this object.
     * 
     * @param array $array The array for which to return the hash for.
     * 
     * @return int Return the hash as an int.
     */       

    public static function getArrayHash(array $array): int {

        $sig = [(string) count($array)];

        if (static::isAssociativeArray($array)) {

            $sig[] = (string) static::getArrayHash(array_keys($array));
        }

        foreach (array_values($array) as $value) {

            if (static::isObject($value)) {

                $sig[] = (string) static::getObjectHash($value);

                continue;
            }

            if (static::isArray($value)) {

                if ($array !== $value) {

                    $sig[] = (string) static::getArrayHash($value);
                }

                continue;
            }

            if ($value === null) {

                $sig[] = 'NULL';
                continue;
            }

            $sig[] = (string) $value;
        }

        return crc32(join('', $sig));
    }

    /**
     * 
     * Return a unique hash that represents the properties of this object.
     * 
     * @param object $object The object for which to return the hash for.
     * 
     * @return int Return the hash as an int.
     */
    
    public static function getObjectHash(object $object): int {

        $sig = [get_class($object)];

        $properties = static::getObjectPropertyValues($object, true, true, true);

//        $sig[] = static::getArrayHash($properties);

        foreach ($properties as $property => $value) {

            if (static::isObject($value)) {

                if ($object != $value) {

                    $sig[] = (string) static::getObjectHash($value);
                }

                continue;
            }

            if (static::isArray($value)) {

                $sig[] = (string) static::getArrayHash($value);
                continue;
            }

            if ($value === null) {

                $sig[] = 'NULL';
                continue;
            }

            $sig[] = (string) $value;
        }

        return crc32(join('', $sig));
    }
    
    /**
     * Returns the value of the $_SERVER['REQUEST_URI'] variable.
     * 
     * @param bool $includeHost Include the host.
     * @param bool $includeProtocol Include the protocol.
     * @return ?string Return the value.
     * 
     */    

    public static function getServerRequestUri(
            
        bool $includeHost = false,
        bool $includeProtocol = true
            
    ): ?string {

        if (!static::isWebServer()) {
            
            return null;
        }

        $host = null;
        $protocol = null;
        
        $path = (string) filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);

        if (static::isEmpty($path, true)) {
            
            $path = (string) $_SERVER['REQUEST_URI'];
        }
        
        if($includeProtocol) {
        
            $https = (string) filter_input(INPUT_SERVER, 'HTTPS', FILTER_DEFAULT);
            
            if (static::isEmpty($https, true)) {
                
                $https = (array_key_exists('HTTPS', $_SERVER) ? (string) $_SERVER['HTTPS'] : null);

            } 
            
            $protocol = (string) (static::toBool($https) ? "https" : "http");                        
        }
        
        if($includeHost) {
            
            $host = (string) filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_DEFAULT);

            if (static::isEmpty($host, true)) {

                $host = (array_key_exists('HTTP_HOST', $_SERVER) ? (string) $_SERVER['HTTP_HOST'] : null);
            }        
        }        

        return 
        
            (!self::isEmpty($protocol) && !self::isEmpty($host) ? "{$protocol}://" : "") . 
            (!self::isEmpty($host) ? $host : "") .
            $path;
    }
    
    /**
     * 
     * Returns the value of the $_SERVER['HTTP_REFERER'] variable.
     * 
     * @return string|null
     */
        
    
    public static function getServerReferrerUri(): ?string {
        
        $tmp = self::toString(self::filterInput('HTTP_REFERER', [ INPUT_SERVER ], FILTER_DEFAULT));
        
        if(!self::isEmpty($tmp)) {
            
            return $tmp;
        }
            
        if(!array_key_exists('HTTP_REFERER', $_SERVER)) {
            
            return null;
        }
        
        return self::toString($_SERVER['HTTP_REFERER']);
    }
    
    /**
     * Returns the value of the $_SERVER['DOCUMENT_ROOT'] variable.
     * 
     * @return ?string Return the value.
     */    

    public static function getServerDocumentRoot(): ?string {

        if (!static::isWebServer()) {
            return null;
        }

        $uri = (string) filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_DEFAULT);

        if (static::isEmpty($uri, true)) {
            $uri = (string) $_SERVER['DOCUMENT_ROOT'];
        }

        if (static::isEmpty($uri, true)) {
            return null;
        }

        return (string) $uri . DIRECTORY_SEPARATOR;
    }

    /**
     * Return whether the current script is running in a command-line context, or somewhere else (like a web server).
     * 
     * @return bool Returns __true__ if we are running as a command-line script - __false__ otherwise. 
     */    
    
    public static function isCommandLine(): bool {
        if (php_sapi_name() === 'cli') {
            return true;
        }

        return false;
    }
    
    /**
     * Return whether the current script is running in a Web context, or somewhere else (like the command-line).
     * 
     * @return bool Returns __true__ if we are running as a Web script - __false__ otherwise. 
     */    

    public static function isWebServer(): bool {

        return !static::isCommandLine();
    }

    /**
     * Return whether a variable is countable.
     * 
     * @return bool Returns __true__ if it is, __false__ otherwise. 
     */    
    
    public static function isCountable($variable): bool {

        if (PHP_MAJOR_VERSION >= 7 && PHP_MINOR_VERSION >= 3) {

            return (bool) is_countable($variable);
        }

        if (static::isObject($variable)) {

            return static::inherits(get_class($variable), \Countable::class);
        }

        return static::isArray($variable);
    }

    
    /**
     * Returns a NULL if that value is null or false - otherwise, the value.
     * 
     * @param mixed $variable The variable to convert.
     */
        
    
    public static function toNull(/* mixed */ $variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true) {

        if (!static::isEmpty($variable, $orWhiteSpaceIfString, $orEmptyIfArray)) {

            return $variable;
        }

        return null;
    }    

    /**
     * Returns the first result of a single or multiple calls to filter_input() and validates input parameters.
     * 
     * @param string $variableName The 'key' to retrieve.
     * @param array $inputs An array of input parameters to call filter_input() with - for valid inputs see: http://php.net/manual/en/function.filter-input.php
     * @param array $filters The filter to apply to each iteration.
     * @param array $options The options to apply to each iteration.
     * 
     * @return bool Returns __true__ if $variable is countable, __false__ otherwise.
     */    
    
//TODO: Read https://stackoverflow.com/questions/25232975/php-filter-inputinput-server-request-method-returns-null and evaluate this method.    
    
    public static function filterInput(string $variableName, array $inputs = [], int $filter = null, array $options = []) {

        if ($inputs === []) {

            $inputs = [INPUT_POST, INPUT_GET];
        }

        if ($filter === null) {

            $filter = FILTER_DEFAULT;
        }

        if (static::isAssociativeArray($inputs)) {

            throw new PhpHelperException("Inputs must not be an associative array.");
        }

        foreach ($inputs as $input) {

            if (!static::isInt($input)) {

                throw new PhpHelperException("Each input must be of type 'int.'");
            }
        }

        if (!static::isInt($filter)) {

            throw new PhpHelperException("The filter must be of type 'int.'");
        }


        if ($options !== [] && !static::isAssociativeArray($options)) {

            throw new PhpHelperException("Options must be an associative array.");
        }

        foreach ($options as $option) {

            if (static::isInt($option)) {

                continue;
            }

            throw new PhpHelperException("Options must be of type 'int.'");
        }

        foreach ($inputs as $i => $input) {

            $result = filter_input($input, $variableName, $filter, $options);

            if (($filter !== FILTER_NULL_ON_FAILURE && $result === false) || ($filter === FILTER_NULL_ON_FAILURE && $result === null)) {

                throw new PhpHelperException("Input filter for '{$variableName}' failed.");
            }

            if (($result !== null) && (($filter !== FILTER_NULL_ON_FAILURE && $result !== false) || ($filter === FILTER_NULL_ON_FAILURE && $result !== null))) {

                return $result;
            }
        }

        return null;
    }
    
   
    /**
     * Capture the output from the output buffer - basically another way of capturing output via ob_start() and ob_get_clean().
     * 
     * @param callable $closure The callable to execute.
     * @param ... $parameters The parameters to execute the callable with.
     * 
     * @return ?string The output captured from the executed callable.
     * 
     */
        

    public static function obGet(callable $closure, ...$parameters): ?string {

        try {
            
            ob_start();
            call_user_func_array($closure, $parameters);
            
            return static::toNull((string) ob_get_clean(), false, true);
        }
        catch(Throwable $th) {
        
            ob_end_clean();
            throw $th;
        }
    }
    
   /**
    * Check if a string ends with a substring.
    * 
    * @param string $string The input string to check.
    * @param string $subString The substring to check for.
    * 
    * @return bool Returns __true__ if $string ends with $subString, __false__ if not.
    */    

    public static function strEndsWith(string $string, string $subString): bool {

        if (!static::strContains($string, $subString)) {

            return false;
        }

        if (strrpos($string, $subString) === strlen($string) - strlen($subString)) {

            return true;
        }

        return false;
    }
    
   /**
    * Check if a string starts with a substring.
    * 
    * @param string $string The input string to check.
    * @param string $subString The substring to check for.
    * 
    * @return bool Returns __true__ if $string starts with $subString, __false__ if not.
    */    

    public static function strStartsWith(string $string, string $subString): bool {

        if (!static::strContains($string, $subString)) {

            return false;
        }

        if (strpos($string, $subString) === 0) {

            return true;
        }

        return false;
    }
    
   /**
    * Count the number of elements in a Countable. This method does the same as count(), except that it returns __NULL__ if the $variable is not countable.
    * 
    * @param mixed $variable The variable to count.
    * 
    * @return ?int Returns the count of the elements if $variable is countable, otherwise __NULL__.
    *
    */    

    public static function count(/* mixed */ $variable): ?int {

        if (!static::isArray($variable) && !static::isCountable($variable)) {

            return null;
        }

        return count($variable);
    }
    
   /**
    * Checks if a string contains a substring. If $position is specified, it will check for a substring at a specific position.
    * 
    * @param string $string The string to check.
    * @param string $subString The substring to check for.
    * @param int $position The position in $string to check at.
    * 
    * @return bool Returns __true__ if $string contains $subString (if $position is not specified), __true__ if $string contains $substring at the specified position (if $position is specified), or __false_ otherwise.
    */    

    public static function strContains(string $string, string $subString, int $position = null): bool {

        if (strpos($string, $subString) === false) {

            return false;
        }

        if ($position !== null && ($position > strlen($string) - strlen($subString))) {

            return false;
        }

        if ($position !== null) {

            return (strpos($string, $subString, 0) === $position);
        }

        return (strpos($string, $subString) !== false);
    }

   /**
    * Returns the path of the calling method or function.
    * 
    * @return string The path to the calling PHP code file.
    * 
    */    
    
    public static function getCallingPath(): string {

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (static::count($trace) === 0 || (static::count($trace) > 0 && !array_key_exists('file', $trace[count($trace) - 1]))) {

            throw new PhpHelperException("Could not determine the calling method / function's file path (i.e. the code file that contains it).");
        }

        return realpath($trace[count($trace) - 1]['file']);
    }
    
   /**
    * Returns the name of the class from where the current function/method was called.
    * 
    * @return string The name of the class from where the containing function/method was called.
    * 
    */    

    public static function getCallingClass(): string {

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (static::count($trace) === 0 || (static::count($trace) > 0 && !array_key_exists('class', $trace[count($trace) - 1]))) {

            throw new PhpHelperException("Could not determine the calling class' name.");
        }

        return $trace[count($trace) - 1]['class'];
    }

   /**
    * Replace multiple strings.
    * 
    * @param array $strings The strings to look for (the needles).
    * @param string $replacement The replacement string.
    * @param string $subject The subject to modify (the haystack).
    * @param bool $ignoreCase If __true__ case is ignored, if __false__ case is taken into consideration.
    * @return string The modified subject.
    */
    
    public static function strReplaceAll(array $strings, string $replacement, string $subject, bool $ignoreCase = false, int &$count = null): string {

        if ($count !== null) {

            $count = 0;
        }

        foreach ($strings as $string) {

            while ($ignoreCase ? stripos($subject, (string) $string) !== false : strpos($subject, (string) $string) !== false) {

                $tmp = 0;

                $subject = ($ignoreCase ? str_ireplace((string) $string, $replacement, $subject, $tmp) : str_replace((string) $string, $replacement, $subject, $tmp));

                if ($count !== null) {

                    $count += $tmp;
                }
            }
        }

        return $subject;
    }

   /**
    * Strip white-space from a string.
    * 
    * @param string $subject The subject to modify.
    * @param string $replaceWith The string to replace white-space instances with.
    * @return string The resulting string.
    */
    
    public static function strStripWhiteSpace(string $subject, string $replaceWith = ' ' /* one space */): string {

        if ($replaceWith !== ' ') {
            return static::strReplaceAll(["\n", "\r", "\t", "  ", " "], $replaceWith, $subject);
        }

        return static::strReplaceAll(["\n", "\r", "\t", "  "], $replaceWith, $subject);
    }

   /**
    * Convert a string to dashed case.
    * 
    * @param $subject The string to modify.
    * @param $dash The string to use as the dash.
    * 
    * @return string The resulting string.
    */
    
    public static function strToDashedCase(string $subject, string $dash = '-'): string {

        $tmp = '';

        $characters = str_split($subject, 1);

        $upperCaseCount = 0;
        $dashCount = 0;

        foreach ($characters as $index => $character) {

            if (ctype_alnum($character) === true) {

                if (ctype_upper($character) === true || ctype_digit($character) === true) {

                    if (($dashCount === 0) && ($upperCaseCount === 0) && ($index !== 0)) {
                        $tmp .= $dash;
                        $dashCount++;
                    }

                    $tmp = $tmp . strtolower($character);

                    $upperCaseCount++;
                    
                } else {
                    
                    $tmp = $tmp . $character;

                    $upperCaseCount = 0;
                }

                $dashCount = 0;
            } else {

                if (($index > 0) && ($index < count($characters) - 1)) {

                    if ($dashCount === 0) {
                        
                        $tmp .= $dash;
                    }

                    $dashCount++;
                }
            }
        }

        // strip extra slashes
        $tmp = preg_replace("/^({$dash}+)/", '', $tmp);
        $tmp = preg_replace("/({$dash}+)$/", '', $tmp);

        return $tmp;
    }

    private static function _cloneObject(object $obj, int $currentLevel, bool $excludeClosures, int $levels = null): ?object {

        if ($obj instanceof Throwable) {

            return null;
        }

        $reflObj = new ReflectionObject($obj);

        if (!$reflObj->isCloneable() || ($levels !== null && $currentLevel >= $levels)) {

            return null;
        }

        $newObj = clone $obj;

        $properties = static::getObjectProperties($newObj, true, true, true);

        foreach ($properties as $propertyName => $property) {

            $property->setAccessible(true);
            $value = $property->getValue($newObj);

            if ($excludeClosures) {

                if ($value instanceof Closure) {

                    $property->setValue($newObj, null);
                    continue;
                }
            }

            if ($property->isStatic()) {

                $property->setValue($newObj, null);
                continue;
            }

            if (static::isObject($value)) {

                $property->setValue($newObj, static::_cloneObject($value, $currentLevel++, $excludeClosures, $levels));
                continue;
            }
        }

        return $newObj;
    }

   /**
    * 
    * Clone an object optimistically - in other words, take what we can and skip what we can't.
    * 
    * @param object $something The object to clone.
    * 
    * @return ?object
    */   
    
    public static function optimisticClone(object $obj, bool $excludeClosures = true, int $levels = null): ?object {

        return static::_cloneObject($obj, 0, $excludeClosures, $levels);
    }
    
   /**
    * 
    * Serialize something (except closures).
    * 
    * @param mixed $something The variable to serialize.
    * @return string
    */    

    public static function serialize($something = null): string {

        if ($something instanceof Closure || static::isCallable($something) || $something === null) {

            return @serialize(null);
        }

        if (static::isObject($something)) {

            $values = [];

            $properties = static::getObjectProperties($something, true, true, true);

            foreach ($properties as $propertyName => $property) {

                $property->setAccessible(true);
                $value = $property->getValue($something);

                if ($property->isStatic() || $value instanceof Closure || static::isCallable($value)) {

                    $values[] = [
                        'reflection' => $property,
                        'value' => $value
                    ];

                    if ($property->isStatic()) {

                        $property->setValue(null);
                        continue;
                    }

                    $property->setValue($something, null);
                    continue;
                }

                if (static::isObject($value)) {

                    $property->setValue($something, static::optimisticClone($value, true, null));
                    continue;
                }
            }

            $result = @serialize($something);

            // restore the nullified properties

            foreach ($values as $value) {

                if ($property->isStatic()) {

                    $property->setValue($value['value']);
                    continue;
                }

                $value['reflection']->setValue($something, $value['value']);
            }

            return $result;
        }

        return @serialize($something);
    }
    
   /**
    * 
    * Unserialize something (except closures).
    * 
    * @param string $something The string to unserialize.
    * @param bool $strict If set to __true__, the method will not treat a blank string as valid.
    * 
    * @return string
    */
    
    public static function unserialize(string $something, bool $strict = false) {
        
        if(!$strict && self::isEmpty($something)) {
            
            return null;
        }        
        
        if($something === 'b:0;') {
            
            return false;
        }
        
        $tmp = @unserialize($something);
        
        if($tmp === false) {
            
            $errorLast = error_get_last();
            
            if($errorLast === null) {
                
                return null;
            }
            
            if(PHP_MAJOR_VERSION >= 7) {
                
                error_clear_last();
            }
            
            throw new PhpHelperException("{$errorLast['type']} error while unserializing '{$something}': {$errorLast['message']} ({$errorLast['file']}, line {$errorLast['line']}).");
        }
        
        return $tmp;
    }
    
    /**
     * 
     * Create a semi-constant value, based on the position from where it is called in the source code.
     * 
     * The value is 'semi'-constant, as it will always remain the same as long as the call isn't moved 
     * textually (e.g. moved to a different column or row in the source).
     * 
     */    

    public static function getLineAnchor(int $backTraceDepth = 1): string {

        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $backTraceDepth)[0];
        return md5($trace['file'] . $trace['line']);
    }
    
   /**
    * 
    * Base64 encode a string either with the native PHP base64_encode() function; or using an URL safe
    * version (compatible with Python).
    * 
    * @param string $string
    * @param bool $urlSafe
    * @return string
    */    

    public static function base64Encode(string $string, bool $urlSafe = false): string {
        
        $data = base64_encode($string);
        
        if($urlSafe) {
            
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        }
        
        return $data;
    }
    
   /**
    * 
    * Base64 decode a string either with the native PHP base64_decode() function; or using an URL safe
    * version (compatible with Python).
    * 
    * @param string $string
    * @param bool $urlSafe
    * @return string
    */    

    public static function base64Decode(string $string, bool $urlSafe = false): string {

        $data = $string;
        
        if($urlSafe) {
            
            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;

            if ($mod4) {
                
                $data .= substr('====', $mod4);
            }
        }
        
        return base64_decode($data);
    }
    
   /**
    * 
    * Generates a string of random bytes (either using random_bytes() if available - otherwise using rand(0, 255) and chr().
    * 
    * @param int $size
    * @return string
    */    

    public static function randomBytes(int $length): string {
        
        if(function_exists('random_bytes')) {
            
            return random_bytes($length);
        }
        
        $ret = '';
        
        for($i = 0; $i < $length; $i++) {
            
            $ret .= chr(rand(0, 255));
        }
        
        return $ret;
    }
}
