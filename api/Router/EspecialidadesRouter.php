<?php

namespace Api\Router;

use Slim\App;
use Api\Controller\EspecialidadesController;
use Api\Middlewares\Especialidades\ValidateEspecialidadesBody;
use Api\Middlewares\Especialidades\ValidateEspecialidadesId;

/**
 * Classe responsável por registrar as rotas do recurso Especialidades.
 *
 * Endpoints disponíveis:
 * - POST   /especialidades
 * - GET    /especialidades
 * - GET    /especialidades/count
 * - GET    /especialidades/{idEspecialidades}
 * - PUT    /especialidades/{idEspecialidades}
 * - DELETE /especialidades/{idEspecialidades}
 */
class EspecialidadesRouter
{
    /**
     * Instância da aplicação Slim.
     *
     * @var App
     */
    private App $app;

    /**
     * Recebe a instância principal da aplicação.
     *
     * @param App $app Aplicação Slim.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Registra todas as rotas relacionadas ao recurso Especialidade.
     *
     * Estrutura esperada do JSON:
     *
     * {
     *   "especialidades": {
     *     "nome": "Musica"
     *   }
     * }
     *
     * @return void
     */
    public function setupRoutes(): void
    {
        /**
         * =========================================================
         * POST /especialidades
         * =========================================================
         */
        $this->app->post(
            '/especialidades',
            [EspecialidadesController::class, 'createController']
        )
            ->add(ValidateEspecialidadesBody::class);

        /**
         * =========================================================
         * GET /especialidades
         * =========================================================
         */
        $this->app->get(
            '/especialidades',
            [EspecialidadesController::class, 'findAllController']
        );

        /**
         * =========================================================
         * GET /especialidades/count
         * =========================================================
         */
        $this->app->get(
            '/especialidades/count',
            [EspecialidadesController::class, 'countController']
        );

        /**
         * =========================================================
         * GET /especialidades/{idEspecialidades}
         * =========================================================
         */
        $this->app->get(
            '/especialidades/{idEspecialidades}',
            [EspecialidadesController::class, 'findByIdController']
        )
            ->add(ValidateEspecialidadesId::class);

        /**
         * =========================================================
         * PUT /especialidades/{idEspecialidades}
         * =========================================================
         */
        $this->app->put(
            '/especialidades/{idEspecialidades}',
            [EspecialidadesController::class, 'updateController']
        )
            ->add(ValidateEspecialidadesBody::class)
            ->add(ValidateEspecialidadesId::class);

        /**
         * =========================================================
         * DELETE /especialidades/{idEspecialidades}
         * =========================================================
         */
        $this->app->delete(
            '/especialidades/{idEspecialidades}',
            [EspecialidadesController::class, 'deleteController']
        )
            ->add(ValidateEspecialidadesId::class);
    }
}
