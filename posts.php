<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obsługa dodawania komentarza
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $comment);

    if ($stmt->execute()) {
        header("Location: posts.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obsługa dodawania reakcji
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reaction'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $reaction_type = $_POST['reaction'];

    $stmt = $conn->prepare("INSERT INTO reactions (user_id, post_id, reaction_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $post_id, $reaction_type);

    if ($stmt->execute()) {
        header("Location: posts.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$result = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodgram - Posty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Posty użytkowników</h1>
    <a href="post.php">Dodaj nowy post</a>
    <a href="logout.php" style="background: red;">Wyloguj</a>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post">
            <h2><?php echo $row['username']; ?></h2>
            <img src="<?php echo $row['image_path']; ?>" alt="Zdjęcie jedzenia">
            <p><?php echo $row['description']; ?></p>

            <!-- Formularz reakcji -->
            <form method="POST" action="" style="display: inline;">
                <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="reaction" value="like">👍</button>
                <button type="submit" name="reaction" value="dislike">👎</button>
            </form>

            <!-- Formularz komentarza -->
            <form method="POST" action="">
                <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                <textarea name="comment" placeholder="Dodaj komentarz" required></textarea>
                <button type="submit">Dodaj komentarz</button>
            </form>

            <!-- Wyświetlanie komentarzy -->
            <div class="comments">
                <h3>Komentarze:</h3>
                <?php
                $post_id = $row['id'];
                $comments_result = $conn->query("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = $post_id ORDER BY created_at DESC");
                while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment">
                        <p><b><?php echo $comment['username']; ?>:</b> <?php echo $comment['comment']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>