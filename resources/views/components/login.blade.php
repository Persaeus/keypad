@props([
    'password' => 'password'
])
<x-keypad::component
    name="login"
    :$password
/>
<x-keypad::hash :target="$password" />