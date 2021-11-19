<?php

namespace App\Options;

use Carbon\Carbon;
use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Validator\Validator;

class UpdateActiveFast
{
    private object $input;
    private object $output;
    private object $validator;
    private object $menu;
    private bool $start_date_error_flag = false;
    private bool $fast_type_error_flag = false;
    protected string $fast_date;
    protected string $fast_type;
    protected array $new_data;

    public array $fast_types = [
        1 => '16 hours',
        2 => '18 hours',
        3 => '20 hours',
        4 => '36 hours'
    ];

    public function __construct()
    {
    }
    public function __invoke()
    {
        $this->input = new Input();
        $this->output = new Output();
        $this->validator = new Validator();
        $this->menu = new Menu(new Input());
        $this->updateActiveClass();
    }

    public function updateActiveClass()
    {
        $new_data_arr = [];
        $data = json_decode(file_get_contents('./fasting_data.json'));
        if ($data) {
            foreach ($data as $key => $value) {
                if ($value->active === true) {
                    $time_diff = $this->output->cyan(Carbon::parse($value->start_time)->diffForHumans(Carbon::now()->addHour(), ['parts' => 5], null, true));
                    echo $this->output->magenta("----------------------------------------------");
                    echo "Status: ", $value->active == true ? $this->output->cyan("Active") : $this->output->cyan("Finished");
                    echo "Start time: ", $this->output->cyan(Carbon::parse($value->start_time));
                    echo "End time: ", $this->output->cyan(Carbon::parse($value->end_time));
                    echo (Carbon::now() > $value->start_time) ? "Elapsed time: " . $time_diff : "Starts in: " . $time_diff;
                    echo "fasting_type: ", $this->output->cyan($value->fast_type);
                    echo $this->output->magenta("----------------------------------------------");
                    echo $this->output->yellow('Are you sure you want to update this fast? [Y/N]');
                    $user_input = $this->input->returnInput();
                    if (strtolower($user_input) == 'n') {
                        $this->menu->mainMenu();
                    } else if (strtolower($user_input) == 'y') {
                        $this->getStartDate();
                        $this->getFastType();
                        $this->updateFast($this->fast_date, $this->fast_type);
                    }
                    array_push($new_data_arr, $this->new_data);
                    continue;
                } else {
                    array_push($new_data_arr, $value);
                }
            }
            file_put_contents('./fasting_data.json', json_encode($new_data_arr));
            echo $this->output->magenta("----------------------------------------------");
            echo $this->output->yellow('Fast updated!');
            echo $this->output->magenta("----------------------------------------------");
            $this->menu->secondaryMenu();
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
    }


    public function getStartDate()
    {
        if (!$this->start_date_error_flag) {
            echo $this->output->blue('Please enter a start date and time (Y-M-D H:M:S)');
        }
        $this->fast_date = $this->input->returnInput();
        $validator = $this->validator->validateDate($this->fast_date);
        if (!$validator) {
            $this->start_date_error_flag = true;
            $this->getStartDate();
        }
    }

    public function getFastType()
    {
        if (!$this->fast_type_error_flag)
            echo $this->output->blue('Please select a fast type');
        foreach ($this->fast_types as $key => $value) {
            echo $this->output->yellow("[$key]__$value");
        }
        try {
            $this->fast_type = $this->input->returnInput();
        } catch (\Throwable $th) {
            echo $this->output->red('Please choose an item from the menu');
            return false;
        }
        if (key_exists($this->fast_type, $this->fast_types)) {
        } else if (!key_exists($this->fast_type, $this->fast_types) && is_int((int)$this->fast_type)) {
            echo $this->output->red('Please choose an item from the menu');
            $this->fast_type_error_flag = true;
            $this->getFastType();
            return false;
        }
    }

    public function updateFast($start_date, $fast_type_id)
    {
        $fast_type_hours = (int)explode(' ', $this->fast_types[$fast_type_id])[0];
        $end_time = Carbon::parse($start_date)->addHours($fast_type_hours);
        $this->new_data = [
            'active' => true,
            'start_time' => Carbon::parse($start_date),
            'end_time' => $end_time,
            'fast_type' => $this->fast_types[$fast_type_id]
        ];
        return $this->new_data;
    }
}
