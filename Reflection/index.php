<?php

/**
 * ReflectionClass
 */

class A
{
    private $message = "Hello world";

    public function __construct($message)
    {
        $this->message = $message;
    }
}

class B extends A
{

}

$refClass = new ReflectionClass('\A');
var_dump($refClass->getProperties(ReflectionProperty::IS_PRIVATE));

$refClassB = new ReflectionClass('\B');
var_dump($refClassB->isSubclassOf('\A'));

$messageProperty = $refClass->getProperty('message');
var_dump($messageProperty);