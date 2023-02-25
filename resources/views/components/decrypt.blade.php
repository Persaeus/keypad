@props(['tag' => 'span'])
@auth
    <x-cipher::component
        name="decrypt"
        :$tag
    >{{ $slot }}</x-cipher::component>
@endauth