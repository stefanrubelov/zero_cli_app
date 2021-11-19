<?php

namespace App\Controllers;

date_default_timezone_set('Etc/GMT+0');

use Carbon\Carbon;
use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Validator\Validator;

class FastController
{
    private $output;
    private object $menu;
    private object $validator;
    private bool $start_date_error_flag = false;
    private bool $fast_type_error_flag = false;
    public string $fast_date;
    public string $fast_type;

    public array $fast_types = [
        1 => '16 hours',
        2 => '18 hours',
        3 => '20 hours',
        4 => '36 hours'
    ];

    public function __construct(
        protected Input $input,
        Output $output
    ) {
        $this->output = $output;
        $this->validator = new Validator();
        $this->menu = new Menu($this->input);
    }

    public function __invoke()
    {
        $this->getStartDate();
        $this->getFastType();
        $this->saveFast($this->fast_date, $this->fast_type);
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
        } else if (!key_exists($this->fast_type, $this->fast_types) && is_int((int)$this->fastType)) {
            echo $this->output->red('Please choose an item from the menu');
            $this->fast_type_error_flag = true;
            $this->getFastType();
            return false;
        }
    }

    public function saveFast($start_date, $fast_type_id)
    {
        $new_data_arr = [];
        $fast_type_hours = (int)explode(' ', $this->fast_types[$fast_type_id])[0];
        $old_data_arr = json_decode(file_get_contents('./fasting_data.json'), true);
        $end_time = Carbon::parse($start_date)->addHours($fast_type_hours);
        $newdata = [
            'active' => true,
            'start_time' => Carbon::parse($start_date),
            'end_time' => $end_time,
            'fast_type' => $this->fast_types[$fast_type_id]
        ];
        if ($old_data_arr) {
            foreach ($old_data_arr as $item) {
                array_push($new_data_arr, $item);
            }
        }
        array_push($new_data_arr, $newdata);
        file_put_contents('./fasting_data.json', json_encode($new_data_arr));
        echo $this->output->cyan('New fast added');
        $this->menu->secondaryMenu();
    }
}
