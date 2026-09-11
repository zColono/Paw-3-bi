<?php

namespace Api\Services;

use Api\Models\Especialidades;
use Api\Dao\EspecialidadeDao;
use Api\Dao\UsuariosDao;
use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Especialidades.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class EspecialidadesService
{
    /**
     * DAO responsável pelo acesso aos dados.
     *
     * @var EspecialidadeDao
     */
    private EspecialidadeDao $especialidadeDAO;

    /**
     * DAO responsável pela validação de usuários.
     *
     * @var UsuariosDao
     */
    private UsuariosDao $usuariosDAO;

    /**
     * Injeção de dependência.
     *
     * @param EspecialidadeDao $especialidadeDAODependency
     * @param UsuariosDao $usuariosDAODependency
     */
    public function __construct(EspecialidadeDao $especialidadeDAODependency, UsuariosDao $usuariosDAODependency)
    {
        error_log("⬆️ EspecialidadesService::__construct()");
        $this->especialidadeDAO = $especialidadeDAODependency;
        $this->usuariosDAO = $usuariosDAODependency;
    }

    /**
     * Valida se o responsavel existe no sistema.
     *
     * @param int $idResponsavel
     * @throws ErrorResponse
     */
        //dominio
    private function validateCoordinator(int $idResponsavel): void
    {
        if (!$this->usuariosDAO->findById($idResponsavel)) {
            throw new ErrorResponse(
                400,
                "Responsavel não encontrado",
                [
                    "message" => "O usuário com id {$idResponsavel} não existe. Verifique o id do responsavel."
                ]
            );
        }
    }

    /**
     * Cria um novo especialidade.
     *
     * @param stdClass $objPHP
     * @return Especialidades
     * @throws ErrorResponse
     */
    public function createService(stdClass $objPHP): Especialidades
    {
        error_log("🟣 EspecialidadesService::createService()");

        /**
         * Valida se o responsavel existe.
         */
        $this->validateCoordinator((int)$objPHP->especialidades->idResponsavel);

        $especialidade = new Especialidades();

        $especialidade->setNome($objPHP->especialidades->nome);
        $especialidade->setDescricao($objPHP->especialidades->descricao ?? null);
        $especialidade->setDiaAtendimento($objPHP->especialidades->diaAtendimento);
        $especialidade->setIdResponsavel($objPHP->especialidades->idResponsavel);

        /**
         * Verifica duplicidade de nome.
         */
        $resultado = $this->especialidadeDAO->findByField(
            'nome',
            $especialidade->getNome()
        );

        if (count($resultado) > 0) {
            throw new ErrorResponse(
                400,
                "Especialidade já existe",
                [
                    "message" =>
                        "O especialidade {$especialidade->getNome()} já está cadastrado"
                ]
            );
        }

        return $this->especialidadeDAO->create($especialidade);
    }

    /**
     * Retorna quantidade total.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 EspecialidadesService::countService()");
        return $this->especialidadeDAO->count();
    }

    /**
     * Lista todos os especialidades.
     *
     * @return array
     */
    public function findAllService(): array
    {
        error_log("🟣 EspecialidadesService::findAllService()");
        return $this->especialidadeDAO->findAll();
    }

    /**
     * Busca especialidade por ID.
     *
     * @param int $idEspecialidade
     * @return Especialidades|null
     */
    public function findByIdService(int $idEspecialidade): ?Especialidades
    {
        error_log("🟣 EspecialidadesService::findByIdService()");

        return $this->especialidadeDAO->findById($idEspecialidade);
    }

    /**
     * Atualiza especialidade existente.
     *
     * @param int $idEspecialidade
     * @param stdClass $objPHP
     * @return Especialidades
     * @throws ErrorResponse
     */
    public function updateService(int $idEspecialidade, stdClass $objPHP): Especialidades
    {
        error_log("🟣 EspecialidadesService::updateService()");

        /**
         * Valida se o responsavel existe.
         */
        $this->validateCoordinator((int)$objPHP->especialidades->idResponsavel);

        /**
         * Verifica existência.
         */
        $especialidadeExistente = $this->especialidadeDAO->findById($idEspecialidade);

        if (!$especialidadeExistente) {
            throw new ErrorResponse(
                404,
                "Especialidade não encontrado",
                [
                    "message" =>
                        "Não existe especialidade com id {$idEspecialidade}"
                ]
            );
        }

        /**
         * Atualiza dados.
         */
        $especialidadeExistente->setNome($objPHP->especialidades->nome);
        $especialidadeExistente->setDescricao($objPHP->especialidades->descricao ?? null);
        $especialidadeExistente->setDiaAtendimento($objPHP->especialidades->diaAtendimento);
        $especialidadeExistente->setIdResponsavel($objPHP->especialidades->idResponsavel);

        $this->especialidadeDAO->update($especialidadeExistente);

        return $especialidadeExistente;
    }

    /**
     * Remove especialidade existente.
     *
     * @param int $idEspecialidade
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(int $idEspecialidade): bool
    {
        error_log("🟣 EspecialidadesService::deleteService()");

        /**
         * Verifica existência.
         */
        $especialidadeExistente = $this->especialidadeDAO->findById($idEspecialidade);

        if (!$especialidadeExistente) {
            throw new ErrorResponse(
                404,
                "Especialidade não encontrado",
                [
                    "message" =>
                        "Não existe especialidade com id {$idEspecialidade}"
                ]
            );
        }

        return $this->especialidadeDAO->delete($especialidadeExistente);
    }
}
