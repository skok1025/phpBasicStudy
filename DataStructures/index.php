<?php

// 1) Stack
$st = new SplStack();

$st->push('Hello World');
$st->push('Hye');

var_dump($st->pop());



// 2) Queue
$qu = new SplQueue();
$qu->enqueue('Hello World');
$qu->enqueue('Bye');

var_dump($qu->dequeue());



// 3) Fixed Array
$array = new SplFixedArray(5);
foreach (range(0, 4) as $num) {
    $array[$num] = $num;
}

var_dump($array);



// 4) Object Storage
$storage  = new SplObjectStorage();

$o1 = new stdClass();
$o2 = new stdClass();

$storage->attach($o1, 'Hello World');
$storage->attach($o2, 'Hye');

var_dump($storage[$o1]);