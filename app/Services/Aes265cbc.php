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
    private $options;

    /**
     * Aes constructor.
     * @param $key
     */
    function __construct($key, $cipher = 'aes-256-cbc', $options = OPENSSL_RAW_DATA)
    {
        $this->key     = $key;
        $this->cipher  = $cipher;

        /**
         * 填充方式
         * OPENSSL_RAW_DATA 或 1：用PKCS#7填充
         * OPENSSL_ZERO_PADDING 或 2：用\0填充（有bug）
         * OPENSSL_NO_PADDING 或 3：不填充
         */
        $this->options = $options;
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

        // 如果是0填充，则手动填充\0。（自动填充有问题）
        if ($this->options == OPENSSL_ZERO_PADDING) {
            $this->options = OPENSSL_NO_PADDING;
            $value = $this->padZero($value);
        }

        $iv = str_random(openssl_cipher_iv_length($this->cipher));

        // 加密
        $value = openssl_encrypt($value, $this->cipher, $this->key, $this->options, $iv);
        if ($value === false) {
            throw new CustomException('加密失败');
        }

        $json = json_encode(['iv' => base64_encode($iv), 'value' => base64_encode($value)]);

        return base64_encode($json);
    }

    // 增加\0填充
    private function padZero($string)
    {
        // AES的区块长度固定为128比特，即16字节
        $blockSize = 16;

        // 获取待加密的字符串长度
        $len = strlen($string);

        // 计算填充长度（与16整数倍的长度差）
        $padLength = $blockSize - ($len % $blockSize);

        // 如果有长度差，则填充 \0
        if ($padLength) {
            $string .= str_repeat("\0", $padLength);
        }

        return $string;
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

        // 如果是0填充，则手动操作。（自动有问题）
        if ($this->options == OPENSSL_ZERO_PADDING) {
            $options = OPENSSL_NO_PADDING;
        } else {
            $options = $this->options;
        }

        $iv = base64_decode($obj->iv);
        $value = base64_decode($obj->value);

        // 解密
        $decrypted = openssl_decrypt($value, $this->cipher, $this->key, $options, $iv);
        if ($decrypted === false) {
            throw new CustomException('解密失败');
        }

        // 如果是0填充，则手动去除\0
        if ($this->options == OPENSSL_ZERO_PADDING) {
            // 去除\0填充
            $decrypted = rtrim($decrypted, "\0");
        }

        if ($jsonDecode) {
            $decrypted = json_decode($decrypted, $assoc);
        }

        return $decrypted;
    }
}
