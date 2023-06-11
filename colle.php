<?php

colle(5, 5, []);

function colle($x, $y, array $coords = [])
{
    if ($x > 0 && $y > 0) {
        system("clear");
        $table = display($x, $y, $coords);
        prompt($x, $y, $coords, $table);
    }
}

function display($x, $y, $coords)
{
    $border = border_line($x - 1);
    $table = "";
    for ($i = 0; $i < $y; $i++) {
        $table .= $border;
        for ($j = 0; $j < $x; $j++) {
            $table .= "| " . isEmpty($j, $i, $coords) . " ";
        }
        $table .= "|\n";
    }
    $table .= $border;
    echo $table;
    return $table;
}

function isEmpty($x, $y, $coords)
{
    foreach ($coords as $key => $value) {
        if ($value[0] == $x && $value[1] == $y) {
            return "X";
        }
    }
    return " ";
}
function prompt($x, $y, $coords, $table)
{
    $playerone = array();
    $playertwo = array();
    $count1 = 0;
    $count2 = 0;
    $count3 = 0;
    $win1 = 0;
    $win2 = 0;
    $player = "one";
    echo "Player 1, place your 2 ships :\n";
    $prompt = readline("Player 1 $> ");
    do {

        if (strpos($prompt, "query") !== false) {
            $coord = explode(" ", $prompt)[1];
            $coord = explode(",", $coord);
            // var_dump($player);
            if ($player == "one") {
                if (isEmpty(trim(substr($coord[0], 1, strlen($coord[0]) - 1)), trim(substr($coord[1], 0, 1)), $playertwo) == " ") {
                    echo "Player 1, you didn’t touch anything.\n";
                } else {
                    $win2++;
                    echo "Player 1, you touched a boat of player 2\n";
                }
                $player = "two";
            } else {
                if (isEmpty(trim(substr($coord[0], 1, strlen($coord[0]) - 1)), trim(substr($coord[1], 0, 1)), $playerone) == " ") {
                    echo "Player 2, you didn’t touch anything.\n";
                } else {
                    $win1++;
                    echo "Player 2, you touched a boat of player 1\n";
                }
                $player = "one";
            }
            // var_dump($playerone);
            // var_dump($playertwo);
        } elseif (strpos($prompt, "add") !== false) {
            $coord = explode(" ", $prompt)[1];
            $coord = explode(",", $coord);
            $coord = [intval(trim(substr($coord[0], 1, strlen($coord[0]) - 1))), intval(trim(substr($coord[1], 0, 1)))];
            $add = add_coord($coord, $coords, $table);

            if ($add != "") {
                echo $add;
            } else {
                if ($player == "one") {
                    add_coord($coord, $playerone, $table);
                } else {
                    add_coord($coord, $playertwo, $table);
                }
                if ($count1 < 3) {
                    $count1++;
                    $player = "one";
                }

                if ($count1 >= 2) {
                    if ($count1 == 2) {
                        echo "Player 2, place your 2 ships :\n";
                    }
                    $count2++;
                    $player = "two";
                }
            }
        } elseif (strpos($prompt, "remove") !== false) {
            $coord = explode(" ", $prompt)[1];
            $coord = explode(",", $coord);
            $coord = [intval(trim(substr($coord[0], 1, strlen($coord[0]) - 1))), intval(trim(substr($coord[1], 0, 1)))];
            $remove = remove_coord($coord, $coords);
            if ($remove != "") {
                echo $remove;
            }
        } elseif (strpos($prompt, "display") !== false) {
            $table = display($x, $y, $coords);
        }

        
        if ($count2 > 2 && $prompt != "") {
            $count3++;
            if ($win1 == 2) {
                echo "Player 2 win !!\n";
                $playerone = array();
                $playertwo = array();
                $count1 = 0;
                $count2 = 0;
                $count3 = 0;
                $win1 = 0;
                $win2 = 0;
                $player = "one";
                echo "Player 1, place your 2 ships :\n";
                $prompt = readline("Player 1 $> ");
            }

            if ($win2 == 2) {
                echo "Player 2 win !!\n";
                $playerone = array();
                $playertwo = array();
                $count1 = 0;
                $count2 = 0;
                $count3 = 0;
                $win1 = 0;
                $win2 = 0;
                $player = "one";
                echo "Player 1, place your 2 ships :\n";
                $prompt = readline("Player 1 $> ");
            }

            if ($count3 % 2 != 0) {
                $player = "one";
                echo "Player 1, launch your attack :\n";
                $prompt = readline("Player 1 $> ");
            } else {
                $player = "two";
                echo "Player 2, launch your attack :\n";
                $prompt = readline("Player 2 $> ");
            }
        } else {
            if ($player == "one") {
                $prompt = readline("Player 1 $> ");
            } else {
                $prompt = readline("Player 2 $> ");
            }
        }

    } while (strpos($prompt, "exit") !== true);
}


function remove_coord($coord, &$player)
{
    if (in_array($coord, $player)) {
        $key = array_search($coord, $player);
        unset($player[$key]);
        return "";
    } else {
        return "No cross exists at this location\n";
    }
}
function add_coord(&$coord, &$coords, $table)
{
    if (isEmpty($coord[0], $coord[1], $coords) == " ") {
        array_push($coords, $coord);
        return "";
    } else {
        return "No cross exists at this location\n";
    }
}

function border_line($x)
{
    $border = "";
    for ($i = 0; $i <= $x; $i++) {
        $border .= "+---";
    }
    $border .= "+\n";
    return $border;
}
