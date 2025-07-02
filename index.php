<?php
session_start();
// Reset all game session variables
unset($_SESSION['game_started']);
unset($_SESSION['current_question']);
unset($_SESSION['total_winnings']);
// Also clear any old session variables that might exist
unset($_SESSION['score']);
unset($_SESSION['q']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="homepage">
    <div class="container">
        <img src="images/logo.png" alt="Millionaire Logo" style="width:200px; margin-bottom: 20px;">
        <h1 class="glow">Who Wants to Be a Millionaire</h1>
        <p class="intro-text">Think you're smart? Try answering all the questions to win $1,000,000.</p>
        <a href="question.php" class="start-btn">Start Game</a>
        <a href="howtoplay.php" class="start-btn" style="background:#4444ff; color:#fff; margin-top:15px;">How to Play</a>
    </div>
</body>
</html>
