<?php

function createPassword($password) {
    $salt = md5(uniqid(rand(), true));
    return $salt . ':' . derive($password, $salt);
}

function verifyPassword($hash, $password) {
    list ($salt, $hash) = explode(':', $hash, 2);
    return derive($password, $salt) == $hash;
}

function derive($password, $salt, $iterations = 1000) {
    $hash = hash_hmac('md5', $salt, $password);
    for ($i = 1; $i < $iterations; $i++) {
        $hash = hash_hmac('md5', $hash, $password);
    }
    return $hash;
}