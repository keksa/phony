<?php

class MockGeneratorTraitConstructor
implements \Eloquent\Phony\Mock\Mock
{
    use \Eloquent\Phony\Test\TestTraitD
    {
        \Eloquent\Phony\Test\TestTraitD::__construct
            as private _callTrait_Eloquent¦Phony¦Test¦TestTraitD»__construct;
    }

    public function __construct()
    {
    }

    private static function _callTraitStatic(
        $traitName,
        $name,
        \Eloquent\Phony\Call\Arguments $arguments
    ) {
        $name = '_callTrait_' .
            \str_replace('\\', "\xc2\xa6", $traitName) .
            "\xc2\xbb" .
            $name;

        return self::$name(...$arguments->all());
    }

    private function _callParentConstructor(
        \Eloquent\Phony\Call\Arguments $arguments
    ) {
        $this->_callTrait_Eloquent¦Phony¦Test¦TestTraitD»__construct(...$arguments->all());
    }

    private function _callTrait(
        $traitName,
        $name,
        \Eloquent\Phony\Call\Arguments $arguments
    ) {
        $name = '_callTrait_' .
            \str_replace('\\', "\xc2\xa6", $traitName) .
            "\xc2\xbb" .
            $name;

        return $this->$name(...$arguments->all());
    }

    private static $_uncallableMethods = [];
    private static $_traitMethods = array (
  '__construct' => 'Eloquent\\Phony\\Test\\TestTraitD',
);
    private static $_customMethods = [];
    private static $_staticHandle;
    private $_handle;
}
