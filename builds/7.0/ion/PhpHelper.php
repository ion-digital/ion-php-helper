<?php
/*
 * See license information at the package root in LICENSE.md
 */
namespace ion;

/**
 * Description of PhpHelper
 *
 * @author Justus
 */
use ion\Types\IStringObject;
use ion\Types\IEnum;
use ReflectionObject;
use ReflectionProperty;
use ReflectionMethod;
use ion\Types\Arrays\IMap;
use ion\Types\Arrays\Map;
use Exception;
use Throwable;
use Error;
use Countable;
use Serializable;
use Closure;

class PhpHelper implements IPhpHelper
{
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isAssociativeArray(array $array) : bool
    {
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
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isEmpty($value, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true) : bool
    {
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
     * method
     * 
     * 
     * @return bool
     */
    
    public static function inherits(string $childClassName, string $parentClassName) : bool
    {
        return is_subclass_of($childClassName, $parentClassName, true);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isObject($variable, string $className = null, bool $parent = true, bool $class = true) : bool
    {
        if (!is_object($variable)) {
            return false;
        }
        if ($className !== null && $parent === true) {
            if ($class === true) {
                return is_a($variable, $className, true) || static::inherits(get_class($variable), $className);
            }
            return static::inherits(get_class($variable), $className);
        }
        if ($className !== null && $parent === false) {
            return get_class($variable) === $className;
        }
        return true;
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isArray($variable, bool $isAssociative = true) : bool
    {
        if (!is_array($variable)) {
            return false;
        }
        if (static::isAssociativeArray($variable) && !$isAssociative) {
            return false;
        }
        return true;
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isString($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_string($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isReal($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return static::isFloat($variable) || static::isDouble($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isFloat($variable = null) : bool
    {
        return is_float($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isDouble($variable = null) : bool
    {
        return is_double($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isNumeric($variable = null) : bool
    {
        return static::isInt($variable) || static::isReal($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function hasDecimals($variable = null) : bool
    {
        if (!static::isReal($variable)) {
            return false;
        }
        if ($variable === 0) {
            return false;
        }
        if (floor((double) $variable) !== (double) $variable) {
            return true;
        }
        return false;
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isInt($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_int($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isBool($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_bool($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isNull($variable = null) : bool
    {
        return $variable === null;
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isCallable($variable = null) : bool
    {
        return is_callable($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isType(string $typeString, $variable = null) : bool
    {
        switch (strtolower(trim($typeString))) {
            case 'null':
                return $variable === null;
            case 'array':
                return static::isArray($variable);
            case 'int':
                return static::isInt($variable);
            case 'real':
            case 'double':
            case 'float':
                return static::isReal($variable);
            case 'string':
                return static::isString($variable);
            case 'bool':
                return static::isBool($variable);
            case 'object':
                return static::isObject($variable);
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
    /**
     * method
     * 
     * 
     * @return mixed
     */
    
    private static function toScalar($variable, callable $callBack, bool $allowDeserialization = false)
    {
        if (static::isObject($variable)) {
            return null;
        }
        if (static::isString($variable) && $allowDeserialization) {
            $tmp = json_decode($variable, JSON_OBJECT_AS_ARRAY);
            if (!self::isEmpty($tmp)) {
                return self::toScalar($tmp, $callBack, false);
            }
            try {
                return self::toScalar(self::unserialize($variable, true), $callBack, false);
            } catch (PhpHelperException $exception) {
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
    
    /**
     * method
     * 
     * 
     * @return ?array
     */
    
    public static function toArray($variable = null, bool $allowDeserialization = false, string $splitCharacterIfString = null)
    {
        return static::toScalar($variable, function ($variable) use($splitCharacterIfString, $allowDeserialization) {
            if ($variable === null) {
                return null;
            }
            if (static::isArray($variable)) {
                return $variable;
            }
            if (static::isString($variable)) {
                if ($variable === '') {
                    return [];
                }
                if (strpos($variable, $splitCharacterIfString, 0) !== false && $splitCharacterIfString !== null) {
                    return explode($splitCharacterIfString, $variable);
                }
            }
            return [$variable];
        }, $allowDeserialization);
    }
    
    /**
     * method
     * 
     * 
     * @return ?string
     */
    
    public static function toString($variable = null, bool $allowDeserialization = false, string $joinCharacterIfArray = null)
    {
        return static::toScalar($variable, function ($variable) use($joinCharacterIfArray, $allowDeserialization) {
            if ($variable === null) {
                return null;
            }
            if (static::isString($variable)) {
                return $variable;
            }
            if (static::isArray($variable)) {
                return implode($joinCharacterIfArray === null ? '' : $joinCharacterIfArray, $variable);
            }
            if (static::isBool($variable)) {
                return $variable ? '1' : '';
            }
            return (string) $variable;
        }, $allowDeserialization);
    }
    
    /**
     * method
     * 
     * 
     * @return ?float
     */
    
    public static function toFloat($variable = null, bool $allowDeserialization = false)
    {
        return static::toScalar($variable, function ($variable) use($allowDeserialization) {
            if ($variable === null) {
                return null;
            }
            if (static::isArray($variable)) {
                return null;
            }
            if (static::isFloat($variable)) {
                return $variable;
            }
            if (static::isBool($variable)) {
                return $variable ? 1.0 : 0.0;
            }
            return (double) $variable;
        }, $allowDeserialization);
    }
    
    /**
     * method
     * 
     * 
     * @return ?int
     */
    
    public static function toInt($variable = null, bool $allowDeserialization = false)
    {
        return static::toScalar($variable, function ($variable) use($allowDeserialization) {
            if ($variable === null) {
                return null;
            }
            if (static::hasDecimals($variable)) {
                return null;
            }
            if (static::isInt($variable)) {
                return $variable;
            }
            if (static::isBool($variable)) {
                return $variable ? 1 : 0;
            }
            if (static::isString($variable)) {
                if (static::hasDecimals(static::toFloat($variable, $allowDeserialization))) {
                    return null;
                }
            }
            return (int) $variable;
        }, $allowDeserialization);
    }
    
    /**
     * method
     * 
     * 
     * @return ?bool
     */
    
    public static function toBool($variable = null, bool $allowDeserialization = false, bool $checkElementsIfArray = false)
    {
        return static::toScalar($variable, function ($variable) use($allowDeserialization, $checkElementsIfArray) {
            if ($variable === null) {
                return null;
            }
            if (static::isBool($variable)) {
                return (bool) $variable;
            }
            if (static::isCountable($variable)) {
                if (!$checkElementsIfArray) {
                    if (static::count($variable) === 0) {
                        return false;
                    }
                    return true;
                }
                $t = 0;
                $f = 0;
                if (static::count($variable) === 0) {
                    return null;
                }
                foreach ($variable as $index => $value) {
                    if (self::toBool($value, $allowDeserialization, $checkElementsIfArray) === true) {
                        $t++;
                        continue;
                    }
                    $f++;
                }
                if ($t === $f) {
                    return null;
                }
                return $t > $f;
            }
            if (static::isString($variable)) {
                if (static::isEmpty($variable, true)) {
                    return false;
                }
                switch (trim(strToLower($variable))) {
                    case 'enabled':
                    case 'enable':
                    case '1':
                    case 'true':
                    case 'on':
                    case 'yes':
                    case 'y':
                        return true;
                    case 'disabled':
                    case 'disable':
                    case '0':
                    case 'false':
                    case 'off':
                    case 'no':
                    case 'n':
                        return false;
                }
                return (bool) $variable;
            }
            if (static::isInt($variable) || static::isFloat($variable)) {
                if ($variable > 0) {
                    return true;
                }
                return false;
            }
            return null;
        }, $allowDeserialization);
    }
    
    /**
     * method
     * 
     * 
     * @return array
     */
    
    public static function getObjectProperties($object, bool $public = true, bool $protected = false, bool $private = false) : array
    {
        $reflector = new ReflectionObject($object);
        $propertyFilter = ($public ? ReflectionProperty::IS_PUBLIC : 0) | ($protected ? ReflectionProperty::IS_PROTECTED : 0) | ($private ? ReflectionProperty::IS_PRIVATE : 0);
        $result = [];
        foreach ($reflector->getProperties($propertyFilter) as $property) {
            $result[$property->getName()] = $property;
        }
        return $result;
    }
    
    /**
     * method
     * 
     * 
     * @return array
     */
    
    public static function getObjectPropertyValues($object, bool $public = true, bool $protected = false, bool $private = false) : array
    {
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
     * method
     * 
     * 
     * @return array
     */
    
    public static function getObjectMethods($object, bool $public = true, bool $protected = false, bool $private = false, bool $abstract = true, bool $final = true) : array
    {
        $reflector = new ReflectionObject($object);
        $methodFilter = ($public ? ReflectionMethod::IS_PUBLIC : 0) | ($protected ? ReflectionMethod::IS_PROTECTED : 0) | ($private ? ReflectionMethod::IS_PRIVATE : 0) | ($abstract ? ReflectionMethod::IS_ABSTRACT : 0) | ($final ? ReflectionMethod::IS_FINAL : 0);
        $result = [];
        foreach ($reflector->getMethods($methodFilter) as $method) {
            $result[$method->getName()] = $method;
        }
        return $result;
    }
    
    /**
     * method
     * 
     * 
     * @return int
     */
    
    public static function getArrayHash(array $array) : int
    {
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
     * method
     * 
     * 
     * @return int
     */
    
    public static function getObjectHash($object) : int
    {
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
     * method
     * 
     * 
     * @return ?string
     */
    
    public static function getServerRequestUri(bool $includeHost = false, bool $includeProtocol = true)
    {
        if (!static::isWebServer()) {
            return null;
        }
        $host = null;
        $protocol = null;
        $path = (string) filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
        if (static::isEmpty($path, true)) {
            $path = (string) $_SERVER['REQUEST_URI'];
        }
        if ($includeProtocol) {
            $https = (string) filter_input(INPUT_SERVER, 'HTTPS', FILTER_DEFAULT);
            if (static::isEmpty($https, true)) {
                $https = (string) $_SERVER['HTTPS'];
            }
            $protocol = (string) (static::toBool($https) ? "https" : "http");
        }
        if ($includeHost) {
            $host = (string) filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_DEFAULT);
            if (static::isEmpty($host, true)) {
                $host = (string) $_SERVER['HTTP_HOST'];
            }
        }
        return (!self::isEmpty($protocol) ? "{$protocol}://" : "") . (!self::isEmpty($host) ? $host : "") . $path;
    }
    
    /**
     * method
     * 
     * @return ?string
     */
    
    public static function getServerReferrerUri()
    {
        $tmp = self::toString(self::filterInput('HTTP_REFERER', [INPUT_SERVER], FILTER_DEFAULT));
        if (!self::isEmpty($tmp)) {
            return $tmp;
        }
        if (!array_key_exists('HTTP_REFERER', $_SERVER)) {
            return null;
        }
        return self::toString($_SERVER['HTTP_REFERER']);
    }
    
    /**
     * method
     * 
     * @return ?string
     */
    
    public static function getServerDocumentRoot()
    {
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
     * method
     * 
     * @return bool
     */
    
    public static function isCommandLine() : bool
    {
        if (php_sapi_name() === 'cli') {
            return true;
        }
        return false;
    }
    
    /**
     * method
     * 
     * @return bool
     */
    
    public static function isWebServer() : bool
    {
        return !static::isCommandLine();
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function isCountable($variable) : bool
    {
        if (PHP_MAJOR_VERSION >= 7 && PHP_MINOR_VERSION >= 3) {
            return (bool) is_countable($variable);
        }
        if (static::isObject($variable)) {
            return static::inherits(get_class($variable), \Countable::class);
        }
        return static::isArray($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return mixed
     */
    
    public static function toNull($variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true)
    {
        if (!static::isEmpty($variable, $orWhiteSpaceIfString, $orEmptyIfArray)) {
            return $variable;
        }
        return null;
    }
    
    //TODO: Read https://stackoverflow.com/questions/25232975/php-filter-inputinput-server-request-method-returns-null and evaluate this method.
    /**
     * method
     * 
     * 
     * @return mixed
     */
    
    public static function filterInput(string $variableName, array $inputs = [], int $filter = null, array $options = [])
    {
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
            throw new PhpHelperException("Each input must be of type 'int.'");
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
            if ($filter !== FILTER_NULL_ON_FAILURE && $result === false || $filter === FILTER_NULL_ON_FAILURE && $result === null) {
                throw new PhpHelperException("Input filter for '{$variableName}' failed.");
            }
            if ($result !== null && ($filter !== FILTER_NULL_ON_FAILURE && $result !== false || $filter === FILTER_NULL_ON_FAILURE && $result !== null)) {
                return $result;
            }
        }
        return null;
    }
    
    /**
     * method
     * 
     * 
     * @return ?string
     */
    
    public static function obGet(callable $closure, ...$parameters)
    {
        ob_start();
        call_user_func_array($closure, $parameters);
        return static::toNull((string) ob_get_clean(), false, true);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function strEndsWith(string $string, string $subString) : bool
    {
        if (!static::strContains($string, $subString)) {
            return false;
        }
        if (strrpos($string, $subString) === strlen($string) - strlen($subString)) {
            return true;
        }
        return false;
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function strStartsWith(string $string, string $subString) : bool
    {
        if (!static::strContains($string, $subString)) {
            return false;
        }
        if (strpos($string, $subString) === 0) {
            return true;
        }
        return false;
    }
    
    /**
     * method
     * 
     * 
     * @return ?int
     */
    
    public static function count($variable)
    {
        if (!static::isArray($variable) && !static::isCountable($variable)) {
            return null;
        }
        return count($variable);
    }
    
    /**
     * method
     * 
     * 
     * @return bool
     */
    
    public static function strContains(string $string, string $subString, int $position = null) : bool
    {
        if (strpos($string, $subString) === false) {
            return false;
        }
        if ($position !== null && $position > strlen($string) - strlen($subString)) {
            return false;
        }
        if ($position !== null) {
            return strpos($string, $subString, 0) === $position;
        }
        return strpos($string, $subString) !== false;
    }
    
    /**
     * method
     * 
     * @return string
     */
    
    public static function getCallingPath() : string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (static::count($trace) === 0 || static::count($trace) > 0 && !array_key_exists('file', $trace[count($trace) - 1])) {
            throw new PhpHelperException("Could not determine the calling method / function's file path (i.e. the code file that contains it).");
        }
        return realpath($trace[count($trace) - 1]['file']);
    }
    
    /**
     * method
     * 
     * @return string
     */
    
    public static function getCallingClass() : string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (static::count($trace) === 0 || static::count($trace) > 0 && !array_key_exists('class', $trace[count($trace) - 1])) {
            throw new PhpHelperException("Could not determine the calling class' name.");
        }
        return $trace[count($trace) - 1]['class'];
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function strReplaceAll(array $strings, string $replacement, string $subject, bool $ignoreCase = false, int &$count = null) : string
    {
        if ($count !== null) {
            $count = 0;
        }
        foreach ($strings as $string) {
            while ($ignoreCase ? stripos($subject, (string) $string) !== false : strpos($subject, (string) $string) !== false) {
                $tmp = 0;
                $subject = $ignoreCase ? str_ireplace((string) $string, $replacement, $subject, $tmp) : str_replace((string) $string, $replacement, $subject, $tmp);
                if ($count !== null) {
                    $count += $tmp;
                }
            }
        }
        return $subject;
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function strStripWhiteSpace(string $subject, string $replaceWith = ' ') : string
    {
        if ($replaceWith !== ' ') {
            return static::strReplaceAll(["\n", "\r", "\t", "  ", " "], $replaceWith, $subject);
        }
        return static::strReplaceAll(["\n", "\r", "\t", "  "], $replaceWith, $subject);
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function strToDashedCase(string $subject, string $dash = '-') : string
    {
        $tmp = '';
        $characters = str_split($subject, 1);
        $upperCaseCount = 0;
        $dashCount = 0;
        foreach ($characters as $index => $character) {
            if (ctype_alnum($character) === true) {
                if (ctype_upper($character) === true) {
                    if ($dashCount === 0 && $upperCaseCount === 0 && $index !== 0) {
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
                if ($index > 0 && $index < count($characters) - 1) {
                    if ($dashCount === 0) {
                        $tmp .= $dash;
                    }
                    $dashCount++;
                }
            }
        }
        // strip extra slashes
        $tmp = preg_replace("/^({$dash}+)/", '', $tmp);
        $tmp = preg_replace("/({$dash}+)\$/", '', $tmp);
        return $tmp;
    }
    
    /**
     * method
     * 
     * 
     * @return ?object
     */
    
    private static function _cloneObject($obj, int $currentLevel, bool $excludeClosures, int $levels = null)
    {
        if ($obj instanceof Throwable) {
            return null;
        }
        $reflObj = new ReflectionObject($obj);
        if (!$reflObj->isCloneable() || $levels !== null && $currentLevel >= $levels) {
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
     * method
     * 
     * 
     * @return ?object
     */
    
    public static function optimisticClone($obj, bool $excludeClosures = true, int $levels = null)
    {
        return static::_cloneObject($obj, 0, $excludeClosures, $levels);
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function serialize($something = null) : string
    {
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
                    $values[] = ['reflection' => $property, 'value' => $value];
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
     * method
     * 
     * 
     * @return mixed
     */
    
    public static function unserialize(string $something, bool $strict = false)
    {
        if (!$strict && self::isEmpty($something)) {
            return null;
        }
        if ($something === 'b:0;') {
            return false;
        }
        $tmp = @unserialize($something);
        if ($tmp === false) {
            $errorLast = error_get_last();
            if (PHP_MAJOR_VERSION >= 7) {
                error_clear_last();
            }
            throw new PhpHelperException("{$errorLast['type']} error while unserializing '{$something}': {$errorLast['message']} ({$errorLast['file']}, line {$errorLast['line']}).");
        }
        return $tmp;
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function getLineAnchor(int $backTraceDepth = 1) : string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $backTraceDepth)[0];
        return md5($trace['file'] . $trace['line']);
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function base64Encode(string $string, bool $urlSafe = false) : string
    {
        $data = base64_encode($string);
        if ($urlSafe) {
            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        }
        return $data;
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function base64Decode(string $string, bool $urlSafe = false) : string
    {
        $data = $string;
        if ($urlSafe) {
            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }
        }
        return base64_decode($data);
    }
    
    /**
     * method
     * 
     * 
     * @return string
     */
    
    public static function randomBytes(int $length) : string
    {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        $ret = '';
        for ($i = 0; $i < $length; $i++) {
            $ret .= chr(rand(0, 255));
        }
        return $ret;
    }

}