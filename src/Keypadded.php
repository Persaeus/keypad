<?php

namespace Nihilsen\Keypad;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Nihilsen\Keypad\Contracts\Registers;

/**
 * @property-read \Nihilsen\Keypad\Keypad $keypad
 * @property-read \Illuminate\Database\Eloquent\Collection<\Nihilsen\Keypad\Keypad> $keypads
 */
trait Keypadded
{
    public static function bootKeypadded()
    {
        static::bootKeypaddedEvents();
    }

    private static function bootKeypaddedEvents()
    {
        foreach ([
            Registered::class => function (Registered $event) {
                $user = $event->user;

                if (
                    !$user instanceof static ||
                    !$user->keypad instanceof Registers
                ) {
                    return;
                }

                $user->keypad->register($event);
            },
        ] as $event => $listener) {
            Event::listen($event, $listener);
        }
    }

    public function keypad(): Relation
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this
            ->morphOne(Keypad::class, 'keypadded')
            ->latestOfMany()
            ->withDefault(fn ($_, self $keypadded) => $keypadded->getDefaultKeypad());
    }

    public function keypads(): Relation
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this->morphMany(Keypad::class, 'keypadded');
    }

    protected function getDefaultKeypad()
    {
        $keypad = new Keypad;

        return $keypad->keypadded()->associate($this);
    }
}
