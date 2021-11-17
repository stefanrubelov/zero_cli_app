<?php

namespace App\Components;

use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Validator\Validator;

class ListFasts extends Output
{
    private $output;
    public function __construct(
        protected Input $input,
        Output $output
    ) {
        $this->output = $output;
    }
    public function __invoke()
    {
        $data = json_decode(file_get_contents('./fasting_data.json'));
        echo "\n\r";
        foreach ($data as $key) {
            echo "Fast #: ",  $this->cyan($key->id);
            echo "Active: ", $this->cyan($key->active);
            echo "Start time: ", $this->cyan($key->start_time);
            echo "End time: ", $this->cyan($key->end_time);
            echo "Elapsed time: ", $this->cyan($key->elapsed_time);
            echo "fasting_type: ", $this->cyan($key->fasting_type);
            echo $this->magenta("----------------------------------------------");
        }
        $menu = new Menu($this->input, $this->output, new Validator);
        $menu->backToMenu();
    }
}
