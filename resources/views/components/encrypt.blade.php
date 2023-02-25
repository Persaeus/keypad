@props([
    'target',
    'cipher' => $cipher,
])
<x-cipher::component
    name="encrypt"
    :key="$cipher->data['p']"
    :$target
/>