<?php

function createPassword($password) {
    $salt = md5(uniqid(rand(), true));
    $raw = pbkdf2('sha512', $password, $salt, 10000, 40);
    return $salt . ':' . base64_encode($raw);
}

function verifyPassword($hash, $password) {
    list ($salt, $hash) = explode(':', $hash, 2);
    $raw = base64_decode($hash);
    return pbkdf2('sha512', $password, $salt, 10000, 40) == $raw;
}

function pbkdf2($algo, $password, $salt, $iterations, $length) {
    $size = strlen(hash($algo, '', true));
    $len = ceil($length / $size);
    $result = '';
    for ($i = 1; $i <= $len; $i++) {
        $tmp = hash_hmac($algo, $salt . pack('N', $i), $password, true);
        $res = $tmp;
        for ($j = 1; $j < $iterations; $j++) {
            $tmp = hash_hmac($algo, $tmp, $password, true);
            $res ^= $tmp;
        }
        $result .= $res;
    }
    return substr($result, 0, $length);
}
