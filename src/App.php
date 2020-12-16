<?php

use Battleship\GameController;
use Battleship\Position;
use Battleship\Letter;
use Battleship\Color;

class App
{
    private static $myFleet = array();
    private static $enemyFleet = array();
    private static $console;
    private static $fieldMin = 5;
    private static $fieldMax = 26;
    private static $currentWidth;
    private static $currentHeight;

    static function run()
    {
        self::$console = new Console();
        self::$console->setForegroundColor(Color::MAGENTA);

        self::$console->println("                                     |__");
        self::$console->println("                                     |\\/");
        self::$console->println("                                     ---");
        self::$console->println("                                     / | [");
        self::$console->println("                              !      | |||");
        self::$console->println("                            _/|     _/|-++'");
        self::$console->println("                        +  +--|    |--|--|_ |-");
        self::$console->println("                     { /|__|  |/\\__|  |--- |||__/");
        self::$console->println("                    +---------------___[}-_===_.'____                 /\\");
        self::$console->println("                ____`-' ||___-{]_| _[}-  |     |_[___\\==--            \\/   _");
        self::$console->println(" __..._____--==/___]_|__|_____________________________[___\\==--____,------' .7");
        self::$console->println("|                        Welcome to Battleship                         BB-61/");
        self::$console->println(" \\_________________________________________________________________________|");
        self::$console->println();
        self::$console->resetForegroundColor();
        self::InitializeGame();
        self::StartGame();
    }

    public static function InitializeEnemyFleet()
    {
        self::$enemyFleet = GameController::initializeShips();

        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 4));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 5));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 6));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 7));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 8));

        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 6));
        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 7));
        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 8));
        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 9));

        array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 3));
        array_push(self::$enemyFleet[2]->getPositions(), new Position('B', 3));
        array_push(self::$enemyFleet[2]->getPositions(), new Position('C', 3));

        array_push(self::$enemyFleet[3]->getPositions(), new Position('F', 8));
        array_push(self::$enemyFleet[3]->getPositions(), new Position('G', 8));
        array_push(self::$enemyFleet[3]->getPositions(), new Position('H', 8));

        array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 5));
        array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 6));
    }

    public static function getFieldSize() {
        return [self::$currentWidth, self::$currentHeight];
    }

    public static function getRandomPosition()
    {
        list($rows, $lines) = self::getFieldSize();

        $letter = Letter::value(random_int(0, $lines - 1));
        $number = random_int(0, $rows - 1);

        return new Position($letter, $number);
    }


    /**
     * Initialize game field size.
     */
    public static function InitializeFieldSize() {
        while(true) {
            self::$console->println("Please enter the field width (min: " . self::$fieldMin . ", max: " . self::$fieldMax . "):");
            $input = (int)readline("");
            if($input < self::$fieldMin|| $input > self::$fieldMax) {
                self::$console->setForegroundColor(Color::RED);
                self::$console->println("Wrong field width");
                self::$console->resetForegroundColor();
            }
            else {
                self::$currentWidth = $input;
                break;
            }
        }
        while(true) {
            self::$console->println("Please enter the field height (min: " . self::$fieldMin . ", max: " . self::$fieldMax . "):");
            $input = (int)readline("");
            if($input < self::$fieldMin|| $input > self::$fieldMax) {
                self::$console->setForegroundColor(Color::RED);
                self::$console->println("Wrong field height");
                self::$console->resetForegroundColor();
            }
            else {
                self::$currentHeight = $input;
                break;
            }
        }
    }

    public static function InitializeMyFleet()
    {
        self::$myFleet = GameController::initializeShips();
        self::$console->println("Please position your fleet (Game board has size from A to " . Letter::$letters[self::$currentWidth - 1] . " and 1 to " . self::$currentHeight . ") :");

        foreach (self::$myFleet as $ship) {

            self::$console->println();
            printf("Please enter the positions for the %s (size: %s)", $ship->getName(), $ship->getSize());

            for ($i = 1; $i <= $ship->getSize(); $i++) {
                printf("\nEnter position %s of %s (i.e A3):", $i, $ship->getSize());
                $input = readline("");
                $ship->addPosition($input);
            }
        }
    }

    public static function beep()
    {
        echo "\007";
    }

    public static function InitializeGame()
    {
        self::InitializeFieldSize();
        self::InitializeMyFleet();
        self::InitializeEnemyFleet();
    }

    public static function StartGame()
    {
        self::$console->println("\033[2J\033[;H");
        self::$console->println("                  __");
        self::$console->println("                 /  \\");
        self::$console->println("           .-.  |    |");
        self::$console->println("   *    _.-'  \\  \\__/");
        self::$console->println("    \\.-'       \\");
        self::$console->println("   /          _/");
        self::$console->println("  |      _  /\" \"");
        self::$console->println("  |     /_\'");
        self::$console->println("   \\    \\_/");
        self::$console->println("    \" \"\" \"\" \"\" \"");

        while (true) {
            self::$console->println("");
            self::$console->println("Player, it's your turn");
            self::$console->println("Enter coordinates for your shot :");
            $position = readline("");

            $isHit = GameController::checkIsHit(self::$enemyFleet, self::parsePosition($position));
            if ($isHit) {
                self::beep();
                self::$console->println("                \\         .  ./");
                self::$console->println("              \\      .:\" \";'.:..\" \"   /");
                self::$console->println("                  (M^^.^~~:.'\" \").");
                self::$console->println("            -   (/  .    . . \\ \\)  -");
                self::$console->println("               ((| :. ~ ^  :. .|))");
                self::$console->println("            -   (\\- |  \\ /  |  /)  -");
                self::$console->println("                 -\\  \\     /  /-");
                self::$console->println("                   \\  \\   /  /");
            }

            echo $isHit ? "Yeah ! Nice hit !" : "Miss";
            self::$console->println();


            $enemyFleetSunk = true;
            foreach (self::$enemyFleet as $ship)
            {
                if (!$ship->isSunk()) {
                    $enemyFleetSunk = false;
                    break;
                }
            }

            if ($enemyFleetSunk)
            {
                self::$console->println("\nYou are the winner!");
                exit();
            }

            $position = self::getRandomPosition();
            $isHit = GameController::checkIsHit(self::$myFleet, $position);
            self::$console->println();
            printf("Computer shoot in %s%s and %s", $position->getColumn(), $position->getRow(), $isHit ? "hit your ship !\n" : "miss");
            if ($isHit) {
                self::beep();

                self::$console->println("                \\         .  ./");
                self::$console->println("              \\      .:\" \";'.:..\" \"   /");
                self::$console->println("                  (M^^.^~~:.'\" \").");
                self::$console->println("            -   (/  .    . . \\ \\)  -");
                self::$console->println("               ((| :. ~ ^  :. .|))");
                self::$console->println("            -   (\\- |  \\ /  |  /)  -");
                self::$console->println("                 -\\  \\     /  /-");
                self::$console->println("                   \\  \\   /  /");

                $myFleetSunk = true;
                foreach (self::$myFleet as $ship)
                {
                    if (!$ship->isSunk()) {
                        $myFleetSunk = false;
                        break;
                    }
                }

                if ($myFleetSunk)
                {
                    self::$console->println("\nYou lost!");
                    exit();
                }
            }
        }
    }

    public static function parsePosition($input)
    {
        $letter = strtoupper(substr($input, 0, 1));
        $number = (int)filter_var($input, FILTER_SANITIZE_NUMBER_INT);

        if(!is_numeric($number)) {
            throw new Exception("Not a number: $number");
        }

        list($rows, $lines) = self::getFieldSize();

        if($number < 1 || $number > $lines) {
            throw new Exception("Out of a game field. Number: $number");
        }

        if(!in_array($letter, Letter::$letters)) {
            throw new Exception("Letter not exist: $letter");
        }

        if(array_search($letter, Letter::$letters) >= $rows ) {
            throw new Exception("Out of a game field. Letter: $letter");
        }
        return new Position($letter, $number);
    }
}
