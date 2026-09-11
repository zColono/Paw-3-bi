<?php

namespace Api\Router;

use Slim\App;
use Api\Controller\AuthController;

/**
 * Classe responsável por registrar a rota de autenticação.
 *
 * Endpoint disponível:
 * - POST /login
 */
class AuthRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setupRoutes(): void
    {
        /**
         * =========================================================
         * POST /login
         * =========================================================
         * Autentica o usuário e retorna um token JWT.
         * Esta é a única rota da API que não exige token (ver
         * Api\Middlewares\Auth\ValidateJwtToken).
         */
        $this->app->post(
            '/login',
            [AuthController::class, 'loginController']
        );
    }
}
