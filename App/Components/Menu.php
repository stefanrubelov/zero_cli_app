<?php

namespace App\Components;

use App\Console\Input;
use App\Console\Output;
use App\Controllers\FastController;
use App\Options\ListFasts;
use App\Options\EndActiveFast;
use App\Options\CheckFastStatus;
use App\Options\UpdateActiveFast;
use App\Validator\Validator;

class Menu
{
    /**
     * @var Output $output
     */
    private $output;

    /**
     * @object Validator $validator
     */
    private $validator;

    /**
     * @var bool $menu_error_flag
     */
    private $menu_error_flag = false;

    /**
     * Menu constructor
     * @param Input $input
     */
    public function __construct(
        protected Input $input,
    ) {
        $this->output = new Output;
        $this->validator = new Validator();
    }

    /**
     * @var array $menu
     * Menu for the (fast) options
     */
    public $menu = [
        1 => 'Check fast status',
        2 => 'Start a new fast',
        3 => 'End an active fast',
        4 => 'Update an active fast',
        5 => 'List all fasts',
        6 => 'Exit the app'
    ];

    /**
     * @var array $secondary_menu
     * Secondary menu for actions after successfull menu option execution
     */
    public $secondary_menu = [
        1 => 'Back to menu',
        2 => 'Exit the app'
    ];

    /**
     * @array $actions
     * Array of actions for executing the menu options
     */
    public $actions = [
        1 => CheckFastStatus::class,
        2 => FastController::class,
        3 => EndActiveFast::class,
        4 => UpdateActiveFast::class,
        5 => ListFasts::class,
    ];

    /**
     * Output the menu items
     * Output error messages 
     * Output menu items
     * 
     * @return void
     */
    public function mainMenu(): void
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

        if ($this->menu_error_flag == false) {
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
            $this->menu_error_flag = true;
            $this->mainMenu();
        } elseif ($userInput == '6') {
            echo $this->output->blue('Goodbye');
            exit;
        }
    }

    /**
     * Output secondary menu
     * Output error messages
     * 
     * @return void
     */
    public function secondaryMenu(): void
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
