<?php

function createPassword($password) {
    return md5($password);
}

function verifyPassword($hash, $password) {
    return md5($password) == $hash;
}