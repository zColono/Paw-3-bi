<?php

namespace Api\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\EspecialidadesService;

/**
 * Classe EspecialidadesController
 *
 * Responsável pelos endpoints REST da entidade Especialidade.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class EspecialidadesController
{
    /**
     * Serviço da entidade Usuario.
     *
     * @var EspecialidadesService
     */
    private EspecialidadesService $especialidadesService;

    /**
     * Injeção de dependência.
     *
     * @param EspecialidadesService $especialidadesServiceDependency
     */
    public function __construct(EspecialidadesService $especialidadesServiceDependency)
    {
        error_log("⬆️ EspecialidadesController::__construct()");
        $this->especialidadesService = $especialidadesServiceDependency;
    }

    /**
     * Cria novo especialidade.
     *
     * Endpoint:
     * POST /especialidades
     *
     * JSON esperado:
     * {
     *   "especialidades": {
     *      "nome": "Música"
     *   }
     * }
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 EspecialidadesController::createController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $novoEspecialidade = $this->especialidadesService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'especialidades' => [
                    [
                        'idEspecialidades' => $novoEspecialidade->getIdEspecialidades(),
                        'nome' => $novoEspecialidade->getNome(),
                        'descricao' => $novoEspecialidade->getDescricao(),
                        'diaAtendimento' => $novoEspecialidade->getDiaAtendimento(),
                        'idResponsavel' => $novoEspecialidade->getIdResponsavel()
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
        error_log("🔵 EspecialidadesController::findAllController()");

        $especialidades = $this->especialidadesService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'especialidades' => $especialidades
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 EspecialidadesController::findByIdController()");

        $idEspecialidade = (int)$args['idEspecialidades'];
        $especialidade = $this->especialidadesService->findByIdService($idEspecialidade);

        if ($especialidade) {
            $resposta = [
                'success' => true,
                'message' => 'Busca realizada com sucesso',
                'data' => [
                    'especialidades' => [
                        [
                            'idEspecialidades' => $especialidade->getIdEspecialidades(),
                            'nome' => $especialidade->getNome(),
                            'descricao' => $especialidade->getDescricao(),
                            'diaAtendimento' => $especialidade->getDiaAtendimento(),
                            'idResponsavel' => $especialidade->getIdResponsavel()
                        ]
                    ]
                ]
            ];
            $status = 200;
        } else {
            $resposta = [
                'success' => false,
                'message' => 'Especialidade não encontrado'
            ];
            $status = 404;
        }

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 EspecialidadesController::updateController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $idEspecialidade = (int)$args['idEspecialidades'];
        $especialidadeAtualizado = $this->especialidadesService->updateService($idEspecialidade, $objPHP);

        if ($especialidadeAtualizado) {
            $resposta = [
                'success' => true,
                'message' => 'Atualização realizada com sucesso',
                'data' => [
                    'especialidades' => [
                        [
                            'idEspecialidades' => $especialidadeAtualizado->getIdEspecialidades(),
                            'nome' => $especialidadeAtualizado->getNome(),
                            'descricao' => $especialidadeAtualizado->getDescricao(),
                            'diaAtendimento' => $especialidadeAtualizado->getDiaAtendimento(),
                            'idResponsavel' => $especialidadeAtualizado->getIdResponsavel()
                        ]
                    ]
                ]
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } else {
            $resposta = [
                'success' => false,
                'message' => 'Especialidade não encontrado'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 EspecialidadesController::deleteController()");

        $idEspecialidade = (int)$args['idEspecialidades'];
        $deletado = $this->especialidadesService->deleteService($idEspecialidade);

        if ($deletado) {
            $resposta = [
                'success' => true,
                'message' => 'Especialidade deletado com sucesso'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } else {
            $resposta = [
                'success' => false,
                'message' => 'Especialidade não encontrado'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 EspecialidadesController::countController()");

        $total = $this->especialidadesService->countService();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
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