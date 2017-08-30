<?php

declare(strict_types=1);

namespace Eloquent\Phony\Matcher;

use Eloquent\Phony\Exporter\Exporter;

/**
 * Used for implementing wrapped matchers.
 */
trait WrappedMatcherTrait
{
    /**
     * Get the wrapped matcher.
     *
     * @return object The matcher.
     */
    public function matcher()
    {
        return $this->matcher;
    }

    /**
     * Returns `true` if `$value` matches this matcher's criteria.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value matches.
     */
    public function matches($value): bool
    {
        return (bool) $this->matcher->matches($value);
    }

    /**
     * Describe this matcher.
     *
     * @param Exporter|null $exporter The exporter to use.
     *
     * @return string The description.
     */
    public function describe(Exporter $exporter = null): string
    {
        return '<' . strval($this->matcher) . '>';
    }

    /**
     * Describe this matcher.
     *
     * @return string The description.
     */
    public function __toString(): string
    {
        return '<' . strval($this->matcher) . '>';
    }

    private $matcher;
}