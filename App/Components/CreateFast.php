<?php

namespace App\Components;

use App\Console\Output;
use App\Console\Input;
use App\Validator\Validator;

class CreateFast
{
    private bool $error = false;
    private $output;
    public $fastDate;
    public $fastType;

    public function __construct(
        protected Input $input,
        Output $output
    ) {
        $this->output = $output;
    }

    public function __invoke()
    {
        $this->getStartDate();
        $this->getFastType();
    }

    public function getStartDate()
    {
        if (!$this->error) {
            echo $this->output->blue('Start a new fast.');
            echo $this->output->blue('Please enter a start date and time (Y-M-D H:M:S)');
        }
        $this->fastDate = $this->input->returnInput();

        $validator = new Validator();
        $validator = $validator->validateDate($this->fastDate);
        if (!$validator) {
            $this->error = true;
            $this->getStartDate();
        }
    }

    public function getFastType()
    {
        echo $this->output->blue('Please select a fast type');
        $this->fastType = $this->input->returnInput();
        echo "this is your fast type $this->fastType, and it starts on $this->fastDate \n\r";
        exit;
        //
        //
        //
        //
        //
    }
}
