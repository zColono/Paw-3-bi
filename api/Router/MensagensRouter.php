<?php

namespace Api\Router;

use Slim\App;
use Api\Controller\MensagensController;
use Api\Middlewares\Mensagens\ValidateMensagensBody;
use Api\Middlewares\Mensagens\ValidateMensagensId;

class MensagensRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $this->app->post('/mensagens', [MensagensController::class, 'createController'])
            ->add(ValidateMensagensBody::class);

        $this->app->get('/mensagens', [MensagensController::class, 'findAllController']);

        $this->app->get('/mensagens/count', [MensagensController::class, 'countController']);

        $this->app->get('/mensagens/{idMensagens}', [MensagensController::class, 'findByIdController'])
            ->add(ValidateMensagensId::class);

        $this->app->put('/mensagens/{idMensagens}', [MensagensController::class, 'updateController'])
            ->add(ValidateMensagensBody::class)
            ->add(ValidateMensagensId::class);

        $this->app->delete('/mensagens/{idMensagens}', [MensagensController::class, 'deleteController'])
            ->add(ValidateMensagensId::class);
    }
}
