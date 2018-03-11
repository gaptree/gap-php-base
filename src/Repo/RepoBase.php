<?php
namespace Gap\Base\Repo;

use Gap\Db\DbManagerInterface;

abstract class RepoBase
{
    protected $dmg;
    protected $cnnName;
    protected $cnn;

    public function __construct(DbManagerInterface $dmg)
    {
        $this->dmg = $dmg;

        if (empty($this->cnnName)) {
            throw new \Exception("cnnName could not be empty");
        }

        $this->cnn = $this->dmg->connect($this->cnnName);

        $this->startup();
    }

    public function startup()
    {
    }
}
