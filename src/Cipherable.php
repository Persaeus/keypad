<?php

namespace Nihilsen\Cipher;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Nihilsen\Cipher\Contracts\Registers;

/**
 * @property-read \Nihilsen\Cipher\Cipher $cipher
 * @property-read \Illuminate\Database\Eloquent\Collection<\Nihilsen\Cipher\Cipher> $ciphers
 */
trait Cipherable
{
    public static function bootCipherable()
    {
        static::bootCipherableEvents();
    }

    private static function bootCipherableEvents()
    {
        foreach ([
            Registered::class => function (Registered $event) {
                $user = $event->user;

                if (
                    ! $user instanceof static ||
                    ! $user->cipher instanceof Registers
                ) {
                    return;
                }

                $user->cipher->register($event);
            },
        ] as $event => $listener) {
            Event::listen($event, $listener);
        }
    }

    public function cipher(): Relation
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this
            ->morphOne(Cipher::class, 'cipherable')
            ->latestOfMany()
            ->withDefault(fn ($_, self $cipherable) => $cipherable->getDefaultCipher());
    }

    public function ciphers(): Relation
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this->morphMany(Cipher::class, 'cipherable');
    }

    protected function getDefaultCipher()
    {
        $cipher = new Cipher;

        return $cipher->cipherable()->associate($this);
    }
}
