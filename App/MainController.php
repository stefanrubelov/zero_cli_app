<?php

namespace App;

use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Validator\Validator;

class MainController
{
    public function __construct(
        protected Input $input
    ) {
    }

    /**
     * Run the maincontroller class
     *
     */
    public function run()
    {
        $menu = new Menu($this->input, new Output(), new Validator());
        $menu->printMenu();
    }
}
