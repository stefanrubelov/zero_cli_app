<?php

namespace App\Console;

use App\Console\Input;

class Output extends Input
{
    /**
     * @array $colors_arr
     * 'string color' => 'console color code'
     */
    private $colors_arr = [
        'BLACK' => '0:30',
        'RED' => '0;31',
        'GREEN' => '0;32',
        'BLUE' => '0;34',
        'MAGENTA' => '0;35',
        'CYAN' => '0;36',
        'YELLOW' => '1;33',
        'WHITE' => '1;37'
    ];

    /**
     * Color output black
     * 
     * @return string
     */
    public function black(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'BLACK');
    }

    /**
     * Color output red
     * 
     * @return string
     */
    public function red(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'RED');
    }

    /**
     * Color output green
     * 
     * @return string
     */
    public function green(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'GREEN');
    }

    /**
     * Color output blue
     * 
     * @return string
     */
    public function blue(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'BLUE');
    }

    /**
     * Color output magenta
     * 
     * @return string
     */
    public function magenta(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'MAGENTA');
    }

    /**
     * Color output cyan
     * 
     * @return string
     */
    public function cyan(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'CYAN');
    }

    /**
     * Color output yellow
     * 
     * @return string
     */
    public function yellow(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'YELLOW');
    }

    /**
     * Color output white
     * 
     * @return string
     */
    public function white(string $messageInput): string
    {
        return $this->printMessage($messageInput, 'WHITE');
    }

    /**
     * Color output 
     * @param string $message
     * @param string $color, white by default
     * 
     * @return string
     */
    private function printMessage(string $message, string $color = 'WHITE'): string
    {
        $stringColor = $this->colors_arr[strtoupper($color)];
        return "\e[{$stringColor}m{$message}\e[0m\n\r";
    }
}
