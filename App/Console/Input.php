<?php

namespace App\Console;

class Input
{
    public function returnInput()
    {
        return trim(fgets(STDIN), "\n\r");
    }
}
