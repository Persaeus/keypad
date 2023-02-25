@props(['tag' => 'span'])
@auth
    <x-cipher::component
        name="decrypt"
        :$tag
        :$cipher
    >{{ $slot }}</x-cipher::component>
@endauth