<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>login</h1>
    <form action="./backend/login.php" method="post">
        <input type="email" name="email" placeholder="masukkan email anda">
        <input type="password" name="password" placeholder="masukkan password anda">
        <input type="submit" value="login" name="submit">

        <div class="buat-akun">
            <label>belum memiliki akun? </label>
            <a href="register.php">register</a>
        </div>
    </form>

</body>

</html>