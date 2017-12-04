<?php
namespace Gap\Base\Repo;

use Gap\Database\DatabaseManager;
use Gap\Database\DataSet;
use Gap\Database\Contract\SqlBuilder\SelectSqlBuilderInterface;

abstract class RepoBase
{
    protected $dmg;
    protected $cnnName;
    protected $cnn;

    public function __construct(DatabaseManager $dmg)
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

    protected function dataSet(SelectSqlBuilderInterface $ssb, $dtoClass)
    {
        return new DataSet($ssb, $dtoClass);
    }
}
