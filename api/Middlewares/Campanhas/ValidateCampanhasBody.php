<?php

namespace Api\Middlewares\Campanhas;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateCampanhasBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (!is_array($body) || !isset($body['campanhas']) || !is_array($body['campanhas'])) {
            throw new ErrorResponse(
                400,
                'Body invalido',
                ['message' => 'O JSON deve conter o objeto campanhas']
            );
        }

        $campanhas = $body['campanhas'];
        $requiredFields = ['titulo', 'descricao', 'dataCampanha', 'localCampanha', 'limiteVagas', 'statusCampanha'];

        foreach ($requiredFields as $field) {
            if (empty(trim((string) ($campanhas[$field] ?? '')))) {
                throw new ErrorResponse(
                    400,
                    'Campo obrigatorio',
                    ['message' => "O campo campanhas.{$field} e obrigatorio"]
                );
            }
        }

        return $handler->handle($request);
    }
}
