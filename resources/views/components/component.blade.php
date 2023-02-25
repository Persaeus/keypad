@props([
    'name',
    'tag' => 'fieldset',
    'hidden' => true,
])
<x-cipher::script />
<x-cipher::element
    :$tag
    :$hidden
    :data-cipher-component="$name"
    :data-cipher-attributes="json_encode([...$attributes])"
>{{ $slot }}</x-cipher::element>