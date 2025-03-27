<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ThreadService;
use App\Services\ValidatorService;
use Framework\Exceptions\ValidatorException;
use Framework\TemplateEngine;

class PanelController{
    public function __construct(
        private TemplateEngine $view,
        private ThreadService $threads,
        private ValidatorService $validator
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
    }

    public function createThread(){

        if($this->threads->getCount() === 10)
        {
            throw new ValidatorException(['thread_name' => ['Exceeded thread limit, max: 10']]);
        }
        $this->validator->validateThread($_POST);
        $this->threads->create($_POST['thread_name']);
        redirectTo('/');
    }
}