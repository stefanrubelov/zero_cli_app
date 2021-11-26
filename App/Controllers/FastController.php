<?php

namespace App\Controllers;

use Carbon\Carbon;
use App\Components\Menu;
use App\Console\Input;
use App\Console\Output;
use App\Validator\Validator;
use Stringable;

// date_default_timezone_set('Etc/GMT+0');
class FastController
{
    /**
     * @var Output $output
     */
    public $output;

    /**
     * @var Menu $menu
     */
    public $menu;

    /**
     * @var Validator $validator
     */
    public $validator;

    /**
     * @var bool $start_date_error_flag
     */
    public $start_date_error_flag = false;

    /**
     * @var bool $fast_type_error_flag
     */
    public $fast_type_error_flag = false;

    /**
     * @var string $fast_start_date
     */
    public $fast_start_date;

    /**
     * @var string $fast_type
     */
    public $fast_type;

    /**
     * @var array $fast_types
     */
    public array $fast_types = [
        1 => '16 hours',
        2 => '18 hours',
        3 => '20 hours',
        4 => '36 hours'
    ];

    /**
     * FastController constructor
     * @param Input $input
     * @param Output $output
     */
    public function __construct(
        protected Input $input,
        Output $output
    ) {
        $this->output = $output;
        $this->validator = new Validator();
        $this->menu = new Menu($this->input);
    }

    /**
     * FastController invoke 
     * Outputs getFastStartDate() method
     * Outputs getFastType() method
     * 
     * @return saveNewFast()
     */
    public function __invoke()
    {
        $this->getFastStartDate();
        $this->getFastType();
        $this->saveNewFast($this->fast_start_date, $this->fast_type);
    }

    /**
     * Outputs input for fast start date
     * 
     * @return bool
     */
    public function getFastStartDate(): void
    {
        if (!$this->start_date_error_flag) {
            echo $this->output->blue('Please enter a start date and time (Y-M-D H:M:S)');
        }
        $this->fast_start_date = $this->input->returnInput();
        $validator = $this->validator->validateDate($this->fast_start_date);
        if (!$validator) {
            $this->start_date_error_flag = true;
            $this->getFastStartDate();
        }
    }

    /**
     * Outputs input for fast type
     * 
     * @return bool
     */
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

    /**
     * Saves new fast into 'fasting_data.json' file
     * 
     * Output success message
     * @return void
     */
    public function saveNewFast($start_date, $fast_type_id): void
    {
        $new_data_arr = [];
        $fast_type_hours = (int)explode(' ', $this->fast_types[$fast_type_id])[0];
        $old_data_arr = json_decode(file_get_contents(FASTING_DATA_JSON_FILE), true);
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
        file_put_contents(FASTING_DATA_JSON_FILE, json_encode($new_data_arr));
        echo $this->output->cyan('New fast added');
        $this->menu->secondaryMenu();
    }
}
