<?php

namespace Nihilsen\Cipher;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Nihilsen\Cipher\Contracts\Registers;

/**
 * @property-read \Illuminate\Database\Eloquent\Model&\Nihilsen\Cipher\Cipherable $cipherable
 * @property \ArrayObject{k:string,p:string,s:string} $data
 */
class Cipher extends Model implements Registers
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

    public function cipherable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * {@inheritDoc}
     */
    public function register(Registered $event)
    {
        $json = request('_cipher');

        /** @var object{k:string,p:string,s:string,} */
        $cipher = json_decode($json, flags: JSON_THROW_ON_ERROR);

        $this->data = $cipher;

        $this->save();
    }

    public function resolve()
    {
        return app(static::class);
    }

    final public function salt(): string
    {
        return config('cipher.salt');
    }
}
