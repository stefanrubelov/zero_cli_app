<?php

namespace App;

class ListFasts
{
    public function __invoke()
    {
        $data = json_decode(file_get_contents('./fasting_data.json'));
        echo "\n\r";
        foreach ($data as $key) {
            echo "Fast #: $key->id \n\r";
            echo "Active: $key->active \n\r";
            echo "Start time: $key->start_time \n\r";
            echo "End time: $key->end_time \n\r";
            echo "Elapsed time: $key->elapsed_time \n\r";
            echo "fasting_type: $key->fasting_type \n\r";
            echo "-------------------------------";
        }
    }
}
