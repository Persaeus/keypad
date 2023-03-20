@props([
    'name',
    'tag' => 'fieldset',
    'hidden' => true,
])
<x-keypad::script />
<x-keypad::element
    tag='keypad-element'
    :$hidden
    :data-keypad-component="$name"
    :data-keypad-attributes="json_encode([...$attributes])"
>{{ $slot }}</x-keypad::element>