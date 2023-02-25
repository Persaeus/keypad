@props([
    'password'     => 'password',
    'confirmation' => 'password_confirmation',
])
<x-cipher::component
    name="register"
    :$password
    :$confirmation
/>