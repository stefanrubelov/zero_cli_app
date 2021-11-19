<?php

namespace App\Components;

use App\Console\Input;
use App\Console\Output;
use App\Options\ListFasts;
use App\Validator\Validator;
use App\Options\EndActiveFast;
use App\Options\CheckFastStatus;
use App\Options\UpdateActiveFast;
use App\Controllers\FastController;

class Menu
{
    private $output;
    private $validator;
    private bool $menu_opened_flag = false;

    public function __construct(
        protected Input $input,
    ) {
        $this->output = new Output;
        $this->validator = new Validator();
    }
    /**
     * Menu for the (fast) options
     */
    public array $menu = [
        1 => 'Check fast status',
        2 => 'Start a new fast',
        3 => 'End an active fast',
        4 => 'Update an active fast',
        5 => 'List all fasts',
        6 => 'Exit the app'
    ];
    /**
     * Secondary menu for actions after successfull menu option execution
     * 
     */
    public array $secondary_menu = [
        1 => 'Back to menu',
        2 => 'Exit the app'
    ];
    /**
     * Array of actions for executing the menu options
     * @return array
     */
    public array $actions = [
        1 => CheckFastStatus::class,
        2 => FastController::class,
        3 => EndActiveFast::class,
        4 => UpdateActiveFast::class,
        5 => ListFasts::class,
    ];
    /**
     * List the menu items
     * @return array
     */
    public function mainMenu()
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
        if ($this->menu_opened_flag == false) {
            echo $this->output->cyan('Please select an item from the menu');
        }
        foreach ($this->menu as $key => $value) {
            echo $this->output->yellow("[$key]__$value");
        }
        $userInput = $this->input->returnInput();

        if (key_exists($userInput, $this->actions) && $userInput != "6") {
            $object = new $this->actions[$userInput]($this->input, new Output);
            $object();
        } elseif (!key_exists($userInput, $this->actions) && $userInput != "6") {
            echo $this->output->red('Please select a valid item from the menu');
            $this->menu_opened_flag = true;
            $this->mainMenu();
        } elseif ($userInput == '6') {
            echo $this->output->blue('Goodbye');
            exit;
        }
    }
    /**
     * Back to menu method
     * @return array
     */
    public function secondaryMenu()
    {
        foreach ($this->secondary_menu as $key => $value) {
            echo $this->output->yellow("[$key]__$value");
        }
        $userInput = $this->input->returnInput();
        if ($userInput == '1') {
            $this->mainMenu();
        } elseif ($userInput == '2') {
            echo $this->output->blue('Goodbye');
            exit;
        } else {
            echo $this->output->red("Press a key from the menu");
            $this->secondaryMenu();
        }
    }
}
