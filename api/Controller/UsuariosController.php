<?php

namespace Api\Controller;

use Api\Http\ErrorResponse;
use Api\Services\UsuariosService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsuariosController
{
    private UsuariosService $usuariosService;

    public function __construct(UsuariosService $usuariosServiceDependency)
    {
        error_log("UsuariosController::__construct()");
        $this->usuariosService = $usuariosServiceDependency;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuariosController::createController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $novoUsuario = $this->usuariosService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'usuarios' => [
                    [
                        'idUsuario' => $novoUsuario->getIdUsuario(),
                        'nomeUsuario' => $novoUsuario->getNomeUsuario(),
                        'email' => $novoUsuario->getEmail(),
                        'tel' => $novoUsuario->getTel(),
                        'tipoUsuario' => $novoUsuario->getTipoUsuario(),
                        'data_nasc' => $novoUsuario->getDataNasc()
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuariosController::findAllController()");

        $usuarios = $this->usuariosService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'usuarios' => $usuarios
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuariosController::findByIdController()");

        $idUsuario = (int) $args['idUsuario'];
        $usuario = $this->usuariosService->findByIdService($idUsuario);

        if (!$usuario) {
            throw new ErrorResponse(
                404,
                'Usuario nao encontrado',
                ['message' => "Nao existe usuario com id {$idUsuario}"]
            );
        }

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'usuarios' => [
                    $usuario
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuariosController::updateController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $idUsuario = (int) $args['idUsuario'];

        $usuarioAtualizado = $this->usuariosService->updateService($idUsuario, $objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Atualizacao realizada com sucesso',
            'data' => [
                'usuarios' => [
                    [
                        'idUsuario' => $usuarioAtualizado->getIdUsuario(),
                        'nomeUsuario' => $usuarioAtualizado->getNomeUsuario(),
                        'email' => $usuarioAtualizado->getEmail(),
                        'tel' => $usuarioAtualizado->getTel(),
                        'tipoUsuario' => $usuarioAtualizado->getTipoUsuario(),
                        'data_nasc' => $usuarioAtualizado->getDataNasc()
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuariosController::deleteController()");

        $idUsuario = (int) $args['idUsuario'];
        $deletado = $this->usuariosService->deleteService($idUsuario);

        $resposta = [
            'success' => $deletado,
            'message' => 'Usuário excluído com sucesso'
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuariosController::countController()");

        $total = $this->usuariosService->countService();

        $resposta = [
            'success' => true,
            'data' => [
                'count' => $total
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
