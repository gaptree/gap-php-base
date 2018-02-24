<?php
namespace phpunit\Gap\Base;

use Gap\Base\ConsoleHandler;

use PHPUnit\Framework\TestCase;

class ConsoleHandlerTest extends TestCase
{
    public function testEmptyCmd(): void
    {
        $app = $this->getApp();
        $consoleHandler = new ConsoleHandler($app);
        $consoleHandler->handle();

        $this->expectOutputString("Useage: \n"
            . "  gap COMMAND [options]\n"
            . "  COMMAND:\n"
            . "    case => phpunit\Gap\Base\Cmd\CaseCmd \n"
            . "    init => Gap\Util\Coder\Cmd\InitCmd \n");
    }

    public function testCmd(): void
    {
        $app = $this->getApp();
        $consoleHandler = new ConsoleHandler($app);
        $consoleHandler->handle(['', 'case'], 1);

        $this->expectOutputString('run case cmd');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage cannot find command [not-exists]
     */
    public function testCannotFound(): void
    {
        $app = $this->getApp();
        $consoleHandler = new ConsoleHandler($app);
        $consoleHandler->handle(['', 'not-exists'], 1);
    }

    protected function getApp()
    {
        $config = new \Gap\Config\Config();
        $config->set('baseDir', __DIR__);
        $config->set('app', [
            'article' => [
                'dir' => 'app/article'
            ]
        ]);

        return new \Gap\Base\App($config);
    }
}
