<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class Slot extends Command
{
    protected $signature = 'slot:generate';

    private $symbols;
    private $value;

    public function __construct()
    {
        $this->symbols = ['9', '10', 'J', 'Q', 'K', 'A', 'cat', 'dog', 'monkey', 'bird'];
//        $this->symbols = ['9', '9', 'J', '9', '9', '9', '9', '9', 'monkey', '9'];
        $this->value = 100;
        parent::__construct();
    }

    public function handle()
    {
        $board = [];
        $paylines = [];
        $bar = [];
        $totalWin = 0;

        //generating board and bar
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $board[$i][$j] = $this->symbols[rand(0, 9)];
                $bar[$i][$j] = rand(0, 14);
            }
        }

        $singleBoard = call_user_func_array('array_merge', $board);
        $countableBoard = [];

        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $countableBoard[] = $board[$j][$i];
            }
        }

        //searching for paylines
        for ($i = 0; $i < 3; $i++) {
            $lastValue = $countableBoard[[$i][0]];
            $repeats = 1;
            for ($j = 1; $j < 5; $j++) {
                if ($lastValue == $countableBoard[$bar[$i][$j]]) {
                    $repeats++;
                } else break;
            }
            if ($repeats >= 3) {
                $paylines[] = [
                    implode(" ", $bar[$i]) => $repeats
                ];
                switch ($repeats) {
                    case 3:
                        $totalWin += $this->value * 0.2;
                        break;
                    case 4:
                        $totalWin += $this->value * 2;
                        break;
                    case 5:
                        $totalWin += $this->value * 10;
                        break;
                }
            }

        }
        print_r(json_encode([
            'board' => $singleBoard,
            'paylines' => $paylines,
            'bet_amount' => $this->value,
            'total_win' => $totalWin,
        ]));

    }
}
