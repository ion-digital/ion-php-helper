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
    
    public static function inherits(string $childClassName, string $parentClassName) : bool
    {
        return is_subclass_of($childClassName, $parentClassName, true);
    }
    
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
    
    public static function isString($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_string($variable);
    }
    
    public static function isReal($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_float($variable);
    }
    
    public static function isFloat($variable = null) : bool
    {
        return static::isReal($variable);
    }
    
    public static function isDouble($variable = null) : bool
    {
        return static::isReal($variable);
    }
    
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
    
    public static function isInt($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_int($variable);
    }
    
    public static function isBool($variable = null) : bool
    {
        if ($variable === null) {
            return false;
        }
        return is_bool($variable);
    }
    
    public static function isNull($variable = null) : bool
    {
        return $variable === null;
    }
    
    public static function isCallable($variable = null) : bool
    {
        return is_callable($variable);
    }
    
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
    private static function toScalar($variable, callable $callBack, bool $allowDeserialization = false)
    {
        if (static::isObject($variable)) {
            return null;
        }
        if (static::isString($variable) && $allowDeserialization) {
            $tmp = json_decode($variable, JSON_OBJECT_AS_ARRAY);
            if ($tmp !== null) {
                return $tmp;
            }
            $tmp = @unserialize($variable);
            if ($tmp !== false) {
                return $tmp;
            }
        }
        // This must be last, to make sure we try to deserialize this first (if allowed).
        $tmp = $callBack($variable);
        if ($tmp !== null) {
            return $tmp;
        }
        return null;
    }
    
    public static function toArray($variable, bool $allowDeserialization = false, string $splitCharacterIfString = null) : ?array
    {
        return static::toScalar($variable, function ($variable) use($splitCharacterIfString, $allowDeserialization) {
            if (static::isArray($variable)) {
                return $variable;
            }
            if (static::isString($variable)) {
                if (strpos($variable, $splitCharacterIfString, 0) !== false && $splitCharacterIfString !== null) {
                    return explode($splitCharacterIfString, $variable);
                }
            }
            return null;
        }, $allowDeserialization);
    }
    
    public static function toString($variable = null, bool $allowDeserialization = false, string $joinCharacterIfArray = null) : ?string
    {
        return static::toScalar($variable, function ($variable) use($joinCharacterIfArray, $allowDeserialization) {
            if (static::isString($variable)) {
                return $variable;
            }
            if (static::isArray($variable)) {
                return implode($joinCharacterIfArray === null ? '' : $joinCharacterIfArray, $variable);
            }
            return null;
        }, $allowDeserialization);
    }
    
    public static function toFloat($variable = null, bool $allowDeserialization = false) : ?float
    {
        return static::toScalar($variable, function ($variable) use($allowDeserialization) {
            if (static::isFloat($variable)) {
                return (double) $variable;
            }
            if (static::isString($variable)) {
                return floatval($variable);
            }
            return null;
        }, $allowDeserialization);
    }
    
    public static function toInt($variable = null, bool $allowDeserialization = false) : ?int
    {
        return static::toScalar($variable, function ($variable) use($allowDeserialization) {
            if (static::hasDecimals($variable)) {
                return null;
            }
            if (static::isInt($variable)) {
                return (int) $variable;
            }
            if (static::isString($variable)) {
                if (static::hasDecimals(static::toFloat($variable, $allowDeserialization))) {
                    return null;
                }
                return (int) $variable;
            }
            return null;
        }, $allowDeserialization);
    }
    
    public static function toBool($variable = null, bool $allowDeserialization = false) : ?bool
    {
        return static::toScalar($variable, function ($variable) use($allowDeserialization) {
            if (static::isBool($variable)) {
                return (bool) $variable;
            }
            if (static::isString($variable)) {
                switch ($variable) {
                    case '1':
                    case 'true':
                    case 'on':
                    case 'yes':
                    case 'y':
                        return true;
                    case '0':
                    case 'false':
                    case 'off':
                    case 'no':
                    case 'n':
                        return false;
                }
            }
            return null;
        }, $allowDeserialization);
    }
    
    public static function getObjectProperties(object $object, bool $public = true, bool $protected = false, bool $private = false) : array
    {
        $reflector = new ReflectionObject($object);
        $propertyFilter = ($public ? ReflectionProperty::IS_PUBLIC : 0) | ($protected ? ReflectionProperty::IS_PROTECTED : 0) | ($private ? ReflectionProperty::IS_PRIVATE : 0);
        $result = [];
        foreach ($reflector->getProperties($propertyFilter) as $property) {
            $result[$property->getName()] = $property;
        }
        return $result;
    }
    
    public static function getObjectPropertyValues(object $object, bool $public = true, bool $protected = false, bool $private = false) : array
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
    
    public static function getObjectMethods(object $object, bool $public = true, bool $protected = false, bool $private = false, bool $abstract = true, bool $final = true) : array
    {
        $reflector = new ReflectionObject($object);
        $methodFilter = ($public ? ReflectionMethod::IS_PUBLIC : 0) | ($protected ? ReflectionMethod::IS_PROTECTED : 0) | ($private ? ReflectionMethod::IS_PRIVATE : 0) | ($abstract ? ReflectionMethod::IS_ABSTRACT : 0) | ($final ? ReflectionMethod::IS_FINAL : 0);
        $result = [];
        foreach ($reflector->getMethods($methodFilter) as $method) {
            $result[$method->getName()] = $method;
        }
        return $result;
    }
    
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
                $sig[] = (string) static::getArrayHash($value);
                continue;
            }
            $sig[] = (string) $value;
        }
        return crc32(join('', $sig));
    }
    
    public static function getObjectHash(object $object) : int
    {
        $sig = [get_class($object)];
        $properties = static::getObjectPropertyValues($object, true, true, true);
        $sig[] = static::getArrayHash($properties);
        //        foreach($properties as $property => $value) {
        //
        //            if(static::isObject($value)) {
        //
        //                $sig[] = (string) static::getObjectHash($value);
        //
        //                continue;
        //            }
        //
        //            if(static::isArray($value)) {
        //
        //                $sig[] = (string) static::getArrayHash($value);
        //
        //                continue;
        //            }
        //
        //            $sig[] = (string) $value;
        //
        //        }
        return crc32(join('', $sig));
    }
    
    public static function getServerRequestUri() : ?string
    {
        if (!static::isWebServer()) {
            return null;
        }
        $uri = (string) filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
        if (static::isEmpty($uri, true)) {
            $uri = (string) $_SERVER['REQUEST_URI'];
        }
        if (static::isEmpty($uri, true)) {
            return null;
        }
        return (string) $uri;
    }
    
    public static function getServerDocumentRoot() : ?string
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
    
    public static function isCommandLine() : bool
    {
        if (php_sapi_name() === 'cli') {
            return true;
        }
        return false;
    }
    
    public static function isWebServer() : bool
    {
        return !static::isCommandLine();
    }
    
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
    
    public static function toNull($variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true)
    {
        if (!static::isEmpty($variable, $orWhiteSpaceIfString, $orEmptyIfArray)) {
            return $variable;
        }
        return null;
    }
    
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
    
    public static function obGet(callable $closure, ...$parameters) : ?string
    {
        ob_start();
        call_user_func_array($closure, $parameters);
        return static::toNull((string) ob_get_clean(), false, true);
    }
    
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
    
    public static function count($variable) : ?int
    {
        if (!static::isArray($variable) && !static::isCountable($variable)) {
            return null;
        }
        return count($variable);
    }
    
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
    
    public static function getCallingPath() : string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (static::count($trace) === 0 || static::count($trace) > 0 && !array_key_exists('file', $trace[count($trace) - 1])) {
            throw new PhpHelperException("Could not determine the calling method / function's file path (i.e. the code file that contains it).");
        }
        return realpath($trace[count($trace) - 1]['file']);
    }
    
    public static function getCallingClass() : string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (static::count($trace) === 0 || static::count($trace) > 0 && !array_key_exists('class', $trace[count($trace) - 1])) {
            throw new PhpHelperException("Could not determine the calling class' name.");
        }
        return $trace[count($trace) - 1]['class'];
    }
    
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
    
    public static function strStripWhiteSpace(string $subject, string $replaceWith = ' ') : string
    {
        if ($replaceWith !== ' ') {
            return static::strReplaceAll(["\n", "\r", "\t", "  ", " "], $replaceWith, $subject);
        }
        return static::strReplaceAll(["\n", "\r", "\t", "  "], $replaceWith, $subject);
    }
    
    public static function strToDashedCase(string $subject) : string
    {
        $tmp = '';
        $characters = str_split($subject, 1);
        $upperCaseCount = 0;
        $dashCount = 0;
        foreach ($characters as $index => $character) {
            if (ctype_alnum($character) === true) {
                if (ctype_upper($character) === true) {
                    if ($dashCount === 0 && $upperCaseCount === 0 && $index !== 0) {
                        $tmp = $tmp . '-';
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
                        $tmp = $tmp . "-";
                    }
                    $dashCount++;
                }
            }
        }
        // strip extra slashes
        $tmp = preg_replace("/^(-+)/", '', $tmp);
        $tmp = preg_replace("/(-+)\$/", '', $tmp);
        return $tmp;
    }
    
    private static function _cloneObject(object $obj, int $currentLevel, bool $excludeClosures, int $levels = null) : ?object
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
    
    public static function optimisticClone(object $obj, bool $excludeClosures = true, int $levels = null) : ?object
    {
        return static::_cloneObject($obj, 0, $excludeClosures, $levels);
    }
    
    public static function serialize($something) : string
    {
        if ($something instanceof Closure || static::isCallable($something)) {
            return serialize(null);
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
            $result = serialize($something);
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
        return serialize($something);
    }

}