<?php
    require_once('MoveStrategy.php');

    class SmartStrategy extends MoveStrategy {
        function pickPlace()
        {
            return [0, 0];
        }
    }