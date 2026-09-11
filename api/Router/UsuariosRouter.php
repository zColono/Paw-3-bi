<?php

namespace Api\Router;

use Slim\App;
use Api\Controller\UsuariosController;
use Api\Middlewares\Usuarios\ValidateUsuariosBody;
use Api\Middlewares\Usuarios\ValidateUsuariosId;

/**
 * Classe responsável por registrar as rotas do recurso Usuários.
 *
 * Endpoints disponíveis:
 * - POST   /usuarios
 * - GET    /usuarios
 * - GET    /usuarios/count
 * - GET    /usuarios/{idUsuario}
 * - PUT    /usuarios/{idUsuario}
 * - DELETE /usuarios/{idUsuario}
 */
class UsuariosRouter
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
     * Registra todas as rotas relacionadas ao recurso Usuário.
     *
     * Estrutura esperada do JSON:
     *
     * {
     *   "usuario": {
     *     "nome": "teste"
     *   }
     * }
     *
     * IMPORTANTE:
     * No Slim Framework, os middlewares executam em ordem inversa
     * à ordem em que são adicionados com ->add().
     *
     * O último middleware adicionado executa primeiro.
     *
     * @return void
     */
    public function setupRoutes(): void
    {
        /**
         * =========================================================
         * POST /usuarios
         * =========================================================
         * Cria um novo usuário.
         *
         * Body:
         * {
         *   "usuario": {
         *     "nome": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateUsuariosBody
         * 2. UsuariosController::createController
         */
        $this->app->post(
            '/usuarios',
            [UsuariosController::class, 'createController']
        )
            ->add(ValidateUsuariosBody::class);

        /**
         * =========================================================
         * GET /usuarios
         * =========================================================
         * Lista todos os usuários.
         *
         * Ordem de execução:
         * 1. UsuariosController::findAllController
         */
        $this->app->get(
            '/usuarios',
            [UsuariosController::class, 'findAllController']
        );

        /**
         * =========================================================
         * GET /usuarios/count
         * =========================================================
         * Retorna a quantidade total de usuários.
         *
         * Ordem de execução:
         * 1. UsuariosController::countController
         */
        $this->app->get(
            '/usuarios/count',
            [UsuariosController::class, 'countController']
        );

        /**
         * =========================================================
         * GET /usuarios/{idUsuario}
         * =========================================================
         * Busca um usuário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateUsuariosId
         * 2. UsuariosController::findByIdController
         */
        $this->app->get(
            '/usuarios/{idUsuario}',
            [UsuariosController::class, 'findByIdController']
        )
            ->add(ValidateUsuariosId::class);

        /**
         * =========================================================
         * PUT /usuarios/{idUsuario}
         * =========================================================
         * Atualiza um usuário existente.
         *
         * Body:
         * {
         *   "usuario": {
         *     "nome": "teste"
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateUsuariosId
         * 2. ValidateUsuariosBody
         * 3. UsuariosController::updateController
         */
        $this->app->put(
            '/usuarios/{idUsuario}',
            [UsuariosController::class, 'updateController']
        )
            ->add(ValidateUsuariosBody::class)
            ->add(ValidateUsuariosId::class);

        /**
         * =========================================================
         * DELETE /usuarios/{idUsuario}
         * =========================================================
         * Remove um usuário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateUsuariosId
         * 2. UsuariosController::deleteController
         */
        $this->app->delete(
            '/usuarios/{idUsuario}',
            [UsuariosController::class, 'deleteController']
        )
            ->add(ValidateUsuariosId::class);
    }
}
