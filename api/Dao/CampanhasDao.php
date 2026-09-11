<?php

namespace Api\Dao;

use Api\Models\Campanhas;
use Api\Database\MysqlDatabase;
use Exception;

/**
 * Classe responsável pelo acesso aos dados da entidade Campanhas.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela campanhas.
 */
class CampanhasDao
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

        error_log("⬆️ CampanhasDao::__construct()");
    }

    /**
     * Cadastra campanha.
     *
     * @param Campanhas $objCampanha
     * @return Campanhas
     * @throws Exception
     */
    public function create(Campanhas $objCampanha): Campanhas
    {
        error_log("🟢 CampanhasDao::create()");

        $sql = "
            INSERT INTO campanhas
            (
                titulo,
                descricao,
                data_campanha,
                local_campanha,
                limite_vagas,
                status_campanha
            )
            VALUES
            (
                :titulo,
                :descricao,
                :data_campanha,
                :local_campanha,
                :limite_vagas,
                :status_campanha
            )
        ";

        $parametros = [
            ':titulo' => $objCampanha->getTitulo(),
            ':descricao' => $objCampanha->getDescricao(),
            ':data_campanha' => $objCampanha->getDataCampanha(),
            ':local_campanha' => $objCampanha->getLocalCampanha(),
            ':limite_vagas' => $objCampanha->getLimiteVagas(),
            ':status_campanha' => $objCampanha->getStatusCampanha()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar campanha.");
        }

        $novoID = (int) $this->database->getConnection()->lastInsertId();

        $objCampanha->setIdCampanha($novoID);

        return $objCampanha;
    }

    /**
     * Remove campanha.
     *
     * @param Campanhas $objCampanhaModel
     * @return bool
     */
    public function delete(Campanhas $objCampanhaModel): bool
    {
        error_log("🟢 CampanhasDao::delete()");

        $sql = "
            DELETE FROM campanhas
            WHERE id_campanha = :id_campanha
        ";

        $parametros = [
            ':id_campanha' => $objCampanhaModel->getIdCampanha()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza campanha.
     *
     * @param Campanhas $objCampanhaModel
     * @return bool
     */
    public function update(Campanhas $objCampanhaModel): bool
    {
        error_log("🟢 CampanhasDao::update()");

        $sql = "
            UPDATE campanhas
            SET
                titulo = :titulo,
                descricao = :descricao,
                data_campanha = :data_campanha,
                local_campanha = :local_campanha,
                limite_vagas = :limite_vagas,
                status_campanha = :status_campanha
            WHERE id_campanha = :id_campanha
        ";

        $parametros = [
            ':titulo' => $objCampanhaModel->getTitulo(),
            ':descricao' => $objCampanhaModel->getDescricao(),
            ':data_campanha' => $objCampanhaModel->getDataCampanha(),
            ':local_campanha' => $objCampanhaModel->getLocalCampanha(),
            ':limite_vagas' => $objCampanhaModel->getLimiteVagas(),
            ':status_campanha' => $objCampanhaModel->getStatusCampanha(),
            ':id_campanha' => $objCampanhaModel->getIdCampanha()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    /**
     * Lista todos os campanhas.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 CampanhasDao::findAll()");

        $sql = "SELECT * FROM campanhas";

        $stmt = $this->database->getConnection()->query($sql);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $campanhas = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $campanha = new Campanhas();

            $campanha->setIdCampanha((int) $linhaMatriz['id_campanha']);
            $campanha->setTitulo($linhaMatriz['titulo']);
            $campanha->setDescricao($linhaMatriz['descricao']);
            $campanha->setDataCampanha($linhaMatriz['data_campanha']);
            $campanha->setLocalCampanha($linhaMatriz['local_campanha']);
            $campanha->setLimiteVagas((int) $linhaMatriz['limite_vagas']);
            $campanha->setStatusCampanha($linhaMatriz['status_campanha']);

            $campanhas[] = $campanha;
        }

        return $campanhas;
    }

    /**
     * Conta campanhas.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 CampanhasDao::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM campanhas";

        $stmt = $this->database->getConnection()->query($sql);

        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $linhaMatriz['qtd'];
    }

    /**
     * Busca campanha por ID.
     *
     * @param int $idCampanha
     * @return Campanhas|null
     */
    public function findById(int $idCampanha): ?Campanhas
    {
        error_log("🟢 CampanhasDao::findById()");

        $resultado = $this->findByField('id_campanha', $idCampanha);

        if (!empty($resultado)) {
            return $resultado[0];
        }

        return null;
    }

    /**
     * Busca campanha por campo.
     *
     * @param string $field
     * @param mixed $value
     * @return array
     * @throws Exception
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 CampanhasDao::findByField()");

        $camposPermitidos = [
            'id_campanha',
            'titulo',
            'descricao',
            'data_campanha',
            'local_campanha',
            'limite_vagas',
            'status_campanha'
        ];

        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM campanhas WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute([
            ':value' => $value
        ]);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $campanhas = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $campanha = new Campanhas();

            $campanha->setIdCampanha((int) $linhaMatriz['id_campanha']);
            $campanha->setTitulo($linhaMatriz['titulo']);
            $campanha->setDescricao($linhaMatriz['descricao']);
            $campanha->setDataCampanha($linhaMatriz['data_campanha']);
            $campanha->setLocalCampanha($linhaMatriz['local_campanha']);
            $campanha->setLimiteVagas((int) $linhaMatriz['limite_vagas']);
            $campanha->setStatusCampanha($linhaMatriz['status_campanha']);

            $campanhas[] = $campanha;
        }

        return $campanhas;
    }
}
