<?php

namespace App\Console;

use App\Console\Input;

class Output extends Input
{
    private array $colors_arr = [
        'BLACK' => '0:30',
        'RED' => '0;31',
        'GREEN' => '0;32',
        'BLUE' => '0;34',
        'MAGENTA' => '0;35',
        'CYAN' => '0;36',
        'YELLOW' => '1;33',
        'WHITE' => '1;37'
    ];

    public function black(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'BLACK');
    }

    public function red(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'RED');
    }

    public function green(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'GREEN');
    }

    public function blue(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'BLUE');
    }

    public function magenta(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'MAGENTA');
    }

    public function cyan(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'CYAN');
    }

    public function yellow(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'YELLOW');
    }

    public function white(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'WHITE');
    }

    private function printMessage(string $message, string $color): string
    {
        $stringColor = $this->colors_arr[strtoupper($color)];
        return "\e[{$stringColor}m{$message}\e[0m\n\r";
    }
}
