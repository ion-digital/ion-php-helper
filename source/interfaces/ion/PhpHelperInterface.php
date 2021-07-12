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



interface PhpHelperInterface {


    static function isAssociativeArray(array $array): bool;

    static function isEmpty($value, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true): bool;

    static function inherits(string $childClassName, string $parentClassName): bool;

    static function isObject($variable, string $className = null, bool $parent = true, bool $class = true): bool;

    static function isArray($variable, bool $isAssociative = true): bool;

    static function isString($variable = null): bool;

    static function isReal($variable = null): bool;

    static function isFloat($variable = null): bool;

    static function isDouble($variable = null): bool;

    static function isNumeric($variable = null): bool;

    static function hasDecimals($variable = null): bool;

    static function isInt($variable = null): bool;

    static function isBool($variable = null): bool;

    static function isNull($variable = null): bool;

    static function isCallable($variable = null): bool;

    static function isType(string $typeString, $variable = null): bool;

    static function toArray($variable = null, bool $allowDeserialization = false, string $splitCharacterIfString = null): ?array;

    static function toString($variable = null, bool $allowDeserialization = false, string $joinCharacterIfArray = null): ?string;

    static function toFloat($variable = null, bool $allowDeserialization = false): ?float;

    static function toInt($variable = null, bool $allowDeserialization = false): ?int;

    static function toBool($variable = null, bool $allowDeserialization = false, bool $checkElementsIfArray = false): ?bool;

    static function getObjectProperties(object $object, bool $public = true, bool $protected = false, bool $private = false): array;

    static function getObjectPropertyValues(object $object, bool $public = true, bool $protected = false, bool $private = false): array;

    static function getObjectMethods(object $object, bool $public = true, bool $protected = false, bool $private = false, bool $abstract = true, bool $final = true): array;

    static function getArrayHash(array $array): int;

    static function getObjectHash(object $object): int;

    static function getServerRequestUri(bool $includeHost = false, bool $includeProtocol = true): ?string;

    static function getServerReferrerUri(): ?string;

    static function getServerDocumentRoot(): ?string;

    static function isCommandLine(): bool;

    static function isWebServer(): bool;

    static function isCountable($variable): bool;

    static function toNull($variable, bool $orWhiteSpaceIfString = true, bool $orEmptyIfArray = true);

    static function filterInput(string $variableName, array $inputs = [], int $filter = null, array $options = []);

    static function obGet(callable $closure, ...$parameters): ?string;

    static function strEndsWith(string $string, string $subString): bool;

    static function strStartsWith(string $string, string $subString): bool;

    static function count($variable): ?int;

    static function strContains(string $string, string $subString, int $position = null): bool;

    static function getCallingPath(): string;

    static function getCallingClass(): string;

    static function strReplaceAll(array $strings, string $replacement, string $subject, bool $ignoreCase = false, int &$count = null): string;

    static function strStripWhiteSpace(string $subject, string $replaceWith = " "): string;

    static function strToDashedCase(string $subject, string $dash = "-"): string;

    static function optimisticClone(object $obj, bool $excludeClosures = true, int $levels = null): ?object;

    static function serialize($something = null): string;

    static function unserialize(string $something, bool $strict = false);

    static function getLineAnchor(int $backTraceDepth = 1): string;

    static function base64Encode(string $string, bool $urlSafe = false): string;

    static function base64Decode(string $string, bool $urlSafe = false): string;

    static function randomBytes(int $length): string;
}
