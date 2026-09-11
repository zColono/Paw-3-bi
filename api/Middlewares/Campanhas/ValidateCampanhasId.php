<?php

namespace Api\Middlewares\Campanhas;

use Api\Http\ErrorResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class ValidateCampanhasId implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $route = RouteContext::fromRequest($request)->getRoute();
        $args = $route ? $route->getArguments() : [];
        $idCampanha = $args['idCampanha'] ?? null;

        if (!is_numeric($idCampanha) || (int) $idCampanha <= 0) {
            throw new ErrorResponse(
                400,
                'Id invalido',
                ['message' => 'O parametro idCampanha deve ser um inteiro maior que zero']
            );
        }

        return $handler->handle($request);
    }
}
