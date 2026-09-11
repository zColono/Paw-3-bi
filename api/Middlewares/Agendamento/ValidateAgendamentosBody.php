<?php

namespace Api\Middlewares\Agendamento;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateAgendamentosBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (!is_array($body) || !isset($body['agendamentos']) || !is_array($body['agendamentos'])) {
            throw new ErrorResponse(
                400,
                'Body invalido',
                ['message' => 'O JSON deve conter o objeto agendamentos']
            );
        }

        $insc = $body['agendamentos'];
        $requiredFields = ['usuarioId', 'campanhaId', 'dataAgendamento', 'presenca'];

        foreach ($requiredFields as $field) {
            if (empty(trim((string) ($insc[$field] ?? '')))) {
                throw new ErrorResponse(
                    400,
                    'Campo obrigatorio',
                    ['message' => "O campo agendamentos.{$field} e obrigatorio"]
                );
            }
        }

        return $handler->handle($request);
    }
}
