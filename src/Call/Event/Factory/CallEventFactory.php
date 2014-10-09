<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Call\Event\Factory;

use Eloquent\Phony\Call\Event\CalledEvent;
use Eloquent\Phony\Call\Event\CalledEventInterface;
use Eloquent\Phony\Call\Event\ResponseEventInterface;
use Eloquent\Phony\Call\Event\ReturnedEvent;
use Eloquent\Phony\Call\Event\ReturnedEventInterface;
use Eloquent\Phony\Call\Event\SentEvent;
use Eloquent\Phony\Call\Event\SentEventInterface;
use Eloquent\Phony\Call\Event\SentExceptionEvent;
use Eloquent\Phony\Call\Event\SentExceptionEventInterface;
use Eloquent\Phony\Call\Event\ThrewEvent;
use Eloquent\Phony\Call\Event\ThrewEventInterface;
use Eloquent\Phony\Call\Event\YieldedEvent;
use Eloquent\Phony\Call\Event\YieldedEventInterface;
use Eloquent\Phony\Clock\ClockInterface;
use Eloquent\Phony\Clock\SystemClock;
use Eloquent\Phony\Sequencer\Sequencer;
use Eloquent\Phony\Sequencer\SequencerInterface;
use Exception;
use Generator;

/**
 * Creates call events.
 *
 * @internal
 */
class CallEventFactory implements CallEventFactoryInterface
{
    /**
     * Get the static instance of this factory.
     *
     * @return CallEventFactoryInterface The static factory.
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Construct a new call event factory.
     *
     * @param SequencerInterface|null $sequencer The sequencer to use.
     * @param ClockInterface|null     $clock     The clock to use.
     */
    public function __construct(
        SequencerInterface $sequencer = null,
        ClockInterface $clock = null
    ) {
        if (null === $sequencer) {
            $sequencer = Sequencer::instance();
        }
        if (null === $clock) {
            $clock = SystemClock::instance();
        }

        $this->sequencer = $sequencer;
        $this->clock = $clock;
    }

    /**
     * Get the sequencer.
     *
     * @return SequencerInterface The sequencer.
     */
    public function sequencer()
    {
        return $this->sequencer;
    }

    /**
     * Get the clock.
     *
     * @return ClockInterface The clock.
     */
    public function clock()
    {
        return $this->clock;
    }

    /**
     * Create a new 'called' event.
     *
     * @param callable|null             $callback  The callback.
     * @param array<integer,mixed>|null $arguments The arguments.
     *
     * @return CalledEventInterface The newly created event.
     */
    public function createCalled($callback = null, array $arguments = null)
    {
        return new CalledEvent(
            $this->sequencer->next(),
            $this->clock->time(),
            $callback,
            $arguments
        );
    }

    /**
     * Create a new response event.
     *
     * @param mixed          $returnValue The return value.
     * @param Exception|null $exception   The thrown exception, or null if no exception was thrown.
     *
     * @return ResponseEventInterface The newly created event.
     */
    public function createResponse(
        $returnValue = null,
        Exception $exception = null
    ) {
        if ($exception) {
            return $this->createThrew($exception);
        }

        return $this->createReturned($returnValue);
    }

    /**
     * Create a new 'returned' event.
     *
     * @param mixed $value The return value.
     *
     * @return ReturnedEventInterface The newly created event.
     */
    public function createReturned($value = null)
    {
        return new ReturnedEvent(
            $this->sequencer->next(),
            $this->clock->time(),
            $value
        );
    }

    /**
     * Create a new 'returned' event for a generator.
     *
     * @param Generator|null $generator The generator.
     *
     * @return ReturnedEventInterface The newly created event.
     */
    public function createGenerated(Generator $generator = null)
    {
        if (null === $generator) {
            $generator = CallEventFactoryDetail::createEmptyGenerator();
        }

        return $this->createReturned($generator);
    }

    /**
     * Create a new 'thrown' event.
     *
     * @param Exception|null $exception The thrown exception.
     *
     * @return ThrewEventInterface The newly created event.
     */
    public function createThrew(Exception $exception = null)
    {
        return new ThrewEvent(
            $this->sequencer->next(),
            $this->clock->time(),
            $exception
        );
    }

    /**
     * Create a new 'yielded' event.
     *
     * If called with one argument, the argument is treated as the value.
     *
     * If called with two arguments, the first is treated as the key, and the
     * second as the value.
     *
     * @param mixed $keyOrValue The yielded key or value.
     * @param mixed $value      The yielded value.
     *
     * @return YieldedEventInterface The newly created event.
     */
    public function createYielded($keyOrValue = null, $value = null)
    {
        if (func_num_args() > 1) {
            $key = $keyOrValue;
        } else {
            $key = null;
            $value = $keyOrValue;
        }

        return new YieldedEvent(
            $this->sequencer->next(),
            $this->clock->time(),
            $key,
            $value
        );
    }

    /**
     * Create a new 'sent' event.
     *
     * @param mixed $value The sent value.
     *
     * @return SentEventInterface The newly created event.
     */
    public function createSent($value = null)
    {
        return new SentEvent(
            $this->sequencer->next(),
            $this->clock->time(),
            $value
        );
    }

    /**
     * Create a new 'sent exception' event.
     *
     * @param Exception|null $exception The sent exception.
     *
     * @return SentExceptionEventInterface The newly created event.
     */
    public function createSentException(Exception $exception = null)
    {
        return new SentExceptionEvent(
            $this->sequencer->next(),
            $this->clock->time(),
            $exception
        );
    }

    private static $instance;
    private $sequencer;
    private $clock;
    private $invoker;
}