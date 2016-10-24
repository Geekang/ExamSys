<?php
header("content-type:text/html;charset='utf-8'");
 
# AES-256 
# @32
#

# AES-128
# @16
#

function encrypt_aes_encode($data, $key) {
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
    $iv_size = mcrypt_enc_get_iv_size($td);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    mcrypt_generic_init($td, $key ,$iv);
    $encrypted = mcrypt_generic($td, $data);
    mcrypt_generic_deinit($td);
    return $iv. $encrypted;
}

function encrypt_aes_decode($data, $key) {
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
    $iv = substr($data, 0, 32);
    mcrypt_generic_init($td, $key, $iv);
    $data = substr($data,  32, strlen($data));
    $data = mdecrypt_generic($td, $data);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    return trim($data);
}













