<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include 'questions.php';

// Initialize game state ONLY when starting completely fresh (no session data exists at all)
if (!isset($_SESSION['game_started']) && !isset($_SESSION['current_question']) && !isset($_SESSION['total_winnings'])) {
    $_SESSION['game_started'] = true;
    $_SESSION['current_question'] = 0;
    $_SESSION['total_winnings'] = 0;
} else {
    // Ensure all required session variables exist
    if (!isset($_SESSION['current_question'])) $_SESSION['current_question'] = 0;
    if (!isset($_SESSION['total_winnings'])) $_SESSION['total_winnings'] = 0;
    if (!isset($_SESSION['game_started'])) $_SESSION['game_started'] = true;
}

// Check if all questions have been answered
if ($_SESSION['current_question'] >= count($questions)) {
    header("Location: win.php");
    exit();
}

$current_question = $questions[$_SESSION['current_question']];
$question_number = $_SESSION['current_question'] + 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Question <?php echo $question_number; ?> - Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="game-body">
    <div class="game-container">
        <div class="money-circle">
            $<?php echo number_format($_SESSION['total_winnings']); ?>
        </div>

        <div class="question-info">
            <h2>Question <?php echo $question_number; ?> of <?php echo count($questions); ?></h2>
            <p>For $<?php echo number_format($current_question['prize']); ?></p>
        </div>

        <div class="question-text">
            <?php echo $current_question['question']; ?>
        </div>

        <form method="post" action="result.php" class="answer-grid">
            <?php foreach ($current_question['options'] as $index => $option): ?>
                <button type="submit" name="answer" value="<?php echo $index; ?>" class="answer-btn">
                    <span class="label">
                        <?php echo chr(65 + $index); ?>:
                    </span> <?php echo $option; ?>
                </button>
            <?php endforeach; ?>
        </form>
    </div>
</body>
</html>
