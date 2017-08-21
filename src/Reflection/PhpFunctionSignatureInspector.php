<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2017 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Reflection;

use Eloquent\Phony\Invocation\InvocableInspector;
use ReflectionFunctionAbstract;

/**
 * Inspects functions to determine their signature under PHP.
 */
class PhpFunctionSignatureInspector extends FunctionSignatureInspector
{
    const PARAMETER_PATTERN = '/^\s*Parameter #\d+ \[ <(required|optional)> (\S+ )?(or NULL )?(&)?(?:\.\.\.)?\$(\S+)( = [^$]+)? ]$/m';

    /**
     * Construct a new function signature inspector.
     *
     * @param InvocableInspector $invocableInspector The invocable inspector to use.
     * @param FeatureDetector    $featureDetector    The feature detector to use.
     */
    public function __construct(
        InvocableInspector $invocableInspector,
        FeatureDetector $featureDetector
    ) {
        parent::__construct($invocableInspector);

        $this->isIterableTypeHintSupported = $featureDetector
            ->isSupported('type.iterable');
        $this->isObjectTypeHintSupported = $featureDetector
            ->isSupported('type.object');
    }

    /**
     * Get the function signature of the supplied function.
     *
     * @param ReflectionFunctionAbstract $function The function.
     *
     * @return array<string,array<string>> The function signature.
     */
    public function signature(ReflectionFunctionAbstract $function): array
    {
        $isMatch = preg_match_all(
            static::PARAMETER_PATTERN,
            $function,
            $matches,
            PREG_SET_ORDER
        );

        if (!$isMatch) {
            return [];
        }

        $parameters = $function->getParameters();
        $signature = [];
        $index = -1;

        foreach ($matches as $match) {
            $parameter = $parameters[++$index];

            $typehint = $match[2];

            if ('self ' === $typehint) {
                $typehint = '\\' . $parameter->getDeclaringClass()->getName()
                    . ' ';
            } elseif (
                '' !== $typehint &&
                'array ' !== $typehint &&
                'callable ' !== $typehint &&
                (
                    !$this->isIterableTypeHintSupported ||
                    'iterable ' !== $typehint
                ) &&
                (
                    !$this->isObjectTypeHintSupported ||
                    'object ' !== $typehint
                )
            ) {
                if (
                    'integer ' === $typehint &&
                    $parameter->getType()->isBuiltin()
                ) {
                    $typehint = 'int ';
                } elseif (
                    'boolean ' === $typehint &&
                    $parameter->getType()->isBuiltin()
                ) {
                    $typehint = 'bool ';
                } elseif ('float ' !== $typehint && 'string ' !== $typehint) {
                    $typehint = '\\' . $typehint;
                }
            }

            $byReference = $match[4];

            if ($parameter->isVariadic()) {
                $variadic = '...';
                $optional = false;
            } else {
                $variadic = '';
                $optional = 'optional' === $match[1];
            }

            if (isset($match[6])) {
                if (' = NULL' === $match[6]) {
                    $defaultValue = ' = null';
                } else {
                    $defaultValue = ' = ' .
                        var_export($parameter->getDefaultValue(), true);
                }
            } elseif ($optional || $match[3]) {
                $defaultValue = ' = null';
            } else {
                $defaultValue = '';
            }

            $signature[$match[5]] =
                [$typehint, $byReference, $variadic, $defaultValue];
        }

        return $signature;
    }

    private $isIterableTypeHintSupported;
    private $isObjectTypeHintSupported;
}