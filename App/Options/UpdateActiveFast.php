<?php

namespace App\Options;

use Carbon\Carbon;
use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Controllers\FastController;
use App\Validator\Validator;

class UpdateActiveFast extends FastController
{
    /**
     * @var array $new_data
     */
    protected $new_data;

    /**
     * UpdateActiveFast constructor
     */
    public function __construct()
    {
        $this->input = new Input();
        $this->output = new Output();
        $this->validator = new Validator();
        $this->menu = new Menu(new Input());
    }

    /**
     * UpdateActiveFast invoke 
     * Outputs updateActiveFast() method
     * 
     * @return string
     */
    public function __invoke()
    {
        $this->updateActiveFast();
    }

    /**
     * Saves updated active fast
     * 
     * @return void
     */
    public function updateActiveFast(): void
    {
        $new_data_arr = [];
        $data = json_decode(file_get_contents(FASTING_DATA_JSON_FILE));
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
                        $this->getFastStartDate();
                        $this->getFastType();
                        $this->saveUpdatedFast($this->fast_date, $this->fast_type);
                    }
                    array_push($new_data_arr, $this->new_data);
                    continue;
                } else {
                    array_push($new_data_arr, $value);
                }
            }
            file_put_contents(FASTING_DATA_JSON_FILE, json_encode($new_data_arr));
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

    /**
     * Updates active fast
     * 
     * @return array
     */
    public function saveUpdatedFast($start_date, $fast_type_id): array
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
