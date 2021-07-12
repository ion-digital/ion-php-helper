<?php
/*
 * See license information at the package root in LICENSE.md
 */
namespace ion;

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
interface PhpHelperInterface
{
    /**
     * Checks to see if an array is an associative array or not.
     *
     * @since 0.0.1
     *
     * @param array $array The array to check.
     * @return bool Returns __true__ if the array is an associative array, __false__ if not.
     *
     */
    static function isAssociativeArray(array $array) : bool;
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
    static function isEmpty($value, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true) : bool;
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
    static function inherits(string $childClassName, string $parentClassName) : bool;
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
    static function isObject($variable, string $className = null, bool $parent = true, bool $class = true) : bool;
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
    static function isArray($variable, bool $isAssociative = true) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isString($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isReal($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isFloat($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isDouble($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isNumeric($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function hasDecimals($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isInt($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isBool($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isNull($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isCallable($variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return bool
     */
    static function isType(string $typeString, $variable = null) : bool;
    /**
     * method
     * 
     * 
     * @return ?array
     */
    static function toArray($variable = null, bool $allowDeserialization = false, string $splitCharacterIfString = null);
    /**
     * method
     * 
     * 
     * @return ?string
     */
    static function toString($variable = null, bool $allowDeserialization = false, string $joinCharacterIfArray = null);
    /**
     * method
     * 
     * 
     * @return ?float
     */
    static function toFloat($variable = null, bool $allowDeserialization = false);
    /**
     * method
     * 
     * 
     * @return ?int
     */
    static function toInt($variable = null, bool $allowDeserialization = false);
    /**
     * method
     * 
     * 
     * @return ?bool
     */
    static function toBool($variable = null, bool $allowDeserialization = false, bool $checkElementsIfArray = false);
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
    static function getObjectProperties($object, bool $public = true, bool $protected = false, bool $private = false) : array;
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
    static function getObjectPropertyValues($object, bool $public = true, bool $protected = false, bool $private = false) : array;
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
    static function getObjectMethods($object, bool $public = true, bool $protected = false, bool $private = false, bool $abstract = true, bool $final = true) : array;
    /**
     *
     * Return a unique hash that represents the properties of this object.
     *
     * @param array $array The array for which to return the hash for.
     *
     * @return int Return the hash as an int.
     */
    static function getArrayHash(array $array) : int;
    /**
     *
     * Return a unique hash that represents the properties of this object.
     *
     * @param object $object The object for which to return the hash for.
     *
     * @return int Return the hash as an int.
     */
    static function getObjectHash($object) : int;
    /**
     * Returns the value of the $_SERVER['REQUEST_URI'] variable.
     *
     * @param bool $includeHost Include the host.
     * @param bool $includeProtocol Include the protocol.
     * @return ?string Return the value.
     *
     */
    static function getServerRequestUri(bool $includeHost = false, bool $includeProtocol = true);
    /**
     *
     * Returns the value of the $_SERVER['HTTP_REFERER'] variable.
     *
     * @return string|null
     */
    static function getServerReferrerUri();
    /**
     * Returns the value of the $_SERVER['DOCUMENT_ROOT'] variable.
     *
     * @return ?string Return the value.
     */
    static function getServerDocumentRoot();
    /**
     * Return whether the current script is running in a command-line context, or somewhere else (like a web server).
     *
     * @return bool Returns __true__ if we are running as a command-line script - __false__ otherwise.
     */
    static function isCommandLine() : bool;
    /**
     * Return whether the current script is running in a Web context, or somewhere else (like the command-line).
     *
     * @return bool Returns __true__ if we are running as a Web script - __false__ otherwise.
     */
    static function isWebServer() : bool;
    /**
     * Return whether a variable is countable.
     *
     * @return bool Returns __true__ if it is, __false__ otherwise.
     */
    static function isCountable($variable) : bool;
    /**
     * Returns a NULL if that value is null or false - otherwise, the value.
     *
     * @param mixed $variable The variable to convert.
     */
    static function toNull($variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true);
    /**
     * method
     * 
     * 
     * @return mixed
     */
    static function filterInput(string $variableName, array $inputs = [], int $filter = null, array $options = []);
    /**
     * Capture the output from the output buffer - basically another way of capturing output via ob_start() and ob_get_clean().
     *
     * @param callable $closure The callable to execute.
     * @param ... $parameters The parameters to execute the callable with.
     *
     * @return ?string The output captured from the executed callable.
     *
     */
    static function obGet(callable $closure, ...$parameters);
    /**
     * Check if a string ends with a substring.
     *
     * @param string $string The input string to check.
     * @param string $subString The substring to check for.
     *
     * @return bool Returns __true__ if $string ends with $subString, __false__ if not.
     */
    static function strEndsWith(string $string, string $subString) : bool;
    /**
     * Check if a string starts with a substring.
     *
     * @param string $string The input string to check.
     * @param string $subString The substring to check for.
     *
     * @return bool Returns __true__ if $string starts with $subString, __false__ if not.
     */
    static function strStartsWith(string $string, string $subString) : bool;
    /**
     * Count the number of elements in a Countable. This method does the same as count(), except that it returns __NULL__ if the $variable is not countable.
     *
     * @param mixed $variable The variable to count.
     *
     * @return ?int Returns the count of the elements if $variable is countable, otherwise __NULL__.
     *
     */
    static function count($variable);
    /**
     * Checks if a string contains a substring. If $position is specified, it will check for a substring at a specific position.
     *
     * @param string $string The string to check.
     * @param string $subString The substring to check for.
     * @param int $position The position in $string to check at.
     *
     * @return bool Returns __true__ if $string contains $subString (if $position is not specified), __true__ if $string contains $substring at the specified position (if $position is specified), or __false_ otherwise.
     */
    static function strContains(string $string, string $subString, int $position = null) : bool;
    /**
     * Returns the path of the calling method or function.
     *
     * @return string The path to the calling PHP code file.
     *
     */
    static function getCallingPath() : string;
    /**
     * Returns the name of the class from where the current function/method was called.
     *
     * @return string The name of the class from where the containing function/method was called.
     *
     */
    static function getCallingClass() : string;
    /**
     * Replace multiple strings.
     *
     * @param array $strings The strings to look for (the needles).
     * @param string $replacement The replacement string.
     * @param string $subject The subject to modify (the haystack).
     * @param bool $ignoreCase If __true__ case is ignored, if __false__ case is taken into consideration.
     * @return string The modified subject.
     */
    static function strReplaceAll(array $strings, string $replacement, string $subject, bool $ignoreCase = false, int &$count = null) : string;
    /**
     * Strip white-space from a string.
     *
     * @param string $subject The subject to modify.
     * @param string $replaceWith The string to replace white-space instances with.
     * @return string The resulting string.
     */
    static function strStripWhiteSpace(string $subject, string $replaceWith = " ") : string;
    /**
     * Convert a string to dashed case.
     *
     * @param $subject The string to modify.
     * @param $dash The string to use as the dash.
     *
     * @return string The resulting string.
     */
    static function strToDashedCase(string $subject, string $dash = "-") : string;
    /**
     *
     * Clone an object optimistically - in other words, take what we can and skip what we can't.
     *
     * @param object $something The object to clone.
     *
     * @return ?object
     */
    static function optimisticClone($obj, bool $excludeClosures = true, int $levels = null);
    /**
     *
     * Serialize something (except closures).
     *
     * @param mixed $something The variable to serialize.
     * @return string
     */
    static function serialize($something = null) : string;
    /**
     *
     * Unserialize something (except closures).
     *
     * @param string $something The string to unserialize.
     * @param bool $strict If set to __true__, the method will not treat a blank string as valid.
     *
     * @return string
     */
    static function unserialize(string $something, bool $strict = false);
    /**
     *
     * Create a semi-constant value, based on the position from where it is called in the source code.
     *
     * The value is 'semi'-constant, as it will always remain the same as long as the call isn't moved
     * textually (e.g. moved to a different column or row in the source).
     *
     */
    static function getLineAnchor(int $backTraceDepth = 1) : string;
    /**
     *
     * Base64 encode a string either with the native PHP base64_encode() function; or using an URL safe
     * version (compatible with Python).
     *
     * @param string $string
     * @param bool $urlSafe
     * @return string
     */
    static function base64Encode(string $string, bool $urlSafe = false) : string;
    /**
     *
     * Base64 decode a string either with the native PHP base64_decode() function; or using an URL safe
     * version (compatible with Python).
     *
     * @param string $string
     * @param bool $urlSafe
     * @return string
     */
    static function base64Decode(string $string, bool $urlSafe = false) : string;
    /**
     *
     * Generates a string of random bytes (either using random_bytes() if available - otherwise using rand(0, 255) and chr().
     *
     * @param int $size
     * @return string
     */
    static function randomBytes(int $length) : string;
}