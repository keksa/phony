<?php

use Eloquent\Phony\Test\Phony;

// setup
$stub = Phony::stub()->setLabel('label')->setUseTraversableSpies(true);
$stub->with('aardvark')->returns('AARDVARK');
$stub->with('bonobo')->returns(array('BONOBO'));
$stub('aardvark');
$stub('bonobo');

// verification
$stub->never()->traversed();