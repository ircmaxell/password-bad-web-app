<?php

function createPassword($password) {
    $salt = md5(uniqid(rand(), true));
    return $salt . ':' . hash_hmac('md5', $password, $salt);
}

function verifyPassword($hash, $password) {
    list ($salt, $hash) = explode(':', $hash, 2);
    return hash_hmac('md5', $password, $salt) == $hash;
}