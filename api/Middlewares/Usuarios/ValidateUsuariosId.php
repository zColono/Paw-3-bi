<?php

namespace Api\Middlewares\Usuarios;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class ValidateUsuariosId implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $route = RouteContext::fromRequest($request)->getRoute();
        $args = $route ? $route->getArguments() : [];
        $idUsuario = $args['idUsuario'] ?? null;

        if (!is_numeric($idUsuario) || (int) $idUsuario <= 0) {
            throw new ErrorResponse(
                400,
                'Id invalido',
                ['message' => 'O parametro idUsuario deve ser um inteiro maior que zero']
            );
        }

        return $handler->handle($request);
    }
}
