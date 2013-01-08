<?php

function createPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($hash, $password) {
    return password_verify($password, $hash);
}

