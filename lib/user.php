<?php

function login($username, $password) {
    $user = findUserByName($username);
    if (!$user) {
        return false;
    } elseif (!verifyPassword($user['password'], $password)) {
        return false;
    }
    return true;
}

function findUserByName($username) {
    global $users;
    foreach ($users as $user) {
        if ($user['name'] == $username) {
            return $user;
        }
    }
    return false;
}

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
    if (!isset($user['id'])) {
        $user['id'] = count($users) + 1;
    }
    $users[$user['id'] - 1] = $user;
}

function saveUsers() {
    global $users;
    $code = '<?php return ' . var_export($users, true) . ';';
    file_put_contents(__DIR__ . '/../data/users.php', $code);
}