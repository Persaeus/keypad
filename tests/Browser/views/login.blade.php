<!DOCTYPE html>
<html>
    <body>
        <form action="/login" method="POST">
            <x-cipher::login />

            <input type="password" name="password" dusk="password-field" />

            <button type="submit">Log in</button>
        </form>
    </body>
</html>