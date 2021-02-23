<?php
    require_once("../play/Board.php");

    define('STRATEGY', 'strategy');

    function check_if_strategy_exists()
    {
        if (!array_key_exists(STRATEGY, $_GET)) {
            echo "{\"response\": false, \"reason\": \"Strategy not specify\"}";
            exit;
        }
    }

    function check_if_strategy_is_valid()
    {
        $strategy = $_GET[STRATEGY];

        if (!($strategy === "Smart" || $strategy === "Random")) {
            echo "{\"response\": false, \"reason\": \"Unknown Strategy\"}";
            exit;
        }
    }

    function create_and_write_to_file($path, $txt)
    {
        $file = fopen("../data/" . $path . ".txt", "w") or die("Unable to open file!");
        fwrite($file, $txt);
        fclose($file);
    }

    check_if_strategy_exists();
    check_if_strategy_is_valid();
    $board = new Board(15, $_GET[STRATEGY]);
    create_and_write_to_file($board->pid, $board->toJson());

    echo "{\"response\":true, \"pid\":\"" . $board->pid . "\"}";