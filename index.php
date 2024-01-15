<?php

/*
 * See license information at the package root in LICENSE.md
 */
$bootstrap = realpath(__DIR__ . "/vendor/ion/packaging/bootstrap.php");

if(!empty($bootstrap))
    require_once($bootstrap);

\Ion\Package::create("ion", "php-helper", function($package) {

    return \Ion\Autoloading\Autoloader::create(
        
        $package, 
        [ 
            "source/classes",
            "source/interfaces",
            "source/traits"
        ], 
        [
            "builds/" . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION,
            "builds/" . PHP_MAJOR_VERSION,
        ]
    );
    
}, __FILE__);

