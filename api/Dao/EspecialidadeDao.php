<?php

namespace Api\Dao;

use Api\Models\Especialidades;
use Api\Database\MysqlDatabase;
use Exception;

/**
 * Classe responsável pelo acesso aos dados da entidade Especialidades.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela especialidades.
 */
class EspecialidadeDao
{
    /**
     * Instância de conexão com banco de dados.
     *
     * @var MysqlDatabase
     */
    private MysqlDatabase $database;

    /**
     * Recebe a conexão via injeção de dependência.
     *
     * @param MysqlDatabase $databaseInstance
     */
    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;
        error_log("⬆️ EspecialidadeDao::__construct()");
    }

    /**
     * Insere um novo especialidade no banco.
     *
     * @param Especialidades $objEspecialidades
     * @return Especialidades gerado
     * @throws Exception
     */
    public function create(Especialidades $objEspecialidades): Especialidades
    {
        error_log("🟢 EspecialidadeDao::create()");

        /**
         * SQL de inserção.
         */
        $sql = "
            INSERT INTO especialidades (nome, descricao, dia_atendimento, id_responsavel)
            VALUES (:nome, :descricao, :dia_atendimento, :id_responsavel)
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':nome' => $objEspecialidades->getNome(),
            ':descricao' => $objEspecialidades->getDescricao(),
            ':dia_atendimento' => $objEspecialidades->getDiaAtendimento(),
            ':id_responsavel' => $objEspecialidades->getIdResponsavel()
        ];

        /**
         * Prepara e executa.
         */
        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar especialidade.");
        }

        /**
         * Retorna ID criado.
         */
        $novoID = (int) $this->database->getConnection()->lastInsertId();
        $objEspecialidades->setIdEspecialidades($novoID);
        return $objEspecialidades;
    }

    /**
     * Remove um especialidade pelo ID.
     *
     * @param Especialidades $objEspecialidadesModel
     * @return bool
     */
    public function delete(Especialidades $objEspecialidadesModel): bool
    {
        error_log("🟢 EspecialidadeDao::delete()");

        /**
         * SQL de exclusão.
         */
        $sql = "
            DELETE FROM especialidades
            WHERE id_especialidades = :id_especialidades
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':id_especialidades' => $objEspecialidadesModel->getIdEspecialidades()
        ];

        /**
         * Executa exclusão.
         */
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        /**
         * True se removeu registro.
         */
        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza um especialidade existente.
     *
     * @param Especialidades $objEspecialidadesModel
     * @return bool
     */
    public function update(Especialidades $objEspecialidadesModel): bool
    {
        error_log("🟢 EspecialidadeDao::update()");

        /**
         * SQL de atualização.
         */
        $sql = "
            UPDATE especialidades
            SET nome = :nome, descricao = :descricao, dia_atendimento = :dia_atendimento, id_responsavel = :id_responsavel
            WHERE id_especialidades = :id_especialidades
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':nome' => $objEspecialidadesModel->getNome(),
            ':descricao' => $objEspecialidadesModel->getDescricao(),
            ':dia_atendimento' => $objEspecialidadesModel->getDiaAtendimento(),
            ':id_responsavel' => $objEspecialidadesModel->getIdResponsavel(),
            ':id_especialidades' => $objEspecialidadesModel->getIdEspecialidades()
        ];

        /**
         * Executa atualização.
         */
        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        /**
         * True se alterou registro.
         */
        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna todos os especialidades cadastrados.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 EspecialidadeDao::findAll()");

        /**
         * Consulta todos os registros.
         */
        $sql = "SELECT * FROM especialidades";

        /**
         * Executa consulta.
         */
        $stmt = $this->database->getConnection()->query($sql);

        /**
         * Matriz de arrays.
         */
        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * Lista final de objetos Especialidades.
         */
        $especialidades = [];

        /**
         * Converte cada linha em objeto Especialidades.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $especialidade = new Especialidades();

            $especialidade->setIdEspecialidades((int) $linhaMatriz['id_especialidades']);
            $especialidade->setNome($linhaMatriz['nome']);
            $especialidade->setDescricao($linhaMatriz['descricao'] ?? null);
            $especialidade->setDiaAtendimento($linhaMatriz['dia_atendimento']);
            $especialidade->setIdResponsavel((int) $linhaMatriz['id_responsavel']);

            $especialidades[] = $especialidade;
        }

        /**
         * Retorna lista pronta.
         */
        return $especialidades;
    }

    /**
     * Retorna total de especialidades cadastrados.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 EspecialidadeDao::count()");

        /**
         * SQL de contagem.
         */
        $sql = "SELECT COUNT(*) AS qtd FROM especialidades";

        /**
         * Executa consulta.
         */
        $stmt = $this->database->getConnection()->query($sql);

        /**
         * Resultado único.
         */
        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        /**
         * Retorna total.
         */
        return (int) $linhaMatriz['qtd'];
    }

    /**
     * Busca especialidade pelo ID.
     *
     * @param int $idEspecialidades
     * @return Especialidades|null
     */
    public function findById(int $idEspecialidades): ?Especialidades
    {
        error_log("🟢 EspecialidadeDao::findById()");

        /**
         * Busca reutilizando método genérico.
         */
        $resultado = $this->findByField('id_especialidades', $idEspecialidades);

        /**
         * Se encontrou registro.
         */
        if (!empty($resultado)) {
            return $resultado[0];
        }

        /**
         * Não encontrado.
         */
        return null;
    }

    /**
     * Busca por campo específico.
     *
     * @param string $field
     * @param mixed $value
     * @return array
     * @throws Exception
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 EspecialidadeDao::findByField()");

        /**
         * Campos permitidos.
         */
        $camposPermitidos = [
            'id_especialidades',
            'nome',
            'descricao',
            'dia_atendimento',
            'id_responsavel'
        ];

        /**
         * Valida campo informado.
         */
        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        /**
         * SQL dinâmica segura.
         */
        $sql = "SELECT * FROM especialidades WHERE $field = :value";

        /**
         * Prepara consulta.
         */
        $stmt = $this->database->getConnection()->prepare($sql);

        /**
         * Executa busca.
         */
        $stmt->execute([
            ':value' => $value
        ]);

        /**
         * Matriz retornada.
         */
        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * Lista final de objetos Especialidades.
         */
        $especialidades = [];

        /**
         * Converte linhas em objetos.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $especialidade = new Especialidades();

            $especialidade->setIdEspecialidades((int) $linhaMatriz['id_especialidades']);
            $especialidade->setNome($linhaMatriz['nome']);
            $especialidade->setDescricao($linhaMatriz['descricao'] ?? null);
            $especialidade->setDiaAtendimento($linhaMatriz['dia_atendimento']);
            $especialidade->setIdResponsavel((int) $linhaMatriz['id_responsavel']);

            $especialidades[] = $especialidade;
        }

        /**
         * Retorna lista.
         */
        return $especialidades;
    }
}
