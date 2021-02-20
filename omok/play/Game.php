<?php
    class Game{
        public $board;
        public $strategy;

        function __construct($board){
            $this->board = $board;
        }

        function make_client_move($x, $y){
            $this->board->places[$x][$y] = 1;
            $this->board->update_file();
        }

        function get_server_move(){
            do{
                $x = rand(0, 14);
                $y = rand(0, 14);

            }while($this->board->places[$x][$y] != 0);
            $this->board->places[$x][$y] = 2;
            $this->board->update_file();
            return [$x, $y];
        }

        function get_player1_returning_row(){
            if(!$this->board->player_won(1)){
                return [];
            }

            if(count($this->board->winner_row) === 0){
                return [];
            }

            return $this->board->winner_row;
        }

        function get_player2_returning_row(){
            if(!$this->board->player_won(2)){
                return [];
            }

            if(count($this->board->winner_row) === 0){
                return [];
            }

            return $this->board->winner_row;
        }

        static function fromJson($json){
            $obj = json_decode($json);
            $strategy = $obj->{'strategy'};
            $board = $obj->{'board'};
            $game = new Game();
            $game->board = Board::fromJson($board);
            $name = $strategy->{'name'};
            $game->strategy = $name::fromJson($strategy);
            $game->strategy->board = $game->board;
            return $game;
        }
    }
