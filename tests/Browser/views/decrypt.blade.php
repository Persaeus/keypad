<!DOCTYPE html>
<html>
    <body>
        @auth
            <x-cipher::decrypt>{{ $_GET['message'] }}</x-cipher::decrypt>
        @endauth
    </body>
</html>