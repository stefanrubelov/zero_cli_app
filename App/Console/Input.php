<?php

namespace App\Console;

class Input
{
    /**
     * Read the user input from the console
     * 
     * @return string
     */
    public function returnInput(): string
    {
        return trim(fgets(STDIN), "\n\r");
    }
}
