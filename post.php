<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $description = $_POST['description'];
    $image_path = 'uploads/' . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, image_path, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $image_path, $description);

        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading image!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodgram - Dodaj post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Dodaj nowy post</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit">Add Post</button>
    </form>
    <a href="posts.php">Powrót do postów</a>
</div>

</body>
</html>