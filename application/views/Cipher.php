<?php

class Cipher
{
    private $algo;
    private $mode;
    private $source;
    private $iv = null;
    private $key = null;

    public function __construct($algo = 'aes-256-cbc', $mode = MCRYPT_MODE_CBC, $source = '')
    {
        if ($source === '') {
            $this->ivSize = openssl_cipher_iv_length($algo);
        }
        $this->algo = $algo;
        $this->mode = $mode;
        $this->source = $source;

        if (is_null($this->algo) || (strlen($this->algo) == 0)) {
            $this->algo = 'aes-256-cbc';
        }
        if (is_null($this->mode) || (strlen($this->mode) == 0)) {
            $this->mode = MCRYPT_MODE_CBC;
        }
    }

    public function encrypt($data, $key = null, $iv = null)
    {
        $key = (strlen($key) == 0) ? null : $key;

        $this->setKey($key);
        $this->setIV($iv);

        $out = openssl_encrypt($data, $this->algo, $this->key, OPENSSL_RAW_DATA, $this->iv);
        return base64_encode($out);
    }

    public function decrypt($data, $key = null, $iv = null)
    {
        $key = (strlen($key) == 0) ? null : $key;

        $this->setKey($key);
        $this->setIV($iv);

        $data = base64_decode($data);
        $out = openssl_decrypt($data, $this->algo, $this->key, OPENSSL_RAW_DATA, $this->iv);
        return trim($out);
    }

    public function getIV()
    {
        return base64_encode($this->iv);
    }

    private function setIV($iv)
    {
        if (!is_null($iv)) {
            $this->iv = base64_decode($iv);
        }
        if (is_null($this->iv)) {
            $iv_size = openssl_cipher_iv_length($this->algo);
            $this->iv = openssl_random_pseudo_bytes($iv_size);
        }
    }

    private function setKey($key)
    {
        if (!is_null($key)) {
            $key_size = openssl_cipher_iv_length($this->algo);
            $this->key = hash("sha256", $key, true);
            $this->key = substr($this->key, 0, $key_size);
        }
        if (is_null($this->key)) {
            trigger_error("You must specify a key at least once in either Cipher::encrypt() or Cipher::decrypt().", E_USER_ERROR);
        }
    }
}

?>
