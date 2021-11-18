<?php

namespace App;


use App\Console\Input;
use App\Console\Output;
use App\Components\Menu;
use App\Validator\Validator;

class MainController
{
    private $menu;
    public function __construct(
        protected Input $input
    ) {
        $this->menu = new Menu($this->input, new Output(), new Validator());
    }

    /**
     * Run the app
     *
     */
    public function run()
    {
        $this->menu->printMenu();
    }
}
