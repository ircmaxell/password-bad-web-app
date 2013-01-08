<?php

function createPassword($password) {
    return $password;
}

function verifyPassword($hash, $password) {
    return $password == $hash;
}