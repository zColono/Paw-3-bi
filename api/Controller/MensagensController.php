<?php

namespace Api\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\MensagensService;

/**
 * Classe MensagensController
 *
 * Responsável pelos endpoints REST da entidade Mensagem.
 *
 * PADRÃO:
 * - Assinaturas em uma linha
 * - JSON convertido para stdClass
 * - Controller delega regras para Service
 */
class MensagensController
{
    /**
     * Serviço da entidade Campanha.
     *
     * @var MensagensService
     */
    private MensagensService $MensagensService;

    /**
     * Injeção de dependência.
     *
     * @param MensagensService $MensagensServiceDependency
     */
    public function __construct(MensagensService $MensagensServiceDependency)
    {
        error_log("⬆️ MensagensController::__construct()");
        $this->MensagensService = $MensagensServiceDependency;
    }

    /**
     * Cria nova mensagem.
     *
     * Endpoint:
     * POST /mensagens
     *
     * JSON esperado:
     * {
     *   "mensagens": {
     *      "titulo": "Aviso",
     *      "conteudo": "Texto",
     *      "usuarioId": 1,
     *      "dataPostagem": "2026-05-18"
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
        error_log("🔵 MensagensController::createController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $novoMensagem = $this->MensagensService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'mensagens' => [
                    [
                        'idMensagens' => $novoMensagem->getIdMensagens(),
                        'titulo' => $novoMensagem->getTitulo(),
                        'conteudo' => $novoMensagem->getConteudo(),
                        'usuarioId' => $novoMensagem->getUsuarioId(),
                        'dataPostagem' => $novoMensagem->getDataPostagem()
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
        error_log("🔵 MensagensController::findAllController()");

        $mensagens = $this->MensagensService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'mensagens' => $mensagens
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MensagensController::updateController()");

        $objPHP = json_decode(json_encode($request->getParsedBody()));

        $idMensagem = (int)$args['idMensagens'];
        $mensagemAtualizada = $this->MensagensService->updateService($idMensagem, $objPHP);

        if ($mensagemAtualizada) {
            $resposta = [
                'success' => true,
                'message' => 'Atualização realizada com sucesso',
                'data' => [
                    'mensagens' => [
                        [
                            'idMensagens' => $mensagemAtualizada->getIdMensagens(),
                            'titulo' => $mensagemAtualizada->getTitulo(),
                            'conteudo' => $mensagemAtualizada->getConteudo(),
                            'usuarioId' => $mensagemAtualizada->getUsuarioId(),
                            'dataPostagem' => $mensagemAtualizada->getDataPostagem()
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
                'message' => 'Mensagem não encontrada'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MensagensController::deleteController()");

        $idMensagem = (int)$args['idMensagens'];
        $deletado = $this->MensagensService->deleteService($idMensagem);

        if ($deletado) {
            $resposta = [
                'success' => true,
                'message' => 'Mensagem deletada com sucesso'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } else {
            $resposta = [
                'success' => false,
                'message' => 'Mensagem não encontrada'
            ];

            $response->getBody()->write(json_encode($resposta));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("🔵 MensagensController::countController()");

        $total = $this->MensagensService->countService();

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