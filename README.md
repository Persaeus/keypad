# Keypad 🎹 for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nihilsen/keypad.svg?style=flat-square)](https://packagist.org/packages/nihilsen/keypad)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nihilsen/keypad/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nihilsen/keypad/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nihilsen/keypad/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nihilsen/keypad/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nihilsen/keypad.svg?style=flat-square)](https://packagist.org/packages/nihilsen/keypad)

_Keypad_ is a Laravel package that aims to make it easier to add client-side encryption and decryption to your Laravel applications.

Keypad offers a suite of reactive Blade components that can be inserted into your existing authentication forms.

Under the hood, Keypad uses browser-native [_Web Crypto API_](https://developer.mozilla.org/en-US/docs/Web/API/Web_Crypto_API).

## Installation

You can install the package via composer:

```bash
composer require nihilsen/keypad
```

You can run the migrations with:

```bash
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="keypad-config"
```

This is the contents of the published config file:

https://github.com/nihilsen/keypad/blob/14e862c555354edbf36fa851c09a167114930a98/config/keypad.php#L3-L23

## Components and usage

### Login: `<x-keypad::login>`

```blade

<form method="POST" action="/login/">
    {{ -- CSRF token -- }}
    @csrf
    
    {{ -- email field -- }}
    <input type="email" name="email_field" placeholder="email" />
    
    {{ -- password field -- }}
    <input type="password" name="password_field" placeholder="password" />
    
    {{ -- Keypad "login" component -- }}
    <x-keypad::login password="password_field" />
    
    {{ -- other form elements, submit button, etc ... -- }}
</form>

```

### Register: `<x-keypad::register>`

```blade

<form method="POST" action="/register/">
    {{ -- CSRF token -- }}
    @csrf
    
    {{ -- email field -- }}
    <input type="email" name="email_field" placeholder="email" />
    
    {{ -- password field -- }}
    <input type="password" name="password_field" placeholder="password" />
    
    {{ -- confirm password field -- }}
    <input type="password" name="confirm_password_field" placeholder="confirm password" />
    
    {{ -- Keypad "register" component -- }}
    <x-keypad::register password="password_field" confirmation="confirm_password_field" />
    
    {{ -- other form elements, submit button, etc ... -- }}
</form>

```

### Encrypt: `<x-keypad::encrypt>`

```blade

<form method="POST" action="/send_message/">
    {{ -- CSRF token -- }}
    @csrf
    
    {{ -- plaintext message field -- }}
    <input type="text" name="secret_message" placeholder="Enter your message" />
    
    {{ -- get recipient keypad -- }}
    @php
        $recipient = User::find($__GET['recipient_id']);
        $keypad = $recipient->keypad;
    @endphp
    
    {{ -- Keypad "encrypt" component -- }}
    <x-keypad::encrypt target="secret_message" :$keypad />
    
    {{ -- other form elements, submit button, etc ... -- }}
</form>

```

### Decrypt: `<x-keypad::decrypt>`

```blade

<x-keypad::decrypt>{{ $message->secret_message }}</x-keypad::decrypt>

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Roadmap

- [x] `<x-keypad::login />`
- [x] `<x-keypad::register />`
- [x] `<x-keypad::encrypt />`
- [x] `<x-keypad::decrypt />`
- [x] `<x-keypad::hash />`
- [x] `<x-keypad::change-password />`
- [ ] `<x-keypad::recover />`

## Contributing

- Pull requests, bug reports and feature requests are welcome.

## Credits

-   [Nihilsen](https://github.com/nihilsen)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
