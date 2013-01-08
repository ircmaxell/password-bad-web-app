<?php

function createPassword($password) {
    $salt = md5(uniqid(rand(), true));
    $raw = crypt($password, '$2y$10$' . $salt); 
    if (strlen($raw) <= 13) {
        throw new Exception('Crypt Failure!');
    }
    return $raw;
}

function verifyPassword($hash, $password) {
    return crypt($password, $hash) == $hash;
}

