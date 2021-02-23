<?php
    class Board{
        public $size;
        public $places;
        public $pid;
        public $strategy;
        public $winner_row = [];

        function __construct($size=15, $strategy=''){
            $this->size = $size;
            $this->places = array_fill(0, $size, array_fill(0, $size, 0));
            $this->pid = uniqid();
            $this->strategy = $strategy;
        }

        function update_file(){
            $path = "../data/" . $this->pid . ".txt";
            $file = fopen($path, "w") or die("Unable to open file!");
            fwrite($file, $this->toJson());
            fclose($file);
        }

        function toJson(){
            return json_encode($this);
        }

        function player_won($player_num){
            for($i = 2; $i < $this->size - 3; $i++){
                for($j = 2; $j < $this->size - 3; $j++){
                    if( $this->check_if_rows_are_valid($i, $j, $player_num)){
                        return true;
                    }
                }
            }
            return false;
        }

        private function check_if_rows_are_valid($i, $j, $player_num)
        {
            $horizontal_row = $this->get_row($i, $j, 1, 0);
            $vertical_row = $this->get_row($i, $j, 0, 1);
            $diagonal_row = $this->get_row($i, $j, 1, 1);
            $neg_diagonal_row = $this->get_row($i, $j, 1, -1);

            if ($this->array_is_fill_with($horizontal_row, $player_num)) {
                $this->winner_row = [];
                array_push($this->winner_row,
                    $i - 0, $j,
                    $i - 1, $j,
                    $i, $j,
                    $i + 1, $j,
                    $i + 2, $j
                );
                return true;
            }

            if ($this->array_is_fill_with($vertical_row, $player_num)) {
                $this->winner_row = [];
                array_push($this->winner_row,
                    $i, $j - 2,
                    $i, $j - 1,
                    $i, $j,
                    $i, $j + 1,
                    $i, $j + 2
                );
                return true;
            }

            if ($this->array_is_fill_with($diagonal_row, $player_num)) {
                $this->winner_row = [];
                array_push($this->winner_row,
                    $i - 2, $j - 2,
                    $i - 1, $j - 1,
                    $i, $j,
                    $i + 1, $j + 1,
                    $i + 2, $j + 2
                );
                return true;
            }

            if ($this->array_is_fill_with($neg_diagonal_row, $player_num)) {
                $this->winner_row = [];
                array_push($this->winner_row,
                    $i + 2, $j - 2,
                    $i + 1, $j - 1,
                    $i, $j,
                    $i - 1, $j + 1,
                    $i - 2, $j + 2
                );
                return true;
            }

            return false;
        }


        function array_is_fill_with($array, $element){
            foreach($array as $num){
                if($num != $element){
                    return false;
                }
            }
            return true;
        }

        function get_row($x, $y, $dx, $dy){
            $row = array();
            for($i = -2; $i < 3; $i++){
                array_push($row, $this->places[$x + $dx * $i][$y + $dy * $i]);
            }
            return $row;
        }

        static function fromJson($json){
            $obj = json_decode($json);
            $board = new Board();
            $board->size = $obj->size;
            $board->places = $obj->places;
            $board->pid = $obj->pid;
            $board->strategy = $obj->strategy;
            return $board;
        }

        public function place_is_empty($x, $y)
        {
            return $this->places[$x][$y] == 0;
        }

        static function get_board($pid){
            $path = "../data/" . $pid . ".txt";
            $file = fopen($path, "r") or die("Unable to open file!");
            $json = fread($file, filesize($path));
            fclose($file);

            return self::fromJson($json);
        }

        public function __toString(){
            $result = "";
            foreach($this->places as $row){
                foreach($row as $place){
                    $result = $result . $place . ", ";
                }
                $result = $result . "<br>";
            }
            return $result;
        }
    }