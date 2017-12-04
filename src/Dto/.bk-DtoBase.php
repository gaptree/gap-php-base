<?php
namespace Gap\Base\Dto;

use JsonSerializable;

abstract class DtoBase implements JsonSerializable
{
    public function __construct($data = [])
    {
        if ($data) {
            foreach ($data as $key => $val) {
                if ($key) {
                    $fun = 'set' . ucfirst($key);
                    $this->$fun($val);
                }
            }
        }
    }

    public function get($key)
    {
        return $this->callGet('get' . ucfirst($key));
    }

    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);

        if ($prefix === 'get') {
            return $this->callGet($method);
        }

        if ($prefix === 'set') {
            return $this->callSet($method, $args);
        }
    }

    public function jsonSerialize()
    {
        return $this->getData();
    }

    public function getData()
    {
        $keys = array_keys(get_object_vars($this));
        $data = [];

        foreach ($keys as $key) {
            $fun = 'get' . ucfirst($key);
            $data[$key] = $this->$fun();
        }

        return $data;
    }

    /*
     * not sure yet
    public function getZcode()
    {
        if ($this->zcode) {
            return $this->zcode;
        }

        $this->zcode = uniqid(
            $this->getApp()->getConfig()->get('server.id')
        );
        return $this->zcode;
    }
     */

    protected function extractVar($method)
    {
        $var = lcfirst(substr($method, 3));
        if (property_exists($this, $var)) {
            return $var;
        }

        // todo
        //throw new \Exception("$var-not-exists in " . get_class($this));

        //return null;
    }

    protected function setVar($key, $val)
    {
        if (property_exists($this, $key)) {
            $this->$key = $val;
        }

        //throw new \Exception("$key-not-exists" . get_class($this));
    }

    protected function callGet($method)
    {
        if ($var = $this->extractVar($method)) {
            return $this->$var;
        }

        return '';
    }

    protected function callSet($method, $args)
    {
        // if $args[0] is null return
        if (!isset($args[0])) {
            return;
        }

        // ::setVar
        if ($var = $this->extractVar($method)) {
            $this->$var = $args[0];
            return;
        }

        // ::setDto_var => getDto()->setVar();
        if ($flagPos = strpos($method, '_')) {
            $dtoName = substr($method, 3, $flagPos - 3);
            if (!$dtoName) {
                throw new \Exception('cannot get dto from ' . $method);
            }

            $getDtoFun = 'get' . $dtoName;
            if (!$dto = $this->$getDtoFun()) {
                return;
            }

            $subSetFun = 'set' . substr($method, $flagPos + 1);
            $dto->$subSetFun($args[0]);
            return;
        }

        // ::setDtoId => getDto()->setDtoId()
        if (($idPos = strpos($method, 'Id')) && !isset($method[$idPos + 2])) {
            return $this->callSetDtoId($method, $args);
        }
    }

    protected function callSetDtoId($method, $args)
    {
        if (!$idPos = strpos($method, 'Id')) {
            return;
        }

        if (isset($method[$idPos + 2])) {
            return;
        }

        $dtoName = substr($method, 3, $idPos - 3);
        $getFun = 'get' . $dtoName;
        if (!$dto = $this->$getFun()) {
            return;
        }
        $subSetFun = 'set' . $dtoName . 'Id';
        $dto->$subSetFun($args[0]);
        return;
    }

    /*
    protected function getApp()
    {
        return app();
    }
     */
}
