<?php

namespace Battleship;

use PHPUnit\Framework\TestCase;

final class PositionTests extends TestCase
{
    public function testIsHit()
    {
        $position = new Position('A', '1');
        $this->assertFalse($position->isHit());
        $position->hit();

        $this->assertTrue($position->isHit());
    }
}
