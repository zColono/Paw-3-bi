<?php

namespace Api\Middlewares\Usuarios;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateUsuariosBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (!is_array($body) || !isset($body['usuarios']) || !is_array($body['usuarios'])) {
            throw new ErrorResponse(
                400,
                'Body invalido',
                ['message' => 'O JSON deve conter o objeto usuarios']
            );
        }

        $usuarios = $body['usuarios'];

        if (empty(trim((string) ($usuarios['nomeUsuario'] ?? '')))) {
            throw new ErrorResponse(
                400,
                'Nome obrigatorio',
                ['message' => 'O campo usuarios.nomeUsuario e obrigatorio']
            );
        }

        if (empty(trim((string) ($usuarios['email'] ?? '')))) {
            throw new ErrorResponse(
                400,
                'Email obrigatorio',
                ['message' => 'O campo usuarios.email e obrigatorio']
            );
        }

        if (empty(trim((string) ($usuarios['senha'] ?? '')))) {
            throw new ErrorResponse(
                400,
                'Senha obrigatoria',
                ['message' => 'O campo usuarios.senha e obrigatorio']
            );
        }

        return $handler->handle($request);
    }
}
