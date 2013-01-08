<?php

function createPassword($password) {
    $salt = md5(uniqid(rand(), true));
    return $salt . ':' . md5($salt . $password);
}

function verifyPassword($hash, $password) {
    list ($salt, $hash) = explode(':', $hash, 2);
    return md5($salt . $password) == $hash;
}