<?php
namespace App\Services;

use App\Exceptions\CustomException;

/**
 * AES128加解密类
 * php7 版本
 */
class Aes256cbc
{
    private $key;
    private $cipher;

    /**
     * Aes constructor.
     * @param $key
     */
    function __construct($key, $cipher = 'aes-256-cbc')
    {
        $this->key    = $key;
        $this->cipher = $cipher;
    }

    /**
     * @param $value
     * @return string
     */
    public function encrypt($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $iv = str_random(openssl_cipher_iv_length($this->cipher));
        // OPENSSL_RAW_DATA方式【会用PKCS#7进行补位】
        $value = openssl_encrypt($value, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        if ($value === false) {
            throw new CustomException('加密失败');
        }

        $json = json_encode(['iv' => base64_encode($iv), 'value' => base64_encode($value)]);

        return base64_encode($json);
    }

    /**
     * @param $str
     * @param $jsonDecode
     * @param $str
     */
    public function decrypt($str, $jsonDecode = true, $assoc = false)
    {
        $obj = json_decode(base64_decode($str));
        if (empty($obj)) {
            throw new CustomException('获取密文失败');
        }

        if (!isset($obj->iv) || !isset($obj->value)) {
            throw new CustomException('解密参数失败');
        }

        $iv = base64_decode($obj->iv);
        $value = base64_decode($obj->value);
        $decrypted = openssl_decrypt($value, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new CustomException('解密失败');
        }

        if ($jsonDecode) {
            $decrypted = json_decode($decrypted, $assoc);
        }

        return $decrypted;
    }
}
