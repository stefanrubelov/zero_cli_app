<?php

namespace App;

// use App\ListFasts;
use App\Console\Input;

class MainController
{
    private bool $alreadyOpened = false;

    public function __construct(
        protected Input $input
    ) {
    }
    /**
     * Menu for the (fast) options
     */
    private array $menu = [
        1 => 'Create',
        2 => 'Start',
        3 => 'End',
        4 => 'Exit',
        5 => 'List'
    ];
    /**
     *    Array of actions for executing the menu options
     */
    private array $actions = [
        1 => 'Create',
        // 2 => 'Start',
        // 3 => 'End',
        4 => 'Exit',
        5 => ListFasts::class
    ];

    /**
     * Run the maincontroller class
     *
     */
    public function run()
    {
        $this->printMenu();
        $input = $this->input->returnInput();
        if (key_exists($input, $this->actions) and $input != "4") {
            // echo $this->actions[$input];
            $object = new $this->actions[$input];
            $object();
            // $command = new $this->actions["$input"]($this->input, $this->output, $this->store);
            // $command->run();
        } elseif ($input == "4") {
            echo "Goodbye";
            exit;
        } elseif (!key_exists($input, $this->actions) and $input != "4") {
            echo "Please select a valid item from the menu\n\r";
            $this->alreadyOpened = true;
            $this->run();
        }
    }

    public function printMenu()
    {
        if ($this->alreadyOpened == false) {
            echo "Please select an item from the menu\n\r";
        }
        foreach ($this->menu as $key => $value) {
            echo '[' . $key . '] ' . $value . "\n\r";
        }
    }
}
