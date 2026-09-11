<?php

namespace Api\Server;

// Importações das classes necessárias
use Slim\App;
use Psr\Http\Message\ServerRequestInterface;
use Api\Http\ErrorResponse;

use Api\Router\AuthRouter;
use Api\Router\UsuariosRouter;
use Api\Router\EspecialidadesRouter;
use Api\Router\CampanhasRouter;
use Api\Router\AgendamentosRouter;
use Api\Router\MensagensRouter;
use Api\Middlewares\Auth\ValidateJwtToken;

/**
 * Classe Server - Responsável por configurar e executar o servidor HTTP
 */
class Server
{
    /**
     * Instância da aplicação Slim
     * @var App
     */
    private App $app;

    /**
     * Router responsável pela rota de login/autenticação
     * @var AuthRouter
     */
    private AuthRouter $authRouter;

    /**
     * Router responsável pelas rotas de usuários
     * @var UsuariosRouter
     */
    private UsuariosRouter $usuariosRouter;

    /**
     * Router responsável pelas rotas de especialidades
     * @var EspecialidadesRouter
     */
    private EspecialidadesRouter $especialidadesRouter;

    /**
     * Router responsável pelas rotas de campanhas
     * @var CampanhasRouter
     */
    private CampanhasRouter $campanhasRouter;

    /**
     * Router responsável pelas rotas de agendamentos
     * @var AgendamentosRouter
     */
    private AgendamentosRouter $agendamentosRouter;

    /**
     * Router responsável pelas rotas de mensagens
     * @var MensagensRouter
     */
    private MensagensRouter $mensagensRouter;

    /**
     * Construtor
     */
    public function __construct(
        App $app,
        AuthRouter $authRouter,
        UsuariosRouter $usuariosRouter,
        EspecialidadesRouter $especialidadesRouter,
        CampanhasRouter $campanhasRouter,
        AgendamentosRouter $agendamentosRouter,
        MensagensRouter $mensagensRouter
    ) {

        $this->app = $app;

        $this->authRouter = $authRouter;
        $this->usuariosRouter = $usuariosRouter;
        $this->especialidadesRouter = $especialidadesRouter;
        $this->campanhasRouter = $campanhasRouter;
        $this->agendamentosRouter = $agendamentosRouter;
        $this->mensagensRouter = $mensagensRouter;

        $this->setupMiddlewares();
        $this->setupRoutes();
        $this->setupErrorHandling();
    }

    /**
     * Configuração dos middlewares
     */
    private function setupMiddlewares(): void
    {

        $this->app->addBodyParsingMiddleware();

        /**
         * Exige um token JWT válido (header Authorization: Bearer <token>)
         * para o acesso a qualquer recurso da API, exceto POST /login.
         */
        $this->app->add(ValidateJwtToken::class);

        $this->app->add(function ($request, $handler) {

            $response = $handler->handle($request);

            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader(
                    'Access-Control-Allow-Methods',
                    'GET, POST, PUT, DELETE, OPTIONS'
                )
                ->withHeader(
                    'Access-Control-Allow-Headers',
                    'Content-Type, Authorization'
                );
        });
    }

    /**
     * Configuração das rotas
     */
    private function setupRoutes(): void
    {

        /**
         * =========================
         * ROTAS
         * =========================
         */

        $this->authRouter->setupRoutes();
        $this->usuariosRouter->setupRoutes();
        $this->especialidadesRouter->setupRoutes();
        $this->campanhasRouter->setupRoutes();
        $this->agendamentosRouter->setupRoutes();
        $this->mensagensRouter->setupRoutes();

        /**
         * =========================
         * ROTA RAIZ
         * =========================
         */

        $this->app->get('/', function ($request, $response) {

            return $response
                ->withHeader('Location', '/login.html')
                ->withStatus(302);
        });
    }

    /**
     * Tratamento global de erros
     */
    private function setupErrorHandling(): void
    {

        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);

        $errorMiddleware->setDefaultErrorHandler(
            function (
                ServerRequestInterface $request,
                \Throwable $exception
            ) {

                $response = new \Slim\Psr7\Response();

                $status = 500;

                /**
                 * ERROS PERSONALIZADOS
                 */
                if ($exception instanceof ErrorResponse) {

                    $payload = [
                        'success' => false,
                        'message' => $exception->getMessage(),
                        'error' => $exception->getError() ?? (object) [],
                    ];

                    $status = $exception->getHttpCode();
                }

                /**
                 * ERROS GERAIS
                 */
                else {

                    $payload = [
                        'success' => false,
                        'message' => $exception->getMessage(),
                        'error' => [
                            'code' => $exception->getCode(),
                            'stack' => $exception->getTrace(),
                            'file' => $exception->getFile(),
                            'line' => $exception->getLine(),
                        ],
                    ];
                }

                $response->getBody()->write(
                    json_encode(
                        $payload,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    )
                );

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader(
                        'Access-Control-Allow-Methods',
                        'GET, POST, PUT, DELETE, OPTIONS'
                    )
                    ->withHeader(
                        'Access-Control-Allow-Headers',
                        'Content-Type, Authorization'
                    )
                    ->withStatus($status);
            }
        );
    }

    /**
     * Executa o servidor
     */
    public function run(): void
    {

        $this->app->run();
    }
}
