<?php

namespace Api\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\CampanhasService;

/**
 * Classe CampanhasController
 *
 * Responsável pelos endpoints REST da entidade Campanha.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class CampanhasController
{
    /**
     * Serviço da entidade Campanha.
     *
     * @var CampanhasService
     */
    private CampanhasService $CampanhasService;

    /**
     * Injeção de dependência.
     *
     * @param CampanhasService $CampanhasServiceDependency
     */
    public function __construct(CampanhasService $CampanhasServiceDependency)
    {
        error_log("⬆️ CampanhasController::__construct()");
        $this->CampanhasService = $CampanhasServiceDependency;
    }

    /**
     * Cria novo campanha.
     *
     * Endpoint:
     * POST /campanhas
     *
     * JSON esperado:
     * {
     *   "campanhas": {
     *      "titulo": "Retiro",
     *      "descricao": "Desc",
     *      "dataCampanha": "2026-07-01",
     *      "localCampanha": "Sítio",
     *      "limiteVagas": 50,
     *      "statusCampanha": "ABERTO"
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
        error_log("🔵 CampanhasController::createController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $novoCampanha = $this->CampanhasService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'campanhas' => [
                    [
                        'idCampanha' => $novoCampanha->getIdCampanha(),
                        'titulo' => $novoCampanha->getTitulo(),
                        'descricao' => $novoCampanha->getDescricao(),
                        'dataCampanha' => $novoCampanha->getDataCampanha(),
                        'localCampanha' => $novoCampanha->getLocalCampanha(),
                        'limiteVagas' => $novoCampanha->getLimiteVagas(),
                        'statusCampanha' => $novoCampanha->getStatusCampanha()
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
        error_log("🔵 CampanhasController::findAllController()");

        $campanhas = $this->CampanhasService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'campanhas' => $campanhas
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CampanhasController::updateController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $idCampanha = (int)$args['idCampanha'];
        $campanhaAtualizado = $this->CampanhasService->updateService($idCampanha, $objPHP);

        if ($campanhaAtualizado) {
            $resposta = [
                'success' => true,
                'message' => 'Atualização realizada com sucesso',
                'data' => [
                    'campanhas' => [
                        [
                            'idCampanha' => $campanhaAtualizado->getIdCampanha(),
                            'titulo' => $campanhaAtualizado->getTitulo(),
                            'descricao' => $campanhaAtualizado->getDescricao(),
                            'dataCampanha' => $campanhaAtualizado->getDataCampanha(),
                            'localCampanha' => $campanhaAtualizado->getLocalCampanha(),
                            'limiteVagas' => $campanhaAtualizado->getLimiteVagas(),
                            'statusCampanha' => $campanhaAtualizado->getStatusCampanha()
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
                'message' => 'Campanha não encontrado'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CampanhasController::deleteController()");

        $idCampanha = (int)$args['idCampanha'];
        $deletado = $this->CampanhasService->deleteService($idCampanha);

        if ($deletado) {
            $resposta = [
                'success' => true,
                'message' => 'Campanha deletado com sucesso'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } else {
            $resposta = [
                'success' => false,
                'message' => 'Campanha não encontrado'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 CampanhasController::countController()");

        $total = $this->CampanhasService->countService();

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