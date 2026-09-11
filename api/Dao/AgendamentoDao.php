<?php

namespace Api\Dao;

use Api\Models\Agendamentos;
use Api\Database\MysqlDatabase;
use Exception;

/**
 * Classe responsável pelo acesso aos dados da entidade Agendamentos.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela agendamentos.
 */
class AgendamentoDao
{
    /**
     * Instância do banco.
     *
     * @var MysqlDatabase
     */
    private MysqlDatabase $database;

    /**
     * Construtor.
     *
     * @param MysqlDatabase $databaseInstance
     */
    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;

        error_log("⬆️ AgendamentoDao::__construct()");
    }

    /**
     * Cadastra agendamento.
     *
     * @param Agendamentos $objAgendamento
     * @return Agendamentos
     * @throws Exception
     */
    public function create(Agendamentos $objAgendamento): Agendamentos
    {
        error_log("🟢 AgendamentoDao::create()");

        $sql = "
            INSERT INTO agendamentos
            (
                usuario_id,
                campanha_id,
                data_agendamento,
                presenca
            )
            VALUES
            (
                :usuario_id,
                :campanha_id,
                :data_agendamento,
                :presenca
            )
        ";

        $parametros = [
            ':usuario_id' => $objAgendamento->getUsuarioId(),
            ':campanha_id' => $objAgendamento->getCampanhaId(),
            ':data_agendamento' => $objAgendamento->getDataAgendamento(),
            ':presenca' => $objAgendamento->getPresenca()
        ];

        //compila o sql antes de inserir os dados previnindo SQL Injection
        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar agendamento.");
        }

        $novoID = (int) $this->database->getConnection()->lastInsertId();

        $objAgendamento->setIdAgendamentos($novoID);

        return $objAgendamento;
    }

    /**
     * Remove agendamento.
     *
     * @param Agendamentos $objAgendamentoModel
     * @return bool
     */
    public function delete(Agendamentos $objAgendamentoModel): bool
    {
        error_log("🟢 AgendamentoDao::delete()");

        $sql = "
            DELETE FROM agendamentos
            WHERE id_agendamentos = :id_agendamentos
        ";

        $parametros = [
            ':id_agendamentos' => $objAgendamentoModel->getIdAgendamentos()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza agendamento.
     *
     * @param Agendamentos $objAgendamentoModel
     * @return bool
     */
    public function update(Agendamentos $objAgendamentoModel): bool
    {
        error_log("🟢 AgendamentoDao::update()");

        $sql = "
            UPDATE agendamentos
            SET
                usuario_id = :usuario_id,
                campanha_id = :campanha_id,
                data_agendamento = :data_agendamento,
                presenca = :presenca
            WHERE id_agendamentos = :id_agendamentos
        ";

        $parametros = [
            ':usuario_id' => $objAgendamentoModel->getUsuarioId(),
            ':campanha_id' => $objAgendamentoModel->getCampanhaId(),
            ':data_agendamento' => $objAgendamentoModel->getDataAgendamento(),
            ':presenca' => $objAgendamentoModel->getPresenca(),
            ':id_agendamentos' => $objAgendamentoModel->getIdAgendamentos()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    /**
     * Lista todas as agendamentos.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 AgendamentoDao::findAll()");

        $sql = "SELECT * FROM agendamentos";

        $stmt = $this->database->getConnection()->query($sql);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $agendamentos = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $agendamento = new Agendamentos();

            $agendamento->setIdAgendamentos((int) $linhaMatriz['id_agendamentos']);
            $agendamento->setUsuarioId((int) $linhaMatriz['usuario_id']);
            $agendamento->setCampanhaId((int) $linhaMatriz['campanha_id']);
            $agendamento->setDataAgendamento($linhaMatriz['data_agendamento']);
            $agendamento->setPresenca($linhaMatriz['presenca']);

            $agendamentos[] = $agendamento;
        }

        return $agendamentos;
    }

    /**
     * Conta agendamentos.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 AgendamentoDao::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM agendamentos";

        $stmt = $this->database->getConnection()->query($sql);

        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $linhaMatriz['qtd'];
    }

    /**
     * Busca agendamento por ID.
     *
     * @param int $idAgendamentos
     * @return Agendamentos|null
     */
    public function findById(int $idAgendamentos): ?Agendamentos
    {
        error_log("🟢 AgendamentoDao::findById()");

        $resultado = $this->findByField('id_agendamentos', $idAgendamentos);

        if (!empty($resultado)) {
            return $resultado[0];
        }

        return null;
    }

    /**
     * Busca agendamento por campo.
     *
     * @param string $field
     * @param mixed $value
     * @return array
     * @throws Exception
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 AgendamentoDao::findByField()");

        $camposPermitidos = [
            'id_agendamentos',
            'usuario_id',
            'campanha_id',
            'data_agendamento',
            'presenca'
        ];

        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM agendamentos WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute([
            ':value' => $value
        ]);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $agendamentos = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $agendamento = new Agendamentos();

            $agendamento->setIdAgendamentos((int) $linhaMatriz['id_agendamentos']);
            $agendamento->setUsuarioId((int) $linhaMatriz['usuario_id']);
            $agendamento->setCampanhaId((int) $linhaMatriz['campanha_id']);
            $agendamento->setDataAgendamento($linhaMatriz['data_agendamento']);
            $agendamento->setPresenca($linhaMatriz['presenca']);

            $agendamentos[] = $agendamento;
        }

        return $agendamentos;
    }
}
