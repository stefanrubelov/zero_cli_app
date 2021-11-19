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
    private bool $errorFlag = false;
    private bool $fastTypeError = false;
    private $output = null;
    public string $fastDate;
    public $fastType;
    protected $validator;
    private $menu;

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
        $this->saveFast($this->fastDate, $this->fastType);
    }

    public function getStartDate()
    {
        if (!$this->errorFlag) {
            echo $this->output->blue('Start a new fast.');
            echo $this->output->blue('Please enter a start date and time (Y-M-D H:M:S)');
        }
        $this->fastDate = $this->input->returnInput();

        $validator = $this->validator->validateDate($this->fastDate);
        if (!$validator) {
            $this->errorFlag = true;
            $this->getStartDate();
        }
    }

    public function getFastType()
    {
        if (!$this->fastTypeError)
            echo $this->output->blue('Please select a fast type');

        foreach ($this->fast_types as $key => $value) {
            echo $this->output->yellow("[$key]__$value");
        }
        try {
            $this->fastType = $this->input->returnInput();
        } catch (\Throwable $th) {
            echo $this->output->red('Please choose an item from the menu');
            return false;
        }
        if (key_exists($this->fastType, $this->fast_types)) {
            // $this->fastType = $this->input->returnInput();
            // echo "this is your fast type $this->fastType, and it starts on $this->fastDate \n\r";
            // return;
        } else if (!key_exists($this->fastType, $this->fast_types) && is_int((int)$this->fastType)) {
            echo $this->output->red('Please choose an item from the menu');
            $this->fastTypeError = true;
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
        $this->menu->backToMenu();
    }
}
