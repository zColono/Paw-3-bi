<?php

namespace Api\Services;

use Api\Models\Campanhas;
use Api\Dao\CampanhasDao;
use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Campanhas.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class CampanhasService
{
    private CampanhasDao $campanhasDAO;

    public function __construct(CampanhasDao $campanhasDAODependency)
    {
        error_log("⬆️ CampanhasService::__construct()");
        $this->campanhasDAO = $campanhasDAODependency;
    }

    public function createService(stdClass $objPHP): Campanhas
    {
        error_log("🟣 CampanhasService::createService()");

        $titulo = trim($objPHP->campanhas->titulo ?? '');
        $descricao = $objPHP->campanhas->descricao ?? null;
        $dataCampanhaStr = $objPHP->campanhas->dataCampanha ?? null;
        $localCampanha = $objPHP->campanhas->localCampanha ?? null;
        $limiteVagas = (int)($objPHP->campanhas->limiteVagas ?? 0);
        $statusCampanha = $objPHP->campanhas->statusCampanha ?? null;

        if ($titulo === '') {
            throw new ErrorResponse(400, "Título inválido", ["message" => "O título do campanha não pode ser vazio."]);
        }

        $existente = $this->campanhasDAO->findByField('titulo', $titulo);
        if (count($existente) > 0) {
            throw new ErrorResponse(400, "Campanha já existe", ["message" => "O campanha {$titulo} já está cadastrado"]);
        }

        if (!$dataCampanhaStr) {
            throw new ErrorResponse(400, "Data inválida", ["message" => "A data do campanha é obrigatória."]);
        }

        try {
            $dataCampanha = new \DateTime($dataCampanhaStr);
        } catch (\Exception $e) {
            throw new ErrorResponse(400, "Data inválida", ["message" => "Formato de data inválido."]);
        }

        $hoje = (new \DateTime())->setTime(0, 0, 0);
        $dataCampanha->setTime(0, 0, 0);
        if ($dataCampanha < $hoje) {
            throw new ErrorResponse(400, "Data inválida", ["message" => "A data do campanha não pode ser anterior à data atual."]);
        }

        if ($limiteVagas < 0) {
            throw new ErrorResponse(400, "Limite inválido", ["message" => "O limite de vagas deve ser maior ou igual a zero."]);
        }

        $campanha = new Campanhas();
        $campanha->setTitulo($titulo);
        $campanha->setDescricao($descricao);
        $campanha->setDataCampanha($dataCampanha->format('Y-m-d'));
        $campanha->setLocalCampanha($localCampanha);
        $campanha->setLimiteVagas($limiteVagas);
        $campanha->setStatusCampanha($statusCampanha);

        return $this->campanhasDAO->create($campanha);
    }

    public function countService(): int
    {
        error_log("🟣 CampanhasService::countService()");
        return $this->campanhasDAO->count();
    }

    public function findAllService(): array
    {
        error_log("🟣 CampanhasService::findAllService()");
        return $this->campanhasDAO->findAll();
    }

    public function findByIdService(int $idCampanha): ?Campanhas
    {
        error_log("🟣 CampanhasService::findByIdService()");
        return $this->campanhasDAO->findById($idCampanha);
    }

    public function updateService(int $idCampanha, stdClass $objPHP): Campanhas
    {
        error_log("🟣 CampanhasService::updateService()");

        $campanhaExistente = $this->campanhasDAO->findById($idCampanha);

        if (!$campanhaExistente) {
            throw new ErrorResponse(
                404,
                "Campanha não encontrado",
                ["message" => "Não existe campanha com id {$idCampanha}"]
            );
        }

        $titulo = trim($objPHP->campanhas->titulo ?? '');
        $descricao = $objPHP->campanhas->descricao ?? null;
        $dataCampanhaStr = $objPHP->campanhas->dataCampanha ?? null;
        $localCampanha = $objPHP->campanhas->localCampanha ?? null;
        $limiteVagas = (int)($objPHP->campanhas->limiteVagas ?? 0);
        $statusCampanha = $objPHP->campanhas->statusCampanha ?? null;

        if ($titulo === '') {
            throw new ErrorResponse(400, "Título inválido", ["message" => "O título do campanha não pode ser vazio."]);
        }

        $existente = $this->campanhasDAO->findByField('titulo', $titulo);
        foreach ($existente as $campanhaMesmoTitulo) {
            if ($campanhaMesmoTitulo->getIdCampanha() !== $idCampanha) {
                throw new ErrorResponse(400, "Campanha já existe", ["message" => "O campanha {$titulo} já está cadastrado"]);
            }
        }

        if (!$dataCampanhaStr) {
            throw new ErrorResponse(400, "Data inválida", ["message" => "A data do campanha é obrigatória."]);
        }

        try {
            $dataCampanha = new \DateTime($dataCampanhaStr);
        } catch (\Exception $e) {
            throw new ErrorResponse(400, "Data inválida", ["message" => "Formato de data inválido."]);
        }

        $hoje = (new \DateTime())->setTime(0, 0, 0);
        $dataCampanha->setTime(0, 0, 0);
        if ($dataCampanha < $hoje) {
            throw new ErrorResponse(400, "Data inválida", ["message" => "A data do campanha não pode ser anterior à data atual."]);
        }

        if ($limiteVagas < 0) {
            throw new ErrorResponse(400, "Limite inválido", ["message" => "O limite de vagas deve ser maior ou igual a zero."]);
        }

        $campanhaExistente->setTitulo($titulo);
        $campanhaExistente->setDescricao($descricao);
        $campanhaExistente->setDataCampanha($dataCampanha->format('Y-m-d'));
        $campanhaExistente->setLocalCampanha($localCampanha);
        $campanhaExistente->setLimiteVagas($limiteVagas);
        $campanhaExistente->setStatusCampanha($statusCampanha);

        $this->campanhasDAO->update($campanhaExistente);
        return $campanhaExistente;
    }

    public function deleteService(int $idCampanha): bool
    {
        error_log("🟣 CampanhasService::deleteService()");

        $campanhaExistente = $this->campanhasDAO->findById($idCampanha);

        if (!$campanhaExistente) {
            throw new ErrorResponse(
                404,
                "Campanha não encontrado",
                ["message" => "Não existe campanha com id {$idCampanha}"]
            );
        }

        return $this->campanhasDAO->delete($campanhaExistente);
    }
}
