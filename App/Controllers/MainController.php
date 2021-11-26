<?php

namespace App\Controllers;

use App\Components\Menu;
use App\Console\Input;

class MainController
{
    /**
     * @var Menu $menu
     */
    private $menu;

    /**
     * FastController constructor
     * @param Input $input
     */
    public function __construct(
        protected Input $input
    ) {
        $this->menu = new Menu($this->input);
    }

    /**
     * Run the app
     * 
     * @return void
     */
    public function run(): void
    {
        $this->menu->mainMenu();
    }
}
