<?php

namespace App\Components;

use App\Console\Input;
use App\Console\Output;
use App\Validator\Validator;
use App\Components\ListFasts;
use App\Components\FastController;

class Menu
{
    private $output;
    private $validator;
    private bool $alreadyOpened = false;
    private bool $errorFlag = false;

    public function __construct(
        protected Input $input,
        Output $output,
        Validator $validator
    ) {
        $this->output = $output;
        $this->validator = $validator;
    }

    /**
     * Menu for the (fast) options
     */
    public array $menu = [
        1 => 'Check a fast Status',
        2 => 'Start a new fast',
        3 => 'End an active fast',
        4 => 'Update an active fast',
        5 => 'List all fasts',
        6 => 'Exit the app'
    ];

    /**
     * Array of actions for executing the menu options
     */
    public array $actions = [
        // 1 => 'check fast status',
        2 => FastController::class,
        // 3 => 'End an active fast',
        // 4 => 'Update an active fast',
        5 => ListFasts::class,
    ];

    /**
     * List the menu items
     * @return array
     */
    public function printMenu()
    {
        if ($this->validator->checkActiveFasts()) {
            unset($this->menu[2]);
            unset($this->actions[2]);
        } elseif (!$this->validator->checkActiveFasts()) {
            unset($this->menu[3]);
            unset($this->actions[3]);
            unset($this->menu[4]);
            unset($this->actions[4]);
        }

        if ($this->alreadyOpened == false) {
            echo $this->output->cyan('Please select an item from the menu');
        }
        foreach ($this->menu as $key => $value) {
            echo $this->output->yellow("[$key]__$value");
        }
        $userInput = $this->input->returnInput();

        if (key_exists($userInput, $this->actions) and $userInput != "6") {
            $object = new $this->actions[$userInput]($this->input, new Output);
            $object();
        } elseif (!key_exists($userInput, $this->actions) and $userInput != "6") {
            echo $this->output->red('Please select a valid item from the menu');
            $this->alreadyOpened = true;
            $this->printMenu();
        } elseif ($userInput == '6') {
            echo $this->output->blue('Goodbye');
            exit;
        }
    }
    /**
     * Back to menu method
     * @return array
     */
    public function backToMenu()
    {
        if (!$this->errorFlag) {
            echo $this->output->yellow("[1]__Back to menu");
            echo $this->output->yellow("[2]__Exit");
        }

        $userInput = $this->input->returnInput();
        if ($userInput == '1') {
            $this->printMenu();
        } elseif ($userInput == '2') {
            echo $this->output->blue('Goodbye');
            exit;
        } else {
            $this->errorFlag = true;
            echo $this->output->red("Press a key from the menu");
            echo $this->output->yellow("[1]__Back to menu");
            echo $this->output->yellow("[2]__Exit");
            $this->backToMenu();
        }
    }
}
