<?php

namespace Api\Controller;

use Api\Auth\JwtConfig;
use Api\Dao\UsuariosDao;
use Api\Http\ErrorResponse;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller responsável pela autenticação dos usuários.
 *
 * Fluxo:
 * POST /login -> valida email/senha -> gera token JWT -> retorna token
 */
class AuthController
{
    private UsuariosDao $usuariosDao;

    public function __construct(UsuariosDao $usuariosDaoDependency)
    {
        error_log("AuthController::__construct()");
        $this->usuariosDao = $usuariosDaoDependency;
    }

    /**
     * Realiza o login do usuário.
     *
     * Corpo esperado:
     * {
     *   "email": "joao@email.com",
     *   "senha": "123456"
     * }
     */
    public function loginController(Request $request, Response $response, array $args): Response
    {
        error_log("AuthController::loginController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $email = trim($objPHP->email ?? '');
        $senha = trim($objPHP->senha ?? '');

        if ($email === '' || $senha === '') {
            throw new ErrorResponse(
                400,
                'Dados inválidos',
                ['message' => 'Informe email e senha para realizar o login']
            );
        }

        $resultado = $this->usuariosDao->findByField('email', $email);

        if (empty($resultado)) {
            throw new ErrorResponse(
                401,
                'Credenciais inválidas',
                ['message' => 'Email ou senha incorretos']
            );
        }

        $usuario = $resultado[0];

        // Comparação simples de senha (o projeto do 2º bimestre não faz hash).
        if ($usuario->getSenha() !== $senha) {
            throw new ErrorResponse(
                401,
                'Credenciais inválidas',
                ['message' => 'Email ou senha incorretos']
            );
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + JwtConfig::EXPIRATION,
            'idUsuario' => $usuario->getIdUsuario(),
            'nomeUsuario' => $usuario->getNomeUsuario(),
            'email' => $usuario->getEmail(),
            'tipoUsuario' => $usuario->getTipoUsuario()
        ];

        $token = JWT::encode($payload, JwtConfig::SECRET_KEY, JwtConfig::ALGORITHM);

        $resposta = [
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'token' => $token,
                'usuario' => [
                    'idUsuario' => $usuario->getIdUsuario(),
                    'nomeUsuario' => $usuario->getNomeUsuario(),
                    'email' => $usuario->getEmail(),
                    'tipoUsuario' => $usuario->getTipoUsuario()
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
