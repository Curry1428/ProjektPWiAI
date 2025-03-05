<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodgram</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Witamy w Foodgram!</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Zalogowano jako <b><?php echo $_SESSION['username']; ?></b></p>
        <a href="posts.php">Przejdź do postów</a>
        <a href="logout.php" style="background: red;">Wyloguj</a>
    <?php else: ?>
        <a href="login.php">Zaloguj się</a>
        <a href="register.php">Zarejestruj się</a>
    <?php endif; ?>
</div>

</body>
</html>