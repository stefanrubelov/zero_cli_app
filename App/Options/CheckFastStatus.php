<?php


namespace App\Options;

use Carbon\Carbon;
use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Validator\Validator;

class CheckFastStatus
{
    // private $input;
    private $output;
    private $menu;
    public function __construct(
        protected Input $input,
        Output $output
    ) {
        $this->output = $output;
        $this->menu = new Menu($this->input, $this->output, new Validator);
    }

    public function __invoke()
    {
        $this->checkFastStatus();
    }

    public function checkFastStatus()
    {
        $data = json_decode(file_get_contents('./fasting_data.json'));
        if ($data) {
            foreach ($data as $key => $value) {
                foreach ($value as $item) {
                    if ($item === true) {
                        $time_diff = $this->output->cyan(Carbon::parse($value->start_time)->diffForHumans(['parts' => 5], null, true));
                        echo $this->output->magenta("----------------------------------------------");
                        echo "Status: ", $value->active == true ? $this->output->cyan("Active") : $this->output->cyan("Finished");
                        echo "Start time: ", $this->output->cyan(Carbon::parse($value->start_time));
                        echo "End time: ", $this->output->cyan(Carbon::parse($value->end_time));
                        echo (Carbon::now() > $value->start_time) ? "Elapsed time: " . $time_diff : "Starts in: " . $time_diff;
                        echo "fasting_type: ", $this->output->cyan($value->fast_type);
                        echo $this->output->magenta("----------------------------------------------");
                        $this->menu->secondaryMenu();
                        return;
                    }
                }
            }
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
}
