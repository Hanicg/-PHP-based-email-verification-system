<?php
require_once 'functions.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['unsubscribe_email'] ?? '';
    $code = $_POST['verification_code'] ?? '';

    $file = __DIR__ . '/verification_codes_10590c88a74a189fcaba539c8cc0a694.txt';
    $codes = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    if ($email && !$code) {
        // Send verification code for unsubscription
        $verificationCode = generateVerificationCode();
        $codes[$email] = $verificationCode;
        file_put_contents($file, json_encode($codes));

        if (sendVerificationEmail($email, $verificationCode)) {
            $message = "✅ Confirmation code sent to $email.";
        } else {
            $message = "❌ Failed to send confirmation code.";
        }
    } elseif ($email && $code) {
        // Verify and unsubscribe
        if (verifyCode($email, $code)) {
            unsubscribeEmail($email);
            unset($codes[$email]);
            file_put_contents($file, json_encode($codes));
            $message = "✅ $email has been unsubscribed successfully.";
        } else {
            $message = "❌ Invalid confirmation code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
</head>
<body>
    <h2>XKCD Comic Email Unsubscription</h2>
    <p><?= $message ?></p>

    <!-- Unsubscribe form -->
    <form method="post">
        <label>Email:</label><br>
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <br>

    <form method="post">
        <label>Verification Code:</label><br>
        <input type="text" name="verification_code" maxlength="6" required>
        <input type="hidden" name="unsubscribe_email" value="<?= htmlspecialchars($_POST['unsubscribe_email'] ?? '') ?>">
        <button id="submit-verification">Verify</button>
    </form>
</body>
</html>
