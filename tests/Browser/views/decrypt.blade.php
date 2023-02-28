<!DOCTYPE html>
<html>
    <body>
        @auth
            <x-keypad::decrypt>{{ $_GET['message'] }}</x-keypad::decrypt>
        @endauth
    </body>
</html>