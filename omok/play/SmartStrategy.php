<?php
    require_once('MoveStrategy.php');
    require_once('RandomStrategy.php');

    class SmartStrategy extends MoveStrategy {
        function pickPlace()
        {
            for($i = 2; $i < 13; $i++){
                for($j = 2; $j < 13; $j++){
                    $horizontal_row = $this->board->get_row($i, $j, 1, 0);
                    $vertical_row = $this->board->get_row($i, $j, 0, 1);
                    $diagonal_row = $this->board->get_row($i, $j, 1, 1);
                    $neg_diagonal_row = $this->board->get_row($i, $j, 1, -1);

                    if($this->row_about_to_win($horizontal_row)){
                        for($k = 0; $k < 3; $k++){
                            if($horizontal_row[2 + $k] === 0){
                                $this->board->places[$i + $k][$j] = 2;
                                $this->board->update_file();
                                return [$i + $k, $j];
                            }

                            if($horizontal_row[2 - $k] === 0){
                                $this->board->places[$i - $k][$j] = 2;
                                $this->board->update_file();
                                return [$i - $k, $j];
                            }
                        }
                    }

                    if($this->row_about_to_win($vertical_row)){
                        for($k = 0; $k < 3; $k++){
                            if($vertical_row[2 + $k] === 0){
                                $this->board->places[$i][ $j + $k] = 2;
                                $this->board->update_file();
                                return [$i, $j + $k];
                            }

                            if($vertical_row[2 - $k] === 0){
                                $this->board->places[$i][ $j - $k] = 2;
                                $this->board->update_file();
                                return [$i, $j - $k];
                            }
                        }
                    }

                    if($this->row_about_to_win($diagonal_row)){
                        for($k = 0; $k < 3; $k++){
                            if($diagonal_row[2 + $k] === 0){
                                $this->board->places[$i + $k][ $j + $k] = 2;
                                $this->board->update_file();
                                return [$i + $k, $j + $k];
                            }

                            if($diagonal_row[2 - $k] === 0){
                                $this->board->places[$i - $k][$j - $k] = 2;
                                $this->board->update_file();
                                return [$i - $k, $j - $k];
                            }
                        }
                    }

                    if($this->row_about_to_win($neg_diagonal_row)){
                        for($k = 0; $k < 3; $k++){
                            if($neg_diagonal_row[2 + $k] === 0){
                                $this->board->places[$i + $k][$j - $k] = 2;
                                $this->board->update_file();
                                return [$i + $k, $j - $k];
                            }

                            if($neg_diagonal_row[2 - $k] === 0){
                                $this->board->places[$i - $k][ $j + $k] = 2;
                                $this->board->update_file();
                                return [$i - $k, $j + $k];
                            }
                        }
                    }

                }
            }
            $random = new RandomStrategy($this->board);
            return $random->pickPlace();
        }

        function row_about_to_win($row){
            $num_of_1s = 0;
            $num_of_2s = 0;
            foreach($row as $num){
                if($num === 1){
                    $num_of_1s++;
                }

                if($num === 2){
                    $num_of_2s++;
                }
            }
            return $num_of_2s == 0 && $num_of_1s > 2;
        }
    }