<?php

namespace App\Options;

use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;

class EndActiveFast
{
    /**
     * @var Input $input
     */
    private $input;

    /**
     * @var Output $output
     */
    private $output;
    /**
     * @var Menu $menu
     */
    private $menu;

    /**
     * @var bool $end_active_fast_error_flag
     */
    private bool $end_active_fast_error_flag = false;

    /**
     * EndActiveFast constructor
     */
    public function __construct()
    {
        $this->input = new Input();
        $this->output = new Output();
        $this->menu = new Menu(new Input());
    }

    /**
     * EndActiveFast invoke 
     * Outputs endActiveFast() method
     * 
     * @return string:bool
     */
    public function __invoke()
    {
        $this->endActiveFast();
    }

    /**
     * Ends an active fast if there is one
     * 
     * @return void
     */
    public function endActiveFast(): void
    {
        if (!$this->end_active_fast_error_flag)
            echo $this->output->yellow('Are you sure you want to end the fast? [Y/N]');
        $user_input = $this->input->returnInput();
        if (strtolower($user_input) == 'n') {
            $this->menu->mainMenu();
        } else if (strtolower($user_input) == 'y') {
            $new_data_arr = [];
            $data = json_decode(file_get_contents(FASTING_DATA_JSON_FILE), true);
            if ($data) {
                foreach ($data as $key => $value) {
                    $value['active'] = false;
                    array_push($new_data_arr, $value);
                }
                file_put_contents(FASTING_DATA_JSON_FILE, json_encode($new_data_arr));
                echo $this->output->magenta("----------------------------------------------");
                echo $this->output->yellow("Fast ended.");
                echo $this->output->magenta("----------------------------------------------");
                $this->menu->secondaryMenu();
                return;
            } else {
                echo $this->output->magenta("----------------------------------------------");
                echo $this->output->yellow("No fast data available.");
                echo $this->output->magenta("----------------------------------------------");
                $this->menu->secondaryMenu();
                return;
            }
            echo $this->output->magenta("----------------------------------------------");
            echo $this->output->yellow("No active fast.");
            echo $this->output->magenta("----------------------------------------------");
            $this->menu->secondaryMenu();
        } else {
            echo $this->output->red('Please type Y to end an active fast, and N to go back to the main menu');
            $this->end_active_fast_error_flag = true;
            $this->endActiveFast();
        }
    }
}
