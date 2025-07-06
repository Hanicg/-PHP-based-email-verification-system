<?php
require_once 'functions.php';

$message = "";
$emailValue = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle subscription verification
    if (isset($_POST['email']) && !isset($_POST['verification_code'])) {
        $email = $_POST['email'];
        $emailValue = $email;
        $verificationCode = generateVerificationCode();
        file_put_contents(__DIR__ . "/verification_codes_" . md5($email) . ".txt", $verificationCode);

        if (sendVerificationEmail($email, $verificationCode)) {
            $message = "✅ Verification code sent to <strong>$email</strong>.";
        } else {
            $message = "❌ Failed to send verification code.";
        }

    } elseif (isset($_POST['email'], $_POST['verification_code'])) {
        $email = $_POST['email'];
        $code = $_POST['verification_code'];
        $emailValue = $email;
        $path = __DIR__ . "/verification_codes_" . md5($email) . ".txt";

        if (file_exists($path) && trim(file_get_contents($path)) === $code) {
            registerEmail($email);
            unlink($path);
            sendWelcomeXKCDComicToAll(); // ✅ Send comic to all users
            $message = "✅ Email verified and registered!";
        } else {
            $message = "❌ Invalid verification code.";
        }
    }

    // Handle unsubscribe request
    if (isset($_POST['unsubscribe_email']) && !isset($_POST['verification_code'])) {
        $unsubscribeEmail = $_POST['unsubscribe_email'];
        $verificationCode = generateVerificationCode();
        file_put_contents(__DIR__ . "/unsubscribe_codes_" . md5($unsubscribeEmail) . ".txt", $verificationCode);

        if (sendVerificationEmail($unsubscribeEmail, $verificationCode, true)) {
            $message = "✅ Unsubscribe code sent to <strong>$unsubscribeEmail</strong>.";
        } else {
            $message = "";
        }

    } elseif (isset($_POST['unsubscribe_email'], $_POST['verification_code'])) {
        $unsubscribeEmail = $_POST['unsubscribe_email'];
        $code = $_POST['verification_code'];
        $path = __DIR__ . "/unsubscribe_codes_" . md5($unsubscribeEmail) . ".txt";

        if (file_exists($path) && trim(file_get_contents($path)) === $code) {
            unsubscribeEmail($unsubscribeEmail);
            unlink($path);
            $message = "✅ Email successfully unsubscribed.";
        } else {
            $message = "❌ Invalid unsubscribe code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XKCD Email Subscription</title>
</head>
<body>
    <h1>XKCD Email Subscription</h1>

    <p><?= $message ?></p>

    <h2>📩 Subscribe</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button id="submit-email">Submit</button>
    </form>

    <h2>🔢 Verify Code</h2>
    <form method="post">
        <input type="text" name="verification_code" maxlength="6" placeholder="Enter verification code" required>
        <input type="hidden" name="email" value="<?= htmlspecialchars($emailValue) ?>">
        <button id="submit-verification">Verify</button>
    </form>

    <h2>📤 Unsubscribe</h2>
    <form method="post">
        <input type="email" name="unsubscribe_email" placeholder="Enter email to unsubscribe" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <h2>🔢 Unsubscribe Verification</h2>
    <form method="post">
        <input type="text" name="verification_code" maxlength="6" placeholder="Enter verification code" required>
        <input type="email" name="unsubscribe_email" placeholder="Confirm email" required>
        <button id="submit-verification">Verify</button>
    </form>
</body>
</html>
