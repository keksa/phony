<?php

use Eloquent\Phony\Test\Phony;

// setup
$stub = Phony::stub()->setLabel('label')->setUseTraversableSpies(true);
$stub->with('aardvark')->returns('AARDVARK');
$stub->with('bonobo')->throws(new RuntimeException('BONOBO'));
$stub->with('chameleon')->returns(array('CHAMELEON'));
$stub->with('dugong')->generates(array('DUGONG'));
$stub('aardvark');
try {
    $stub('bonobo');
} catch (RuntimeException $e) {
}
$stub('chameleon');
$stub('dugong');

// verification
$stub->traversed()->produced();