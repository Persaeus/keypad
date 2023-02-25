<!DOCTYPE html>
<html>
    <body>
        @auth
            <p>logged in</p>
            <form action="/login" method="POST">
                <x-cipher::login />

                <input type="type" name="email" dusk="email-field">

                <input type="password" name="password" dusk="password-field" />

                <button type="submit" dusk="submit-button">Log in</button>
            </form>
        @endauth
    </body>
</html>