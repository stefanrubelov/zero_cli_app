<?php

namespace App\Validator;

date_default_timezone_set('Etc/GMT+0');

use Carbon\Carbon;
use App\Console\Output;

class Validator extends Output
{
    /**
     * @var string Input $output
     */
    public $input;

    /**
     * @var object $time_now
     */
    public $time_now;

    /**
     * @var object $input_date
     */
    public $input_date;

    /**
     * Validate input date
     * @return bool
     */
    public function validateDate($user_input): string
    {
        $this->time_now = Carbon::now()->addHour();

        try {
            $this->input_date = Carbon::parse($user_input);
        } catch (\Exception $e) {
            echo $this->red("Invalid date/time format, try again");
            return false;
        }

        if ($this->input_date < $this->time_now) {
            echo $this->red("You cant enter a past date/time in the past, try again");
            return false;
        }
        return true;
    }

    /**
     * Checks if there is an active fast in the saved data
     * @return bool
     */
    public function checkActiveFasts()
    {
        $data = json_decode(file_get_contents(FASTING_DATA_JSON_FILE));
        if ($data) {
            foreach ($data as $key => $value) {
                foreach ($value as $item) {
                    if ($item === true) {
                        return true;
                    }
                }
            }
        }
    }
}
