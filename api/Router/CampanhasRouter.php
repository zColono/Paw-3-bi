<?php

namespace Api\Router;

use Slim\App;
use Api\Controller\CampanhasController;
use Api\Middlewares\Campanhas\ValidateCampanhasBody;
use Api\Middlewares\Campanhas\ValidateCampanhasId;

class CampanhasRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        $this->app->post('/campanhas', [CampanhasController::class, 'createController'])
            ->add(ValidateCampanhasBody::class);

        $this->app->get('/campanhas', [CampanhasController::class, 'findAllController']);

        $this->app->get('/campanhas/count', [CampanhasController::class, 'countController']);

        $this->app->get('/campanhas/{idCampanha}', [CampanhasController::class, 'findByIdController'])
            ->add(ValidateCampanhasId::class);

        $this->app->put('/campanhas/{idCampanha}', [CampanhasController::class, 'updateController'])
            ->add(ValidateCampanhasBody::class)
            ->add(ValidateCampanhasId::class);

        $this->app->delete('/campanhas/{idCampanha}', [CampanhasController::class, 'deleteController'])
            ->add(ValidateCampanhasId::class);
    }
}
