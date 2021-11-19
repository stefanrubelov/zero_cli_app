<?php

namespace App\Options;

use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use Carbon\Carbon;

class ListFasts extends Output
{
    private $menu;

    public function __construct(
        protected Input $input,
        Output $output
    ) {
        $this->output = $output;
        $this->menu = new Menu($this->input);
    }
    public function __invoke()
    {
        $this->listFasts();
    }
    public function listFasts()
    {
        $data = json_decode(file_get_contents('./fasting_data.json'));
        echo $this->magenta("----------------------------------------------");
        if ($data) {
            foreach ($data as $key) {
                $time_diff = $this->cyan(Carbon::parse($key->start_time)->diffForHumans(['parts' => 5], null, true));
                echo "Status: ", $key->active == true ? $this->cyan("Active") : $this->cyan("Finished");
                echo "Start time: ", $this->cyan($key->start_time);
                echo "End time: ", $this->cyan($key->end_time);
                echo (Carbon::now() > $key->start_time) ? "Elapsed time: " . $time_diff : "Starts in: " . $time_diff;
                echo "fasting_type: ", $this->cyan($key->fast_type);
                echo $this->magenta("----------------------------------------------");
            }
        } else {
            echo $this->magenta("----------------------------------------------");
            echo $this->yellow("No fast data available.");
            echo $this->magenta("----------------------------------------------");
        }
        $this->menu->secondaryMenu();
    }
}
