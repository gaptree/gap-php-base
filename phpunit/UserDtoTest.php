<?php
namespace phpunit\Gap\Base;

use PHPUnit\Framework\TestCase;

class UserDtoTest extends TestCase
{
    public function testUser(): void
    {
        $nick = 'testNick';
        $user = new UserDto([
            'nick' => $nick
        ]);

        $this->assertEquals($nick, $user->nick);
    }
}
