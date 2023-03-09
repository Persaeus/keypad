<!DOCTYPE html>
<html>
    <body>
        @auth
            <form action="/change-password" method="POST">
                @csrf

                <x-keypad::change-password />

                <input type="password" name="password" dusk="password-field" />
                
                <input type="password" name="password_confirmation" dusk="confirm-password-field" />

                <button type="submit" dusk="submit-button">Log in</button>
            </form>
        @endauth
    </body>
</html>