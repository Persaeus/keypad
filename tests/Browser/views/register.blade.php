<!DOCTYPE html>
<html>
    <body>
        <form action="/register" method="POST">
            @csrf

            <x-keypad::register :confirmation="false" />

            <input type="text" name="name" value="Placeholder Name">

            <input type="text" name="email" dusk="email-field">

            <input type="password" name="password" dusk="password-field" />

            <button type="submit" dusk="submit-button">Register</button>
        </form>
    </body>
</html>