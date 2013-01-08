<?php

use Zend\Crypt\BlockCipher;

const CIPHER_KEY = "AVk05eoEmhgG10+s9D8VDXbJKGxAi67roAPRpeJRX4o49wfsg/o2FwuwU9dXk33cpWpcM1GcEUkIcLV+hMYPwg==";

function createPassword($password) {
    return encrypt(password_hash($password, PASSWORD_DEFAULT));
}

function verifyPassword($hash, $password) {
    return password_verify($password, decrypt($hash));
}

function encrypt($password) {
    global $key;
    $cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
    $cipher->setKey(base64_decode(CIPHER_KEY));

    return $cipher->encrypt($password);
}

function decrypt($password) {
    global $key;
    $cipher = BlockCipher::factory('mcrypt', array('algorithm' => 'aes'));
    $cipher->setKey(base64_decode(CIPHER_KEY));

    return $cipher->decrypt($password);
}
