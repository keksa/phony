<?php

use Eloquent\Phony\Test\Phony;

// setup
$stub = Phony::stub()->setLabel('label')->setUseIterableSpies(true);
$stub->with('aardvark')->generates(array('AARDVARK', 'ANTEATER'));
$stub->with('bonobo')->generates(array('BONOBO', 'BADGER'));
$generator = $stub('aardvark');
$generator->send('MECHA-AARDVARK');
$generator->send('MECHA-ANTEATER');
$generator = $stub('bonobo');
$generator->send('MECHA-BONOBO');
$generator->send('MECHA-BADGER');
// verification
$stub->generated()->times(3)->received();
