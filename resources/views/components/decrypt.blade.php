@props(['tag' => 'span'])
@auth
    <x-keypad::component
        name="decrypt"
        :$tag
    >{{ $slot }}</x-keypad::component>
@endauth