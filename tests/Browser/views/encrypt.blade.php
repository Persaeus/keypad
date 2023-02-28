<!DOCTYPE html>
<html>
    <body>
        @auth
            <p>logged in</p>
            <form action="/decrypt" method="GET">
                <x-keypad::encrypt target="message" />

                <input type="type" name="message" dusk="plaintext-field">

                <button type="submit" dusk="submit-button">Encrypt</button>
            </form>
        @endauth
    </body>
</html>