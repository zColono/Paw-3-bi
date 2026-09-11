<?php

namespace Api\Middlewares\Mensagens;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class ValidateMensagensId implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $route = RouteContext::fromRequest($request)->getRoute();
        $args = $route ? $route->getArguments() : [];
        $idMensagens = $args['idMensagens'] ?? null;

        if (!is_numeric($idMensagens) || (int) $idMensagens <= 0) {
            throw new ErrorResponse(
                400,
                'Id invalido',
                ['message' => 'O parametro idMensagens deve ser um inteiro maior que zero']
            );
        }

        return $handler->handle($request);
    }
}
