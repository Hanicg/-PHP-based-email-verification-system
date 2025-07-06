<?php
file_put_contents(__DIR__ . '/cron_debug.txt', "cron.php started at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

require_once 'functions.php';

// Send XKCD comic to all verified users
sendXKCDUpdatesToSubscribers();
