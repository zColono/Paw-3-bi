<?php

namespace Api\Middlewares\Mensagens;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateMensagensBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (!is_array($body) || !isset($body['mensagens']) || !is_array($body['mensagens'])) {
            throw new ErrorResponse(
                400,
                'Body invalido',
                ['message' => 'O JSON deve conter o objeto mensagens']
            );
        }

        $msg = $body['mensagens'];
        $requiredFields = ['titulo', 'conteudo', 'usuarioId', 'dataPostagem'];

        foreach ($requiredFields as $field) {
            if (empty(trim((string) ($msg[$field] ?? '')))) {
                throw new ErrorResponse(
                    400,
                    'Campo obrigatorio',
                    ['message' => "O campo mensagens.{$field} e obrigatorio"]
                );
            }
        }

        return $handler->handle($request);
    }
}
