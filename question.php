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

// Initialize 50/50 hint tracking if not set
if (!isset($_SESSION['hint_used'])) {
    $_SESSION['hint_used'] = false;
}

// Handle 50/50 hint request
if (isset($_POST['use_hint']) && !$_SESSION['hint_used']) {
    $_SESSION['hint_used'] = true;
    
    $current_question = $questions[$_SESSION['current_question']];
    $correct_answer = $current_question['answer'];
    
    // Get all incorrect answer indices
    $incorrect_indices = [];
    for ($i = 0; $i < count($current_question['options']); $i++) {
        if ($i != $correct_answer) {
            $incorrect_indices[] = $i;
        }
    }
    
    // Randomly select one incorrect answer to keep visible
    $keep_incorrect = $incorrect_indices[array_rand($incorrect_indices)];
    
    // Store which answers to hide
    $hide_indices = [];
    foreach ($incorrect_indices as $index) {
        if ($index != $keep_incorrect) {
            $hide_indices[] = $index;
        }
    }
    
    $_SESSION['hidden_answers'] = $hide_indices;
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

        <!-- 50/50 Hint Button -->
        <?php if (!$_SESSION['hint_used']): ?>
            <div class="hint-container">
                <form method="post" style="display: inline;">
                    <button type="submit" name="use_hint" class="hint-btn">
                        50:50 Hint
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="hint-container">
                <span class="hint-used">50:50 Hint Used</span>
            </div>
        <?php endif; ?>

        <form method="post" action="result.php" class="answer-grid">
            <?php 
            $hidden_answers = isset($_SESSION['hidden_answers']) ? $_SESSION['hidden_answers'] : [];
            foreach ($current_question['options'] as $index => $option): 
                $is_hidden = in_array($index, $hidden_answers);
            ?>
                <button type="submit" name="answer" value="<?php echo $index; ?>" 
                        class="answer-btn <?php echo $is_hidden ? 'hidden-answer' : ''; ?>"
                        <?php echo $is_hidden ? 'disabled' : ''; ?>>
                    <span class="label">
                        <?php echo chr(65 + $index); ?>:
                    </span> 
                    <?php echo $is_hidden ? '[Hidden]' : $option; ?>
                </button>
            <?php endforeach; ?>
        </form>
    </div>
</body>
</html>
