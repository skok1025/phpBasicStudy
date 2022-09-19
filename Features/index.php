<?php

/**
 * Properties and Methods
 */
class A
{
    public $message = 'Hello world';

    public function foo()
    {
        return $this->message;
    }
}

$a = new A();
var_dump($a->foo());