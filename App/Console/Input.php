<?php

namespace App\Console;

class Input
{
    public function returnInput(): string
    {
        return trim(fgets(STDIN), "\n\r");
    }
}
