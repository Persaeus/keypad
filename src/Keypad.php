<?php

namespace Nihilsen\Keypad;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Nihilsen\Keypad\Contracts\ChangesPassword;
use Nihilsen\Keypad\Contracts\Registers;

/**
 * @property-read \Illuminate\Database\Eloquent\Model&\Nihilsen\Keypad\Keypadded $keypadded
 * @property \ArrayObject{k:string,p:string,s:string} $data
 */
class Keypad extends Model implements ChangesPassword, Registers
{
    const VERSION = '0.1.0';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    public function changePassword()
    {
        $this->updateFromRequestData();
    }

    public function keypadded(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * {@inheritDoc}
     */
    public function register(Registered $event)
    {
        $this->updateFromRequestData();
    }

    public function resolve()
    {
        return app(static::class);
    }

    final public function salt(): string
    {
        return config('keypad.salt');
    }

    /**
     * Parse keypad data from request and set data correspondingly.
     */
    protected function updateFromRequestData()
    {
        $json = request('_keypad');

        /** @var object{k:string,p:string,s:string} */
        $data = json_decode($json, flags: JSON_THROW_ON_ERROR);

        $this->data = $data;

        $this->save();
    }
}
