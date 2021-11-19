<?php

namespace App\Controllers;

use App\Console\Input;
use App\Components\Menu;

class MainController
{
    private $menu;
    public function __construct(
        protected Input $input
    ) {
        $this->menu = new Menu($this->input);
    }

    /**
     * Run the app
     *
     */
    public function run()
    {
        $this->menu->mainMenu();
    }
}
