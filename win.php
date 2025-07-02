<?php
session_start();
$total_winnings = isset($_SESSION['total_winnings']) ? $_SESSION['total_winnings'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>You Win!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="gameover">
    <div class="center">
        <h1>Congratulations!</h1>
        <p>You answered all questions correctly and won $<?php echo number_format($total_winnings); ?>!</p>
        <p>You are now a millionaire!</p>
        <a href="index.php" class="start-btn">Play Again</a>
    </div>
</body>
</html>
