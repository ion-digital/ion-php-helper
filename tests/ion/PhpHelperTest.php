<?php

/*
 * See license information at the package root in LICENSE.md
 */


namespace ion;

/**
 * Description of PhpHelperTest
 *
 * @author Justus
 */

use PHPUnit\Framework\TestCase;
use \ion\PhpHelper as PHP;
use \ion\SystemType;
use \Countable;
use \Exception;
use \Throwable;
use \Error;

interface InterfaceA {
    
}

interface InterfaceB extends InterfaceA {
    
}

interface InterfaceC extends InterfaceB {
    
}

class ClassA implements InterfaceA {
    
}

class ClassB extends ClassA implements InterfaceB {
    
}

class ClassC extends ClassB implements InterfaceC {
    
}

class ClassD {
    private $private = 'private';
    protected $protected = 'protected';
    public $public = 'public';
	
    private function private() { }
    protected function protected() { }
    public function public() { }
	
}

class ClassE {
    public $int = 1;
    public $float = 1.111;
    public $string = 'STRING';
    public $bool = true;
}

class ClassF {
    public $int = 1;
    public $float = 1.111;
    public $string = 'STRING';
    public $bool = true;
    public $object = null;
    
    public function __construct() {
        
        $this->object = new ClassE();
    }
}

class ClassG extends ClassF {
    
    public static $static = 123;
    
    public static $staticClosure = null;
    
    public $closure = null;
    
    public function __construct() {
        
        parent::__construct();
        
        $this->closure = function() { return; };
        static::$staticClosure = function() { return; };
    }
}

class CountableClass implements \Countable {
    
    public function count() {
        return 0;
    }
}

class CallingClass {

    public static function callFromStatic() {
        
        return static::testStatic();
    }
    
    public static function testStatic() {
        
        return PHP::getCallingClass();
    }
    
    public function callFromInstance() {

        return $this->testInstance();
    }

    public function testInstance() {

        return PHP::getCallingClass();
    }
}

class PhpHelperTest extends TestCase {
    
    const SLUG = 'the-quick-brown-fox-jumps-over-the-lazy-dog-1-2-3';
    const SINGLE_LINE_SENTENCE = 'The quick brown fox, jumps over the lazy dog 1 2 3.';    
    
    public function testIsAssociativeArray() {
        
        //isAssociativeArray(array $array)
        
        $assocArray = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3'
        ];
        
        $nonAssocArray = [
            'value_1',
            'value_2',
            'value_3'            
        ];
        
        $mixedArray = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'value_3',
            1,
            2,
            3
        ];
        
        $this->assertEquals(true, PHP::isAssociativeArray($assocArray));
        
        $this->assertEquals(false, PHP::isAssociativeArray($nonAssocArray));
        
        $this->assertEquals(true, PHP::isAssociativeArray($mixedArray));
        
        
    }
    
    public function testIsEmpty() {
        
        $emptyValues = [
            '',
            0,
            false,
            null
        ];
        
        foreach($emptyValues as $emptyValue) {
            $this->assertEquals(PHP::isEmpty($emptyValue, false), true);
        }
        
        $this->assertEquals(PHP::isEmpty('    ', false), false);
        
        $nonEmptyValues = [
            '  ',
            1,
            true
        ];        
        
        foreach($nonEmptyValues as $nonEmptyValue) {
            $this->assertEquals(PHP::isEmpty($nonEmptyValue, false), false);
        }        
    }
        
        
    
    /**
     * @depends testIsEmpty
     */    
    public function testIsEmptyOrWhiteSpaceIfString() {
        
        //isEmpty(/* mixed */ $value, bool $orWhiteSpaceIfString = true)
        $this->assertEquals(true, PHP::isEmpty(null, true));
        $this->assertEquals(false, PHP::isEmpty('ABC', true));
        $this->assertEquals(true, PHP::isEmpty('', true));
        $this->assertEquals(true, PHP::isEmpty('    ', true));
        $this->assertEquals(false, PHP::isEmpty('    ', false));
        
    }    
    
    /**
     * @depends testIsEmpty
     */    
    public function testIsEmptyOrArrayIfEmpty() {
        
        //isEmpty(/* mixed */ $value, bool $orWhiteSpaceIfString = true)
        
        $this->assertEquals(true, PHP::isEmpty(null, false, false));
        $this->assertEquals(false, PHP::isEmpty([], false, false));
        $this->assertEquals(true, PHP::isEmpty([], false, true));
        $this->assertEquals(false, PHP::isEmpty([0,1,2], false, true));
        $this->assertEquals(false, PHP::isEmpty([0,1,2], false, true));
        
    }      
    
    public function testInherits() {
     
//        $classA = new ClassA();
//        $classB = new ClassB();
//        $classC = new ClassC();
        
        $this->assertEquals(false, PHP::inherits(ClassA::class, ClassA::class));       
        //$this->assertEquals(true, PHP::inherits(ClassA::class, ClassA::class, true));
        
        $this->assertEquals(true, PHP::inherits(ClassB::class, ClassA::class));
        //$this->assertEquals(true, PHP::inherits(ClassB::class, ClassA::class, true));
        
        $this->assertEquals(true, PHP::inherits(ClassC::class, ClassA::class));
        //$this->assertEquals(true, PHP::inherits(ClassC::class, ClassA::class, true));
        
        $this->assertEquals(true, PHP::inherits(ClassC::class, ClassB::class));
        //$this->assertEquals(true, PHP::inherits(ClassC::class, ClassB::class, true));
        
    }
    
    public function testIsObject() {
      
        $int = 123;
        $string = 'string';
        $bool = true;
        $float = 1.234;
        
        $classA = new ClassA();
        $classB = new ClassB();
        $classC = new ClassC();
        
        $this->assertEquals(false, PHP::isObject($int));
        $this->assertEquals(false, PHP::isObject($string));
        $this->assertEquals(false, PHP::isObject($bool));
        $this->assertEquals(false, PHP::isObject($float));        
        
        $this->assertEquals(false, PHP::isObject($int, ClassA::class));
        $this->assertEquals(false, PHP::isObject($string, ClassA::class));
        $this->assertEquals(false, PHP::isObject($bool, ClassA::class));
        $this->assertEquals(false, PHP::isObject($float, ClassA::class));
        
        $this->assertEquals(true, PHP::isObject($classA));
        $this->assertEquals(true, PHP::isObject($classB));
        $this->assertEquals(true, PHP::isObject($classC));        
        
        $this->assertEquals(false, PHP::isObject($classA, ClassA::class, true, false));
        $this->assertEquals(true, PHP::isObject($classA, ClassA::class, true, true));        
        $this->assertEquals(false, PHP::isObject($classB, ClassB::class, true, false));
        $this->assertEquals(true, PHP::isObject($classB, ClassB::class, true, true));
        $this->assertEquals(false, PHP::isObject($classC, ClassC::class, true, false));
        $this->assertEquals(true, PHP::isObject($classC, ClassC::class, true, true));
        
        $this->assertEquals(true, PHP::isObject($classB, ClassA::class, true, false));        
        $this->assertEquals(true, PHP::isObject($classC, ClassA::class, true, false));
        $this->assertEquals(true, PHP::isObject($classC, ClassB::class, true, false));
        
        $this->assertEquals(true, PHP::isObject($classA, 'ion\\InterfaceA', true, true));

        
        $this->assertEquals(true, PHP::isObject($classC, ClassC::class, false));        
        $this->assertEquals(false, PHP::isObject($classC, ClassA::class, false));       
    }
    
    public function testIsArray() {
        $flatArray = [ 1, 2, 3 ];
        $assocArray = [ 'A' => 1, 'B' => 2, 'C' => 3];
        
        $this->assertEquals(true, PHP::isArray($flatArray, true));
        $this->assertEquals(true, PHP::isArray($flatArray, false));
        
        $this->assertEquals(true, PHP::isArray($assocArray, true));
        $this->assertEquals(false, PHP::isArray($assocArray, false));
    }
    
    public function testIsString() {
        
        $string = 'STRING';
        $int = 1;
        $float = (float) 0.123;
        $double = (double) 0.123;
        $bool = false;
        $array = [];
        $object = new ClassA();
        
        $this->assertEquals(true, PHP::isString($string));
        $this->assertEquals(false, PHP::isString($int));
        $this->assertEquals(false, PHP::isString($float));
        $this->assertEquals(false, PHP::isString($double));
        $this->assertEquals(false, PHP::isString($bool));
        $this->assertEquals(false, PHP::isString($array));
        $this->assertEquals(false, PHP::isString($object));
        
    }
    
    public function testIsReal() {
        
        $string = 'STRING';
        $int = 1;
        $float = (float) 0.123;
        $bool = false;
        $array = [];
        $object = new ClassA();
        
        $this->assertEquals(false, PHP::isReal($string));
        $this->assertEquals(false, PHP::isReal($int));
        $this->assertEquals(true, PHP::isReal($float));
        $this->assertEquals(false, PHP::isReal($bool));
        $this->assertEquals(false, PHP::isReal($array));
        $this->assertEquals(false, PHP::isReal($object));        
    }
    
    public function testIsDouble() {
        
        $string = 'STRING';
        $int = 1;
        $float = (float) 0.123;
        $bool = false;
        $array = [];
        $object = new ClassA();
        
        $this->assertEquals(false, PHP::isDouble($string));
        $this->assertEquals(false, PHP::isDouble($int));
        $this->assertEquals(true, PHP::isDouble($float));
        $this->assertEquals(false, PHP::isDouble($bool));
        $this->assertEquals(false, PHP::isDouble($array));
        $this->assertEquals(false, PHP::isDouble($object));        
    }    
    
    public function testIsNumeric() {
        
        $string = 'STRING';
        $int = 1;
        $float = (float) 0.123;
        $bool = false;
        $array = [];
        $object = new ClassA();
        
        $this->assertEquals(false, PHP::isNumeric($string));
        $this->assertEquals(true, PHP::isNumeric($int));
        $this->assertEquals(true, PHP::isNumeric($float));
        $this->assertEquals(false, PHP::isNumeric($bool));
        $this->assertEquals(false, PHP::isNumeric($array));
        $this->assertEquals(false, PHP::isNumeric($object));        
    }
    
    public function testIsBool() {
        
        $string = 'STRING';
        $int = 1;
        $float = (float) 0.123;
        $bool = false;
        $array = [];
        $object = new ClassA();
        
        $this->assertEquals(false, PHP::isBool($string));
        $this->assertEquals(false, PHP::isBool($int));
        $this->assertEquals(false, PHP::isBool($float));
        $this->assertEquals(true, PHP::isBool($bool));
        $this->assertEquals(false, PHP::isBool($array));
        $this->assertEquals(false, PHP::isBool($object));        
    }
    
    public function testIsCallable() {
        
        $this->assertEquals(false, PHP::isCallable(null));
        $this->assertEquals(true, PHP::isCallable(function() { /* empty!*/ }));
        $this->assertEquals(false, PHP::isCallable(0));
        $this->assertEquals(false, PHP::isCallable(0.0));
        $this->assertEquals(false, PHP::isCallable(true));
        $this->assertEquals(false, PHP::isCallable(false));
        $this->assertEquals(false, PHP::isCallable([]));
        $this->assertEquals(false, PHP::isCallable(new \stdClass()));        
    }
    
    public function testIsNull() {
        
        $this->assertEquals(true, PHP::isNull(null));
        $this->assertEquals(false, PHP::isNull(0));
        $this->assertEquals(false, PHP::isNull(0.0));
        $this->assertEquals(false, PHP::isNull(true));
        $this->assertEquals(false, PHP::isNull(false));
        $this->assertEquals(false, PHP::isNull([]));
        $this->assertEquals(false, PHP::isNull(new \stdClass()));
    }    
    
    public function testIsType() {
        
        $string = 'STRING';
        $int = 1;
        $float = 0.123;
        $bool = false;
        $array = [];
        $object = new ClassA();
        
        // string
        
        $this->assertEquals(true, PHP::isType('string', $string));
        $this->assertEquals(false, PHP::isType('int', $string));
        $this->assertEquals(false, PHP::isType('float', $string));
        $this->assertEquals(false, PHP::isType('double', $string));
        $this->assertEquals(false, PHP::isType('bool', $string));
        $this->assertEquals(false, PHP::isType('array', $string));
        $this->assertEquals(false, PHP::isType('object', $string));         
        $this->assertEquals(false, PHP::isType('ion\\ClassA', $string));
        
        // int
        
        $this->assertEquals(false, PHP::isType('string', $int));
        $this->assertEquals(true, PHP::isType('int', $int));
        $this->assertEquals(false, PHP::isType('float', $int));
        $this->assertEquals(false, PHP::isType('double', $int));
        $this->assertEquals(false, PHP::isType('bool', $int));
        $this->assertEquals(false, PHP::isType('array', $int));
        $this->assertEquals(false, PHP::isType('object', $int));         
        $this->assertEquals(false, PHP::isType('ion\\ClassA', $int));

        // float
        
        $this->assertEquals(false, PHP::isType('string', $float));
        $this->assertEquals(false, PHP::isType('int', $float));
        $this->assertEquals(true, PHP::isType('float', $float));        
        $this->assertEquals(true, PHP::isType('double', $float));
        $this->assertEquals(false, PHP::isType('bool', $float));
        $this->assertEquals(false, PHP::isType('array', $float));
        $this->assertEquals(false, PHP::isType('object', $float));         
        $this->assertEquals(false, PHP::isType('ion\\ClassA', $float));     
        
        // bool
        
        $this->assertEquals(false, PHP::isType('string', $bool));
        $this->assertEquals(false, PHP::isType('int', $bool));
        $this->assertEquals(false, PHP::isType('float', $bool));
        $this->assertEquals(false, PHP::isType('double', $bool));
        $this->assertEquals(true, PHP::isType('bool', $bool));
        $this->assertEquals(false, PHP::isType('array', $bool));
        $this->assertEquals(false, PHP::isType('object', $bool));         
        $this->assertEquals(false, PHP::isType('ion\\ClassA', $bool));

        // array
        
        $this->assertEquals(false, PHP::isType('string', $array));
        $this->assertEquals(false, PHP::isType('int', $array));
        $this->assertEquals(false, PHP::isType('float', $array));
        $this->assertEquals(false, PHP::isType('double', $array));
        $this->assertEquals(false, PHP::isType('bool', $array));
        $this->assertEquals(true, PHP::isType('array', $array));
        $this->assertEquals(false, PHP::isType('object', $array));         
        $this->assertEquals(false, PHP::isType('ion\\ClassA', $array));

        // object / class
        
        $this->assertEquals(false, PHP::isType('string', $object));
        $this->assertEquals(false, PHP::isType('int', $object));
        $this->assertEquals(false, PHP::isType('float', $object));
        $this->assertEquals(false, PHP::isType('double', $object));
        $this->assertEquals(false, PHP::isType('bool', $object));
        $this->assertEquals(false, PHP::isType('array', $object));
        $this->assertEquals(true, PHP::isType('object', $object));         
        $this->assertEquals(true, PHP::isType('ion\\ClassA', $object));
        $this->assertEquals(false, PHP::isType('non_existent_class', $object));
        
    }
    
    public function testGetObjectProperties() {
        $classD = new ClassD();
        
        $this->assertEquals(1, count(PHP::getObjectProperties($classD, true, false, false)));
        $this->assertEquals(1, count(PHP::getObjectProperties($classD, false, true, false)));
        $this->assertEquals(1, count(PHP::getObjectProperties($classD, false, false, true)));
        
        $this->assertEquals(1, count(PHP::getObjectProperties($classD, true, false, false)));
        $this->assertEquals(2, count(PHP::getObjectProperties($classD, true, true, false)));
        $this->assertEquals(3, count(PHP::getObjectProperties($classD, true, true, true)));      
        

        
    }
	
    public function testGetObjectPropertyValues() {
        $classD = new ClassD();
        
        $this->assertEquals(1, count(PHP::getObjectPropertyValues($classD, true, false, false)));
        $this->assertEquals(1, count(PHP::getObjectPropertyValues($classD, false, true, false)));
        $this->assertEquals(1, count(PHP::getObjectPropertyValues($classD, false, false, true)));
        
        $this->assertEquals(1, count(PHP::getObjectPropertyValues($classD, true, false, false)));
        $this->assertEquals(2, count(PHP::getObjectPropertyValues($classD, true, true, false)));
        $this->assertEquals(3, count(PHP::getObjectPropertyValues($classD, true, true, true)));      
        
        $values = PHP::getObjectPropertyValues($classD, true, true, true);

        $this->assertEquals('private', $values['private']);
        $this->assertEquals('protected', $values['protected']);
        $this->assertEquals('public', $values['public']);
        
    }	
    
    public function testGetObjectMethods() {
                
        $classD = new ClassD();
        
        $this->assertEquals(1, count(PHP::getObjectMethods($classD, true, false, false)));
        $this->assertEquals(1, count(PHP::getObjectMethods($classD, false, true, false)));
        $this->assertEquals(1, count(PHP::getObjectMethods($classD, false, false, true)));
        
        $this->assertEquals(1, count(PHP::getObjectMethods($classD, true, false, false)));
        $this->assertEquals(2, count(PHP::getObjectMethods($classD, true, true, false)));
        $this->assertEquals(3, count(PHP::getObjectMethods($classD, true, true, true)));      

    }	
    
    public function testGetArrayHash() {
        
        $array1 = [ 1, 1.1, true, false, new ClassF(), [ 3, 4, 5 ] ];
        
        $hash1 = PHP::getArrayHash($array1);
        
        //$this->assertEquals(173265430, $hash);        
        $this->assertNotNull($hash1);
        
        
        $array2 = [ 'obj' => new ClassF() ];
        
        $hash2 = PHP::getArrayHash($array2);
        
        $this->assertNotNull($hash2);
    }
	
    public function testGetObjectHash() {
        
        $classF = new ClassF();
        
        $hash = PHP::getObjectHash($classF);
        
        //$this->assertEquals(1093683956, $hash);
        $this->assertNotNull($hash);
        
    }
    
    public function testGetServerRequestUri() {

        $this->assertEquals(null, PHP::getServerRequestUri());
    }
    
    public function testGetServerDocumentRoot() {

        $this->assertEquals(null, PHP::getServerDocumentRoot());
    }    
    
    public function testIsCommandLine() {
        $this->assertEquals(true, PHP::isCommandLine());
    }
    
    public function testIsWebServer() {
        $this->assertEquals(false, PHP::isWebServer());
    }
    
    public function testIsCountable() {
        
        $array = [1, 2, 3];
        $countableObject = new CountableClass();
        $nonCountableObject = new ClassA();
        $string = "ABC";
        $float = 0.1;
        
        
        $this->assertEquals(true, PHP::isCountable($array));
        $this->assertEquals(true, PHP::isCountable($countableObject));
        $this->assertEquals(false, PHP::isCountable($nonCountableObject));
        $this->assertEquals(false, PHP::isCountable($string));
        $this->assertEquals(false, PHP::isCountable($float));
        
    }
    
    public function testToNull() {                
        
        $this->assertEquals(null, PHP::toNull(null, false));
        $this->assertEquals(null, PHP::toNull(false, false));                   
        $this->assertEquals(null, PHP::toNull('', false));         
        $this->assertEquals(null, PHP::toNull(0, false));
        $this->assertEquals(null, PHP::toNull([], false));
        
        $this->assertEquals(null, PHP::toNull(null, true));
        $this->assertEquals(null, PHP::toNull(false, true));                   
        $this->assertEquals(null, PHP::toNull('', true));         
        $this->assertEquals(null, PHP::toNull(0, true));        
        $this->assertEquals(null, PHP::toNull([], true)); 
        
        $this->assertEquals(true, PHP::toNull(true, false));
        $this->assertEquals(1, PHP::toNull(1, false));        
        $this->assertEquals(' ', PHP::toNull(' ', false));
        
        $this->assertEquals(true, PHP::toNull(true, true));
        $this->assertEquals(1, PHP::toNull(1, true));
        $this->assertEquals(null, PHP::toNull(' ', true));        
    }

    
    public function testFilterInput() {
        
        // This just calls the method a couple of times - not sure how to modify any filterable variables?
        
        // http://php.net/manual/en/filter.filters.validate.php
        
        $this->assertEquals(null, PHP::filterInput('null', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('empty_int', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('int', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('empty_dec', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('dec', [ INPUT_POST ]));   
        $this->assertEquals(null, PHP::filterInput('empty_bool', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('bool', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('empty_string', [ INPUT_POST ]));
        $this->assertEquals(null, PHP::filterInput('string', [ INPUT_POST ]));
        
    }
    
//    public function testGetCurrentSystem() {
//        $systemType = PHP::getOperatingSystemType();
//    }
    
    public function testToArray() {
        
        $this->assertEquals(null, PHP::toArray(null));
        $this->assertEquals(null, PHP::toArray(true));
        $this->assertEquals(null, PHP::toArray(false));
        $this->assertEquals(null, PHP::toArray(0));
        $this->assertEquals(null, PHP::toArray(0.0));    
        $this->assertEquals(null, PHP::toArray(''));
        $this->assertEquals(null, PHP::toArray('', ','));
        $this->assertEquals([1,2,3], PHP::toArray('1,2,3', false, ','));
        
        $this->assertEquals([1,2,3], PHP::toArray(serialize([1,2,3]), true));
        $this->assertEquals([1,2,3], PHP::toArray(json_encode([1,2,3]), true));
    }
    
    public function testToString() {

        $this->assertEquals(null, PHP::toString(null));
        $this->assertEquals(null, PHP::toString(true));
        $this->assertEquals(null, PHP::toString(false));
        $this->assertEquals('', PHP::toString(''));
        $this->assertEquals(null, PHP::toString(0));
        $this->assertEquals(null, PHP::toString(0.0));        
        $this->assertEquals(null, PHP::toString([]));        
        $this->assertEquals('', PHP::toString([], false, ','));        
        $this->assertEquals('1,2,3', PHP::toString([1,2,3], false, ','));        
        
        $this->assertEquals('123', PHP::toString(serialize('123'), true));
        $this->assertEquals('123', PHP::toString(json_encode('123'), true));            
    }

    public function testToFloat() {
        
        $this->assertEquals(null, PHP::toFloat(null));
        $this->assertEquals(null, PHP::toFloat(true));
        $this->assertEquals(null, PHP::toFloat(false));
        $this->assertEquals(null, PHP::toFloat(''));
        $this->assertEquals(null, PHP::toFloat(0));
        $this->assertEquals(0.0, PHP::toFloat(0.0));        
        $this->assertEquals(null, PHP::toFloat([]));    
        
        $this->assertEquals(0, PHP::toFloat('0'));
        $this->assertEquals(1, PHP::toFloat('1'));
        $this->assertEquals(2, PHP::toFloat('2'));
        $this->assertEquals(1.0, PHP::toFloat('1.0'));
        $this->assertEquals(1.1, PHP::toFloat('1.1'));
        $this->assertEquals(null, PHP::toFloat('something'));      
        
        $this->assertEquals(1.23, PHP::toFloat(serialize(1.23), true));
        $this->assertEquals(1.23, PHP::toFloat(json_encode(1.23), true));       
    }

    public function testToInt() {
     
        $this->assertEquals(null, PHP::toInt(null));
        $this->assertEquals(null, PHP::toInt(true));
        $this->assertEquals(null, PHP::toInt(false));
        $this->assertEquals(null, PHP::toInt(''));
        $this->assertEquals(0, PHP::toInt(0));
        $this->assertEquals(null, PHP::toInt(0.0));        
        $this->assertEquals(null, PHP::toInt([]));    
        
        $this->assertEquals(0, PHP::toInt('0'));
        $this->assertEquals(1, PHP::toInt('1'));
        $this->assertEquals(2, PHP::toInt('2'));
        $this->assertEquals(1, PHP::toInt('1.0'));
        $this->assertEquals(null, PHP::toInt('1.1'));
        $this->assertEquals(null, PHP::toInt('something'));

        $this->assertEquals(123, PHP::toInt(serialize(123), true));
        $this->assertEquals(123, PHP::toInt(json_encode(123), true));          
    }

    public function testToBool() {
        
        $this->assertEquals(null, PHP::toBool(null));
        $this->assertEquals(true, PHP::toBool(true));
        $this->assertEquals(false, PHP::toBool(false));
        $this->assertEquals(null, PHP::toBool(''));
        $this->assertEquals(null, PHP::toBool(0));
        $this->assertEquals(null, PHP::toBool(0.0));        
        $this->assertEquals(null, PHP::toBool([]));       
        
        $this->assertEquals(true, PHP::toBool('true'));
        $this->assertEquals(true, PHP::toBool('yes'));
        $this->assertEquals(false, PHP::toBool('no'));
        $this->assertEquals(false, PHP::toBool('false'));
        $this->assertEquals(false, PHP::toBool('0'));
        $this->assertEquals(true, PHP::toBool('1'));
        $this->assertEquals(null, PHP::toBool('something'));
        
        $this->assertEquals(true, PHP::toBool(serialize(true), true));
        $this->assertEquals(false, PHP::toBool(serialize(false), true));
        $this->assertEquals(true, PHP::toBool(json_encode(true), true));         
        $this->assertEquals(false, PHP::toBool(json_encode(false), true));
    }    

    public function testHasDecimals() {
        
        $this->assertEquals(false, PHP::hasDecimals(0));
        $this->assertEquals(false, PHP::hasDecimals(1));
        $this->assertEquals(false, PHP::hasDecimals(0.0));
        $this->assertEquals(true, PHP::hasDecimals(0.1));
        $this->assertEquals(true, PHP::hasDecimals(1.1));
        $this->assertEquals(false, PHP::hasDecimals(1.0));
        
    }
    
    public function testObGet() {
        
        $closure = function($x = null, $y = null) {
            
            echo ($x === null ? '' : $x) . ($y === null ? '' : $y) ;
        };
        
        $this->assertEquals('XY', PHP::obGet($closure, 'X', 'Y'));
        $this->assertEquals(null, PHP::obGet($closure, ''));
    }
    
    public function testEndsWith() {
        
        $this->assertEquals(true, PHP::strEndsWith('ABCD', 'CD'));
        $this->assertEquals(false, PHP::strEndsWith('ABCD', 'AB'));
        $this->assertEquals(false, PHP::strEndsWith('ABCD', 'YZ'));
    }
    
    public function testStartsWith() {
        
        $this->assertEquals(false, PHP::strStartsWith('ABCD', 'CD'));
        $this->assertEquals(true, PHP::strStartsWith('ABCD', 'AB'));
        $this->assertEquals(false, PHP::strStartsWith('ABCD', 'YZ'));        
    }

    public function testCount() {
        
        $this->assertEquals(0, PHP::count(new CountableClass()));
        $this->assertEquals(3, PHP::count([1, 2, 3]));
        $this->assertEquals(null, PHP::count('ABCD'));
        $this->assertEquals(null, PHP::count(1));
        $this->assertEquals(null, PHP::count(1.2));
    }    
    
    public function testContains() {
        
        $this->assertEquals(true, PHP::strContains('ABCD', 'CD'));
        $this->assertEquals(true, PHP::strContains('ABCD', 'AB'));
        $this->assertEquals(true, PHP::strContains('ABCD', 'BC'));
        
        $this->assertEquals(true, PHP::strContains('ABCD', 'CD', 2));
        $this->assertEquals(true, PHP::strContains('ABCD', 'AB', 0));
        $this->assertEquals(true, PHP::strContains('ABCD', 'BC', 1));        
        
        $this->assertEquals(false, PHP::strContains('ABCD', 'CD', 0));
        $this->assertEquals(false, PHP::strContains('ABCD', 'AB', 2));
        $this->assertEquals(false, PHP::strContains('ABCD', 'BC', 2));                 
        
        $this->assertEquals(false, PHP::strContains('ABCD', 'YZ'));        
    }
    
    private function callingPathTestEnclosingMethod() {
        
        return PHP::getCallingPath();
    }
    
    public function testGetCallingPath() {                
        
        $this->assertEquals(__FILE__, self::callingPathTestEnclosingMethod());
        
        $this->expectException(PhpHelperException::class);
        PHP::getCallingPath();
    }
    
//    public function testGetParameters() {
//        
//        $callable1 = function(int $int, float $float, string $string): array {
//            
//            return PHP::getParameters();
//        };
//        
//        $parameters = $callable1(1, 1.1, 'string');   
//        
//        var_Dump($parameters);
//        
//        $this->assertEquals(3, PHP::count($parameters));
//        
//        
//        
//        
//    }
    
    public function testReplaceAll() {
                
        $this->assertEquals('1X2X3X4X5X6X', PHP::strReplaceAll([ 'A', 'B', 'C' ], 'X', '1A2B3C4a5b6c', true));        
        $this->assertEquals('1X2X3X4a5b6c', PHP::strReplaceAll([ 'A', 'B', 'C' ], 'X', '1A2B3C4a5b6c', false));   
        
        $count = 0;
        $this->assertEquals('YYYYYY', PHP::strReplaceAll([ 'X', 'Z' ], 'Y', 'XXXZZZ', false, $count));
        $this->assertEquals(6, $count);
    }
    
    public function testStripWhiteSpace() {
        
        $this->assertEquals('12345', PHP::strStripWhiteSpace("    1\n2\r3\t4 5    ", ''));
        $this->assertEquals(' 1 2 3 4 5 ', PHP::strStripWhiteSpace("    1\n2\r3\t4 5    ", ' '));
    }
    
    public function testStrToDashedCase() {

        $this->assertEquals(static::SLUG, PHP::strToDashedCase(static::SINGLE_LINE_SENTENCE));        
        $this->assertEquals(static::SLUG, PHP::strToDashedCase(PHP::strToDashedCase(static::SINGLE_LINE_SENTENCE)));        
        $this->assertEquals(static::SLUG, PHP::strToDashedCase(PHP::strToDashedCase(PHP::strToDashedCase(static::SINGLE_LINE_SENTENCE))));        
    }
    
    public function testSerialize() {
        
        $this->assertEquals(serialize('abc'), PHP::serialize('abc'));
        
//    public $int = 1;
//    public $float = 1.111;
//    public $string = 'STRING';
//    public $bool = true;
//    public $object = null;
    
        $serialized = PHP::serialize(new ClassG());
        
        $deserialized = unserialize($serialized);
        
        $this->assertEquals(1, $deserialized->int);
        $this->assertEquals(1.111, $deserialized->float);
        $this->assertEquals('STRING', $deserialized->string);
        $this->assertTrue($deserialized->bool);
        $this->assertNotNull($deserialized->object);
        $this->assertNull($deserialized->closure);
        
        $closure = PHP::serialize(function() { });        
        $this->assertNull(unserialize($closure));
    }
    
    public function testCloneObject() {
        
        $obj = new ClassG();
        
        $this->assertNotNull($obj->object);
                
        $deepClone1 = PHP::optimisticClone($obj, true);        
        $this->assertNull($deepClone1->closure);
        
        $deepClone2 = PHP::optimisticClone($obj, false);        
        $this->assertNotNull($deepClone2->closure);        

        $exceptionClone = PHP::optimisticClone(new Exception("Test"));
        $this->assertNull($exceptionClone);
        
        $errorClone = PHP::optimisticClone(new Error("Test"));
        $this->assertNull($errorClone);        

    }
    
    public function testGetCallingClass() {
        
        $obj = new CallingClass();
        
        $this->assertEquals(CallingClass::class, $obj->callFromInstance());        
        $this->assertEquals(CallingClass::class, CallingClass::callFromStatic());
        
        $this->assertEquals(self::class, PHP::getCallingClass());              
    }
    
    public function testLineAnchor() {
        
        $tmp = md5(__FILE__ . __LINE__); $anchor = PHP::getLineAnchor(); // These two statements need to be on one line!        
        
        $this->assertEquals($anchor, $tmp);        
    }
}
