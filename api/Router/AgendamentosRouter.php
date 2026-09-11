<?php

namespace Api\Router;

use Slim\App;
use Api\Controller\AgendamentosController;
use Api\Middlewares\Agendamento\ValidateAgendamentosBody;
use Api\Middlewares\Agendamento\ValidateAgendamentosId;

class AgendamentosRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $this->app->post('/agendamentos', [AgendamentosController::class, 'createController'])
            ->add(ValidateAgendamentosBody::class);

        $this->app->get('/agendamentos', [AgendamentosController::class, 'findAllController']);

        $this->app->get('/agendamentos/count', [AgendamentosController::class, 'countController']);

        $this->app->get('/agendamentos/{idAgendamentos}', [AgendamentosController::class, 'findByIdController'])
            ->add(ValidateAgendamentosId::class);

        $this->app->put('/agendamentos/{idAgendamentos}', [AgendamentosController::class, 'updateController'])
            ->add(ValidateAgendamentosBody::class)
            ->add(ValidateAgendamentosId::class);

        $this->app->delete('/agendamentos/{idAgendamentos}', [AgendamentosController::class, 'deleteController'])
            ->add(ValidateAgendamentosId::class);
    }
}
