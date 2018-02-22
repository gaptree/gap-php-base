<?php
namespace Gap\Base\View\Register;

use Foil\Engine;

interface RegisterInterface
{
    public function register(Engine $engine): void;
}
