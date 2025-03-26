<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ThreadService;
use Framework\TemplateEngine;

class PanelController{
    public function __construct(
        private TemplateEngine $view,
        private ThreadService $threads
    )
    {
        
    }

    public function view()
    {
        //Sprawdzić aktywny wątek, rodzaj konta, zezwolenia w aktywnym wątku
        //Pobrać wpisy z aktywnego wątku z uwzględnieniem uprawnień			
        /*
			Pobranie ulubionych wątków z bazy danych
			Print wątków do panelu bocznego HTML
		*/
        $this->view->addGlobal('styles', ['/assets/css/task.css', '/assets/css/thread.css']);
        echo $this->view->render('/panel.php');
        $this->threads->create('JEDEN');
    }

    public function createThread(){

    }
}