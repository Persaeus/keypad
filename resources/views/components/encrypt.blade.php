@props([
    'target',
    'keypad' => $keypad,
])
<x-keypad::component
    name="encrypt"
    :key="$keypad->data['p']"
    :$target
/>