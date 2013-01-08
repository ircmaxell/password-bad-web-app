<?php

function isUserNameAvailable($username) {
    global $users;
    foreach ($users as $user) {
        if ($user['name'] == $username) {
            return false;
        }
    }
    return true;
}

function writeUser(array $user) {
    global $users;
    $user['id'] = count($users) + 1;
    $users[] = $user;
    $code = '<?php return ' . var_export($users, true) . ';';
    file_put_contents(__DIR__ . '/../data/users.php', $code);
}