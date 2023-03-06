@props([
    'password'     => 'password',
    'confirmation' => 'password_confirmation',
])
<x-keypad::component
    name="register"
    :$password
/>
<x-keypad::hash :target="$password" />

@if ($confirmation)
    <x-keypad::hash :target="$confirmation" />
@endif