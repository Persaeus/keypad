<html>
    <body>
        <form action="/login" method="POST">
            <input type="email" name="email" />
            
            <input type="password" name="my_password" />

            <x-cipher::login password="my_password" />
        </form>
    </body>
</html>