<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class PanelController{
    public function __construct(
        private TemplateEngine $view
    )
    {
        
    }

    public function view()
    {
        echo "Logged";
    }
}