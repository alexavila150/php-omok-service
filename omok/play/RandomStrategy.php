<?php
    require_once('MoveStrategy.php');

    class RandomStrategy extends MoveStrategy {

        function pickPlace()
        {
            do{
                $x = rand(0, 14);
                $y = rand(0, 14);

            }while($this->board->places[$x][$y] != 0);
            $this->board->places[$x][$y] = 2;
            $this->board->update_file();
            return [$x, $y];
        }
    }