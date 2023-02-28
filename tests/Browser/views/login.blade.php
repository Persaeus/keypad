<!DOCTYPE html>
<html>
    <body>
        <form action="/login" method="POST">
            @csrf

            <x-keypad::login />

            <input type="type" name="email" dusk="email-field">

            <input type="password" name="password" dusk="password-field" />

            <button type="submit" dusk="submit-button">Log in</button>
        </form>
    </body>
</html>