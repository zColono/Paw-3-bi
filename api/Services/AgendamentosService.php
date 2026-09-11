<?php

namespace Api\Services;

use Api\Models\Agendamentos;
use Api\Dao\AgendamentoDao;
use Api\Dao\UsuariosDao;
use Api\Dao\CampanhasDao;
use Api\Http\ErrorResponse;
use stdClass;

class AgendamentosService
{
    private AgendamentoDao $agendamentoDAO;
    private UsuariosDao $usuariosDAO;
    private CampanhasDao $campanhasDAO;

    public function __construct(
        AgendamentoDao $agendamentoDAODependency,
        UsuariosDao $usuariosDAODependency,
        CampanhasDao $campanhasDAODependency
    ) {
        error_log("⬆️ AgendamentosService::__construct()");
        $this->agendamentoDAO = $agendamentoDAODependency;
        $this->usuariosDAO = $usuariosDAODependency;
        $this->campanhasDAO = $campanhasDAODependency;
    }

    public function createService(stdClass $objPHP): Agendamentos
    {
        error_log("🟣 AgendamentosService::createService()");

        $usuarioId = $objPHP->agendamentos->usuarioId ?? null;
        $campanhaId = $objPHP->agendamentos->campanhaId ?? null;
        $dataAgendamento = $objPHP->agendamentos->dataAgendamento ?? null;
        $presenca = $objPHP->agendamentos->presenca ?? null;

        if (!$usuarioId || !$this->usuariosDAO->findById($usuarioId)) {
            throw new ErrorResponse(
                400,
                "Usuário não encontrado",
                ["message" => "O usuário com id {$usuarioId} não existe."]
            );
        }

        if (!$campanhaId || !$this->campanhasDAO->findById($campanhaId)) {
            throw new ErrorResponse(
                400,
                "Campanha não encontrado",
                ["message" => "O campanha com id {$campanhaId} não existe."]
            );
        }

        $agendamentosUsuario = $this->agendamentoDAO->findByField('usuario_id', $usuarioId);
        foreach ($agendamentosUsuario as $insc) {
            if ($insc->getCampanhaId() === $campanhaId) {
                throw new ErrorResponse(
                    400,
                    "Agendamento duplicada",
                    ["message" => "Usuário já inscrito neste campanha."]
                );
            }
        }

        $campanhasAgendamentos = $this->agendamentoDAO->findByField('campanha_id', $campanhaId);
        $campanha = $this->campanhasDAO->findById($campanhaId);
        if ($campanha && $campanha->getLimiteVagas() > 0 && count($campanhasAgendamentos) >= $campanha->getLimiteVagas()) {
            throw new ErrorResponse(
                400,
                "Campanha sem vagas",
                ["message" => "Não há vagas disponíveis neste campanha."]
            );
        }

        if (!$dataAgendamento) {
            $dataAgendamento = (new \DateTime())->format('Y-m-d');
        }

        $agendamento = new Agendamentos();
        $agendamento->setUsuarioId($usuarioId);
        $agendamento->setCampanhaId($campanhaId);
        $agendamento->setDataAgendamento($dataAgendamento);
        $agendamento->setPresenca($presenca);

        return $this->agendamentoDAO->create($agendamento);
    }

    public function countService(): int
    {
        error_log("🟣 AgendamentosService::countService()");
        return $this->agendamentoDAO->count();
    }

    public function findAllService(): array
    {
        error_log("🟣 AgendamentosService::findAllService()");
        return $this->agendamentoDAO->findAll();
    }

    public function findByIdService(int $idAgendamentos): ?Agendamentos
    {
        error_log("🟣 AgendamentosService::findByIdService()");
        return $this->agendamentoDAO->findById($idAgendamentos);
    }

    public function updateService(int $idAgendamentos, stdClass $objPHP): Agendamentos
    {
        error_log("🟣 AgendamentosService::updateService()");

        $agendamentoExistente = $this->agendamentoDAO->findById($idAgendamentos);

        if (!$agendamentoExistente) {
            throw new ErrorResponse(
                404,
                "Agendamento não encontrada",
                ["message" => "Não existe agendamento com id {$idAgendamentos}"]
            );
        }

        $usuarioId = $objPHP->agendamentos->usuarioId ?? null;
        $campanhaId = $objPHP->agendamentos->campanhaId ?? null;
        $dataAgendamento = $objPHP->agendamentos->dataAgendamento ?? null;
        $presenca = $objPHP->agendamentos->presenca ?? null;

        if (!$usuarioId || !$this->usuariosDAO->findById($usuarioId)) {
            throw new ErrorResponse(
                400,
                "Usuário não encontrado",
                ["message" => "O usuário com id {$usuarioId} não existe."]
            );
        }

        if (!$campanhaId || !$this->campanhasDAO->findById($campanhaId)) {
            throw new ErrorResponse(
                400,
                "Campanha não encontrado",
                ["message" => "O campanha com id {$campanhaId} não existe."]
            );
        }

        $agendamentosUsuario = $this->agendamentoDAO->findByField('usuario_id', $usuarioId);
        foreach ($agendamentosUsuario as $insc) {
            if ($insc->getCampanhaId() === $campanhaId && $insc->getIdAgendamentos() !== $idAgendamentos) {
                throw new ErrorResponse(
                    400,
                    "Agendamento duplicada",
                    ["message" => "Usuário já inscrito neste campanha."]
                );
            }
        }

        $campanha = $this->campanhasDAO->findById($campanhaId);
        $campanhasAgendamentos = $this->agendamentoDAO->findByField('campanha_id', $campanhaId);
        if ($campanha && $campanha->getLimiteVagas() > 0 && $agendamentoExistente->getCampanhaId() !== $campanhaId && count($campanhasAgendamentos) >= $campanha->getLimiteVagas()) {
            throw new ErrorResponse(
                400,
                "Campanha sem vagas",
                ["message" => "Não há vagas disponíveis neste campanha."]
            );
        }

        if (!$dataAgendamento) {
            $dataAgendamento = (new \DateTime())->format('Y-m-d');
        }

        $agendamentoExistente->setUsuarioId($usuarioId);
        $agendamentoExistente->setCampanhaId($campanhaId);
        $agendamentoExistente->setDataAgendamento($dataAgendamento);
        $agendamentoExistente->setPresenca($presenca);

        $this->agendamentoDAO->update($agendamentoExistente);
        return $agendamentoExistente;
    }

    public function deleteService(int $idAgendamentos): bool
    {
        error_log("🟣 AgendamentosService::deleteService()");

        $agendamentoExistente = $this->agendamentoDAO->findById($idAgendamentos);

        if (!$agendamentoExistente) {
            throw new ErrorResponse(
                404,
                "Agendamento não encontrada",
                ["message" => "Não existe agendamento com id {$idAgendamentos}"]
            );
        }

        return $this->agendamentoDAO->delete($agendamentoExistente);
    }
}
