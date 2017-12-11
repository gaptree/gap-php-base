<?php
namespace Gap\Base\Exception;

class ClientException extends \RuntimeException
{
    protected $key = '';
    protected $msg = [];

    public function __construct($msg, $key = '')
    {
        if (is_string($msg)) {
            $msg = [$msg];
        }

        if (!is_array($msg)) {
            // todo
            throw new \Exception("msg-not-array");
        }

        $this->key = $key;
        $this->msg = $msg;

        parent::__construct(sprintf(...$msg));
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getMsg()
    {
        return $this->msg;
    }
}
