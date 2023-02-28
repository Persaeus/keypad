@props([
    'password'     => 'password',
    'confirmation' => 'password_confirmation',
])
<x-keypad::component
    name="register"
    :$password
    :$confirmation
/>