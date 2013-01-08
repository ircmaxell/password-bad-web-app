<?php
require_once 'vendor/autoload.php';

require_once 'lib/user.php';
require_once 'lib/password.php';

$default = require 'data/default_users.php';
$users = array();

foreach ($default as $key => $user) {
    $user['password'] = createPassword($user['password']);
    writeUser($user);
}
saveUsers();