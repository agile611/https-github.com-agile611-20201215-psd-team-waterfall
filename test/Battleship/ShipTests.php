<?php

namespace Battleship;

use PHPUnit\Framework\TestCase;

final class ShipTests extends TestCase
{
    public function testIsSunk()
    {
        $ship = new Ship("TestShip", 1);
        $ship->addPosition("A1");

        $this->assertFalse($ship->isSunk());

        $ship->shoot(new Position('A', 1));

        $this->assertTrue($ship->isSunk());
    }
}
