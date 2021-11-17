<?php

namespace App\Validator;

use App\Console\Output;
use Carbon\Carbon;

class Validator extends Output
{
    public object $now;
    public string $input;
    public object $inputDate;

    public function __construct()
    {
    }

    public function validateDate($userInput): string
    {
        $this->now = Carbon::now();

        try {
            $this->inputDate = Carbon::createFromFormat('Y-m-d H:i:s', $userInput);
        } catch (\Exception $e) {
            echo $this->red("Invalid date/time format, try again");
            return false;
        }

        if ($this->inputDate && $this->inputDate < $this->now) {
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
        $data = json_decode(file_get_contents('./fasting_data.json'));
        foreach ($data as $key => $value) {
            foreach ($value as $item) {
                if ($item == 'true') {
                    return true;
                }
            }
        }
    }
}
