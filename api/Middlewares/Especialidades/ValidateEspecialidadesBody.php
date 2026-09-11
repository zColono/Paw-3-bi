<?php

namespace Api\Middlewares\Especialidades;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateEspecialidadesBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (!is_array($body) || !isset($body['especialidades']) || !is_array($body['especialidades'])) {
            throw new ErrorResponse(
                400,
                'Body invalido',
                ['message' => 'O JSON deve conter o objeto especialidades']
            );
        }

        $especialidades = $body['especialidades'];

        if (empty(trim((string) ($especialidades['nome'] ?? '')))) {
            throw new ErrorResponse(
                400,
                'Nome obrigatorio',
                ['message' => 'O campo especialidades.nome e obrigatorio']
            );
        }


        return $handler->handle($request);
    }
}
