<?php

namespace Api\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\AgendamentosService;

/**
 * Classe AgendamentosController
 *
 * Responsável pelos endpoints REST da entidade Agendamento.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class AgendamentosController
{
    /**
     * Serviço da entidade Campanha.
     *
     * @var AgendamentosService
     */
    private AgendamentosService $AgendamentosService;

    /**
     * Injeção de dependência.
     *
     * @param AgendamentosService $AgendamentosServiceDependency
     */
    public function __construct(AgendamentosService $AgendamentosServiceDependency)
    {
        error_log("⬆️ AgendamentosController::__construct()");
        $this->AgendamentosService = $AgendamentosServiceDependency;
    }

    /**
     * Cria nova agendamento.
     *
     * Endpoint:
     * POST /agendamentos
     *
     * JSON esperado:
     * {
     *   "agendamentos": {
     *      "usuarioId": 1,
     *      "campanhaId": 1,
     *      "dataAgendamento": "2026-01-01",
     *      "presenca": "CONFIRMADA"
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
        error_log("🔵 AgendamentosController::createController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $novoAgendamento = $this->AgendamentosService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'agendamentos' => [
                    [
                        'idAgendamentos' => $novoAgendamento->getIdAgendamentos(),
                        'usuarioId' => $novoAgendamento->getUsuarioId(),
                        'campanhaId' => $novoAgendamento->getCampanhaId(),
                        'dataAgendamento' => $novoAgendamento->getDataAgendamento(),
                        'presenca' => $novoAgendamento->getPresenca()
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
        error_log("🔵 AgendamentosController::findAllController()");

        $agendamentos = $this->AgendamentosService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'agendamentos' => $agendamentos
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AgendamentosController::updateController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $idAgendamento = (int)$args['idAgendamentos'];
        $agendamentoAtualizada = $this->AgendamentosService->updateService($idAgendamento, $objPHP);

        if ($agendamentoAtualizada) {
            $resposta = [
                'success' => true,
                'message' => 'Atualização realizada com sucesso',
                'data' => [
                    'agendamentos' => [
                        [
                            'idAgendamentos' => $agendamentoAtualizada->getIdAgendamentos(),
                            'usuarioId' => $agendamentoAtualizada->getUsuarioId(),
                            'campanhaId' => $agendamentoAtualizada->getCampanhaId(),
                            'dataAgendamento' => $agendamentoAtualizada->getDataAgendamento(),
                            'presenca' => $agendamentoAtualizada->getPresenca()
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
                'message' => 'Agendamento não encontrada'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AgendamentosController::deleteController()");

        $idAgendamento = (int)$args['idAgendamentos'];
        $deletado = $this->AgendamentosService->deleteService($idAgendamento);

        if ($deletado) {
            $resposta = [
                'success' => true,
                'message' => 'Agendamento deletada com sucesso'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } else {
            $resposta = [
                'success' => false,
                'message' => 'Agendamento não encontrada'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 AgendamentosController::countController()");

        $total = $this->AgendamentosService->countService();

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