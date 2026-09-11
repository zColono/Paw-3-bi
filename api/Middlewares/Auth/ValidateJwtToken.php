<?php

namespace Api\Middlewares\Auth;

use Api\Auth\JwtConfig;
use Api\Http\ErrorResponse;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * Middleware global responsável por exigir um token JWT válido para
 * o acesso a qualquer recurso da API.
 *
 * Regras:
 * - Requisições OPTIONS (preflight de CORS) são sempre liberadas.
 * - A rota POST /login é pública (é ela quem gera o token).
 * - Todas as demais rotas exigem o header:
 *       Authorization: Bearer <token>
 */
class ValidateJwtToken
{
    /**
     * Rotas que não exigem autenticação.
     *
     * @var string[]
     */
    private const ROTAS_PUBLICAS = [
        '/login',
        '/'
    ];

    public function __invoke(Request $request, Handler $handler): Response
    {
        $metodo = $request->getMethod();
        $caminho = rtrim($request->getUri()->getPath(), '/');
        $caminho = $caminho === '' ? '/' : $caminho;

        if ($metodo === 'OPTIONS') {
            return $handler->handle($request);
        }

        if (in_array($caminho, self::ROTAS_PUBLICAS, true)) {
            return $handler->handle($request);
        }

        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            throw new ErrorResponse(
                401,
                'Token não fornecido',
                ['message' => 'Informe o token JWT no header Authorization: Bearer <token>']
            );
        }

        try {
            $decoded = JWT::decode(
                $matches[1],
                new Key(JwtConfig::SECRET_KEY, JwtConfig::ALGORITHM)
            );
        } catch (\Exception $e) {
            throw new ErrorResponse(
                401,
                'Token inválido ou expirado',
                ['message' => $e->getMessage()]
            );
        }

        /**
         * Disponibiliza os dados do usuário logado para os
         * controllers/services que quiserem consultar (ex: idUsuario
         * de quem está fazendo a requisição).
         */
        $request = $request->withAttribute('usuarioLogado', $decoded);

        return $handler->handle($request);
    }
}
