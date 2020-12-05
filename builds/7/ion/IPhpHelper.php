<?php

/*
 * See license information at the package root in LICENSE.md
 */


namespace ion;

interface IPhpHelper
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

    static function isAssociativeArray(array $array): bool;

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

    static function isEmpty(/* mixed */ $variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true): bool;

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
    
    static function inherits(string $childClassName, string $parentClassName): bool;
    
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

    static function isObject(/* mixed */ $variable, string $className = null, bool $parent = true, bool $class = true): bool;    
    
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
    
    static function isArray(/* mixed */ $variable, bool $isAssociative = true): bool;
    
    static function isString(/* mixed */ $variable = null): bool;
        
    static function isReal(/* mixed */ $variable = null): bool;
    
    static function isFloat(/* mixed */ $variable = null): bool;
    
    static function isDouble(/* mixed */ $variable = null): bool;
    
    static function hasDecimals(/* mixed */ $variable = null): bool;
    
    static function isInt(/* mixed */ $variable = null): bool;
    
    static function isBool(/* mixed */ $variable = null): bool;
    
    static function isCallable(/* mixed */ $variable = null): bool;
    
    static function isNull(/* mixed */ $variable = null): bool;
    
    static function isType(string $typeString, /* mixed */ $variable = null): bool;   
    
    static function isNumeric(/* mixed */ $variable = null): bool;
    
    static function toArray(/* mixed */ $variable, bool $allowDeserialization = false, string $splitCharacterIfString = null): ?array;
    
    static function toString(/* mixed */ $variable = null, bool $allowDeserialization = false, string $joinCharacterIfArray = null): ?string;
    
    static function toFloat(/* mixed */ $variable = null, bool $allowDeserialization = false): ?float;

    static function toInt(/* mixed */ $variable = null, bool $allowDeserialization = false): ?int;
    
    static function toBool(/* mixed */ $variable = null, bool $allowDeserialization = false): ?bool;
    
    /**
     * Returns a NULL if that value is null or false - otherwise, the value.
     * 
     * @param mixed $variable The variable to convert.
     */
    
    static function toNull(/* mixed */ $variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true);          
    
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
    
    static function getObjectProperties(object $object, bool $public = true, bool $protected = false, bool $private = false): array;

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
    
    static function getObjectPropertyValues(object $object, bool $public = true, bool $protected = false, bool $private = false): array;
	
	
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
    
    static function getObjectMethods(object $object, bool $public = true, bool $protected = false, bool $private = false, bool $abstract = true, bool $final = true): array;	
    
    /**
     * 
     * Return a unique hash that represents the properties of this object.
     * 
     * @param array $array The array for which to return the hash for.
     * 
     * @return int Return the hash as an int.
     */    
    
    static function getArrayHash(array $array): int;
    
    /**
     * 
     * Return a unique hash that represents the properties of this object.
     * 
     * @param object $object The object for which to return the hash for.
     * 
     * @return int Return the hash as an int.
     */
    
    static function getObjectHash(object $object): int;
    
    /**
     * Returns the value of the $_SERVER['REQUEST_URI'] variable.
     * 
     * @return ?string Return the value.
     */
    
    static function getServerRequestUri(): ?string;

    /**
     * Returns the value of the $_SERVER['DOCUMENT_ROOT'] variable.
     * 
     * @return ?string Return the value.
     */
    
    static function getServerDocumentRoot(): ?string;    
    
    /**
     * Return whether the current script is running in a command-line context, or somewhere else (like a web server).
     * 
     * @return bool Returns __true__ if we are running as a command-line script - __false__ otherwise. 
     */
    
    static function isCommandLine(): bool;    
    
    /**
     * Return whether the current script is running in a Web context, or somewhere else (like the command-line).
     * 
     * @return bool Returns __true__ if we are running as a Web script - __false__ otherwise. 
     */
    
    static function isWebServer(): bool;
    
    /**
     * Return whether a variable is countable.
     * 
     * @return bool Returns __true__ if it is, __false__ otherwise. 
     */
    
    static function isCountable($variable): bool;
            
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
    
    static function filterInput(string $variableName, array $inputs = [], int $filter = null, array $options = []);
    
    
//    /**
//     * Suppress errors, warnings and/or notices and execute the specified closure; returning the resulting value.
//     * 
//     * @param callable $closure The closure to execute.
//     * @param bool $includeExceptions Suppress exceptions as well; defaults to __false__.
//     * @param bool $convertToExceptions Convert all notices, warnings and errors to exceptions instead; defaults to __true__.
//     * @param int $errorLevel The error level set before executing the closure; defaults to __E_ALL__
//     * 
//     * @return mixed The value returned by the closure.
//     * 
//     */
//    
//    static function suppress(callable $closure, bool $includeExceptions = false, bool $convertToExceptions = true, int $errorLevel = 0);
   
    /**
     * Capture the output from the output buffer - basically another way of capturing output via ob_start() and ob_get_clean().
     * 
     * @param callable $closure The callable to execute.
     * @param ... $parameters The parameters to execute the callable with.
     * 
     * @return ?string The output captured from the executed callable.
     * 
     */
    
   static function obGet(callable $closure, ...$parameters): ?string;
    
   /**
    * Check if a string ends with a substring.
    * 
    * @param string $string The input string to check.
    * @param string $subString The substring to check for.
    * 
    * @return bool Returns __true__ if $string ends with $subString, __false__ if not.
    */
   
   static function strEndsWith(string $string, string $subString): bool;
   
   /**
    * Check if a string starts with a substring.
    * 
    * @param string $string The input string to check.
    * @param string $subString The substring to check for.
    * 
    * @return bool Returns __true__ if $string starts with $subString, __false__ if not.
    */
   
   static function strStartsWith(string $string, string $subString): bool;
   
   /**
    * Count the number of elements in a Countable. This method does the same as count(), except that it returns __NULL__ if the $variable is not countable.
    * 
    * @param mixed $variable The variable to count.
    * 
    * @return ?int Returns the count of the elements if $variable is countable, otherwise __NULL__.
    *
    */
   
   static function count(/* mixed */ $variable): ?int;
   
   /**
    * Checks if a string contains a substring. If $position is specified, it will check for a substring at a specific position.
    * 
    * @param string $string The string to check.
    * @param string $subString The substring to check for.
    * @param int $position The position in $string to check at.
    * 
    * @return bool Returns __true__ if $string contains $subString (if $position is not specified), __true__ if $string contains $substring at the specified position (if $position is specified), or __false_ otherwise.
    */
   
   static function strContains(string $string, string $subString, int $position = null): bool;
   
   /**
    * Returns the path of the calling method or function.
    * 
    * @return string The path to the calling PHP code file.
    * 
    */
   
   static function getCallingPath(): string;
   
   /**
    * Returns the name of the class from where the current function/method was called.
    * 
    * @return string The name of the class from where the containing function/method was called.
    * 
    */
   
   static function getCallingClass(): string;   
   
//   /**
//    * Returns the method / function parameters as an associative array (name => [ 'type' => __type__, 'value' => __value__ ]).         
//    * 
//    * @param callable $callable The callable to get the parameters for - if __NULL__, then it defaults to the current method / function.
//    * @return array Returns the method / function parameters as an associative array.
//    */
//   
//   static function getParameters(callable $callable = null): array;
   
   /**
    * Replace multiple strings.
    * 
    * @param array $strings The strings to look for (the needles).
    * @param string $replacement The replacement string.
    * @param string $subject The subject to modify (the haystack).
    * @param bool $ignoreCase If __true__ case is ignored, if __false__ case is taken into consideration.
    * @return string The modified subject.
    */
   
   static function strReplaceAll(array $strings, string $replacement, string $subject, bool $ignoreCase = false, int &$count = null): string;
   
   /**
    * Strip white-space from a string.
    * 
    * @param string $subject The subject to modify.
    * @param string $replaceWith The string to replace white-space instances with.
    * @return string The resulting string.
    */
   
   static function strStripWhiteSpace(string $subject, string $replaceWith = ' ' /* one space */): string;
   
   /**
    * Convert a string to dashed case.
    * 
    * @param $subject The string to modify.
    * 
    * @return string The resulting string.
    */
   
   static function strToDashedCase(string $subject): string;
   
   /**
    * 
    * Serialize something (except closures).
    * 
    * @param type $something
    * @return string
    */
   
   static function serialize($something): string;
   
   /**
    * 
    * Clone an object optimistically - in other words, take what we can and skip what we can't.
    * 
    * @param object $something 
    * @return ?object
    */   
   
   static function optimisticClone(object $obj, bool $excludeClosures = true, int $levels = null): ?object;
   
    /**
     * 
     * Create a semi-constant value, based on the position from where it is called in the source code.
     * 
     * The value is 'semi'-constant, as it will always remain the same as long as the call isn't moved 
     * textually (e.g. moved to a different column or row in the source).
     * 
     */
   
   static function getLineAnchor(int $backTraceDepth = 1): string;
   
   /**
    * 
    * Base64 encode a string either with the native PHP base64_encode() function; or using an URL safe
    * version (compatible with Python).
    * 
    * @param string $string
    * @param bool $urlSafe
    * @return string
    */
   
   static function base64Encode(string $string, bool $urlSafe = false): string;
   
   /**
    * 
    * Base64 decode a string either with the native PHP base64_decode() function; or using an URL safe
    * version (compatible with Python).
    * 
    * @param string $string
    * @param bool $urlSafe
    * @return string
    */
   
   static function base64Decode(string $string, bool $urlSafe = false): string;  
   
   /**
    * 
    * Generates a string of random bytes (either using random_bytes() if available - otherwise using rand(0, 255) and chr().
    * 
    * @param int $size
    * @return string
    */
   
   static function randomBytes(int $size): string;
}