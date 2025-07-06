<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "From: no-reply@example.com\r\nContent-Type: text/html";
    return mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
        return true;
    }
    return false;
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    $updated = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
    return true;
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
    $randomId = random_int(1, 3000);
    $url = "https://xkcd.com/{$randomId}/info.0.json";
    $data = @file_get_contents($url);

    if (!$data) return "";

    $comic = json_decode($data, true);
    $title = htmlspecialchars($comic['title'] ?? '');
    $img = htmlspecialchars($comic['img'] ?? '');
    $alt = htmlspecialchars($comic['alt'] ?? '');

    $html = "<h2>XKCD Comic: {$title}</h2>
             <img src='{$img}' alt='XKCD Comic'>
             <p>{$alt}</p>
             <p><a href='/unsubscribe.php'>Unsubscribe</a></p>";

    return $html;
}


/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    $logFile = __DIR__ . '/comics.json';

    if (!file_exists($file)) {
        file_put_contents($logFile, "🛑 [CRON] No registered_emails.txt found.\n", FILE_APPEND);
        return;
    }

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($emails)) {
        file_put_contents($logFile, "📭 [CRON] No emails found.\n", FILE_APPEND);
        return;
    }

    $content = fetchAndFormatXKCDData();
    if (empty($content)) {
        file_put_contents($logFile, "⚠️ [CRON] XKCD comic fetch failed.\n", FILE_APPEND);
        return;
    }

    foreach ($emails as $email) {
        $subject = "Daily XKCD Comic";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";

        $result = mail($email, $subject, $content, $headers);
        if ($result) {
            file_put_contents($logFile, "✅ [CRON] Comic sent to $email\n", FILE_APPEND);
        } else {
            file_put_contents($logFile, "❌ [CRON] Failed to send to $email\n", FILE_APPEND);
        }
    }
}



/**
 * Verify code from the stored verification_codes.json
 */
function verifyCode($email, $code): bool {
    $file = __DIR__ . '/verification_codes_10590c88a74a189fcaba539c8cc0a694.txt';
    if (!file_exists($file)) return false;

    $codes = json_decode(file_get_contents($file), true);
    return isset($codes[$email]) && $codes[$email] === $code;
}
function sendWelcomeXKCDComic(string $email) {
    $logFile = __DIR__ . '/mail_log.txt';
    $content = fetchAndFormatXKCDData();

    if (empty($content)) {
        file_put_contents($logFile, "⚠️ [Welcome] XKCD comic fetch failed for $email.\n", FILE_APPEND);
        return;
    }

    $content .= "<p><em>Note: Future comics will be sent every 24 hours.</em></p>";
    $subject = "Your First XKCD Comic!";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";

    $result = mail($email, $subject, $content, $headers);
    if ($result) {
        file_put_contents($logFile, "✅ [Welcome] First comic sent to $email\n", FILE_APPEND);
    } else {
        file_put_contents($logFile, "❌ [Welcome] Failed to send to $email\n", FILE_APPEND);
    }
}

function sendWelcomeXKCDComicToAll() {
    $file = __DIR__ . '/registered_emails.txt';
    $logFile = __DIR__ . '/mail_log.txt';

    if (!file_exists($file)) {
        file_put_contents($logFile, "🛑 [Welcome] No registered_emails.txt found.\n", FILE_APPEND);
        return;
    }

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($emails)) {
        file_put_contents($logFile, "📭 [Welcome] No emails found.\n", FILE_APPEND);
        return;
    }

    foreach ($emails as $email) {
        sendWelcomeXKCDComic($email);
    }
}
