<?php
namespace phpunit\Gap\Base;

use PHPUnit\Framework\TestCase;

class FunTest extends TestCase
{
    public function testCurrentDate(): void
    {
        $now = current_date();
        $expected = date(gap_date_format());

        $this->assertEquals($now, $expected);
    }
}
