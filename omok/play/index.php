<?php
    require_once('Move.php');
    require_once('Board.php');
    require_once('Game.php');

    define('PID', 'pid');
    define('MOVE', 'move');
    define('DATA', '../data/');

    class Response{
        public $response;
        public $ack_move;
        public $move;

        function __construct($response, $ack_move, $move)
        {
            $this->response = $response;
            $this->ack_move = $ack_move;
            $this->move = $move;
        }
    }

    class Index{
        public $x_req;
        public $y_req;
        public $x_res;
        public $y_res;
        public $board;
        public $game;

        function check_if_coordinates_are_in_range($x, $y)
        {
            if ($x > 14 || $x < 0) {
                echo "{\"response\": false, \"reason\": \"Invalid x coordinate\"}";
                exit;
            }

            if ($y > 14 || $y < 0) {
                echo "{\"response\": false, \"reason\": \"Invalid y coordinate\"}";
                exit;
            }
        }

        function check_if_pid_is_valid()
        {
            if (!array_key_exists(PID, $_GET)) {
                echo "{\"response\": false, \"reason\": \"Pid not specify\"}";
                exit;
            }

            $files = scandir(DATA);

            if (!in_array($_GET[PID] . '.txt', $files)) {
                echo "{\"response\": false, \"reason\": \"Pid not specified\"}";
                exit;
            }
        }

        public function check_if_move_is_valid()
        {
            if (!array_key_exists(MOVE, $_GET)) {
                echo "{\"response\": false, \"reason\": \"Move not well-formed\"}";
                exit;
            }

            $coordinates = Index::get_coordinates_from_string($_GET['move']);
            $this->x_req = intval($coordinates[0]);
            $this->y_req = intval($coordinates[1]);
        }

        public function check_if_place_is_empty()
        {
            $this->board = Board::get_board($_GET[PID]);

            if ($this->board->places[$this->x_req][$this->y_req] != 0) {
                echo "{\"response\": false, \"reason\": \"Move not allowed\"}";
                exit;
            }
        }

        function send_json_response()
        {
            $ack_move = new MoveDTO(
                $this->x_req,
                $this->y_req,
                $this->board->player_won(1),
                false,
                $this->game->get_player1_returning_row()
            );

            $move = new MoveDTO(
                $this->x_res,
                $this->y_res,
                $this->board->player_won(2),
                false,
                $this->game->get_player2_returning_row()
            );

            $response = new Response(true, $ack_move, $move);
            echo json_encode($response);
        }

        function get_coordinates_from_string($move_string){
            $move_list = explode(",", $move_string);
            if(sizeof($move_list) != 2){
                echo "{\"response\": false, \"reason\": \"Invalid Move Format\"}";
                exit;
            }

            $this->check_if_coordinates_are_in_range($move_list[0], $move_list[1]);
            return $move_list;
        }


        function on_start()
        {
            $this->check_if_pid_is_valid();
            $this->check_if_move_is_valid();
            $this->check_if_place_is_empty();

            $this->game = new Game($this->board);
            $this->game->make_client_move($this->x_req, $this->y_req);
            $move_coordinates = $this->game->get_server_move();
            $this->x_res = $move_coordinates[0];
            $this->y_res = $move_coordinates[1];

            //TODO:Check if tied

            Index::send_json_response();
        }
    }

    $index = new Index();
    $index->on_start();



