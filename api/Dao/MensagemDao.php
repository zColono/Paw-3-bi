<?php

namespace Api\Dao;

use Api\Models\Mensagens;
use Api\Database\MysqlDatabase;
use Exception;

/**
 * Classe responsável pelo acesso aos dados da entidade Mensagens.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela mensagens.
 */
class MensagemDao
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

        error_log("⬆️ MensagemDao::__construct()");
    }

    /**
     * Cadastra mensagem.
     *
     * @param Mensagens $objMensagem
     * @return Mensagens
     * @throws Exception
     */
    public function create(Mensagens $objMensagem): Mensagens
    {
        error_log("🟢 MensagemDao::create()");

        $sql = "
            INSERT INTO mensagens
            (
                titulo,
                conteudo,
                usuario_id,
                data_postagem
            )
            VALUES
            (
                :titulo,
                :conteudo,
                :usuario_id,
                :data_postagem
            )
        ";

        $parametros = [
            ':titulo' => $objMensagem->getTitulo(),
            ':conteudo' => $objMensagem->getConteudo(),
            ':usuario_id' => $objMensagem->getUsuarioId(),
            ':data_postagem' => $objMensagem->getDataPostagem()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar mensagem.");
        }

        $novoID = (int) $this->database->getConnection()->lastInsertId();

        $objMensagem->setIdMensagens($novoID);

        return $objMensagem;
    }

    /**
     * Remove mensagem.
     *
     * @param Mensagens $objMensagemModel
     * @return bool
     */
    public function delete(Mensagens $objMensagemModel): bool
    {
        error_log("🟢 MensagemDao::delete()");

        $sql = "
            DELETE FROM mensagens
            WHERE id_mensagens = :id_mensagens
        ";

        $parametros = [
            ':id_mensagens' => $objMensagemModel->getIdMensagens()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    /**
     * Atualiza mensagem.
     *
     * @param Mensagens $objMensagemModel
     * @return bool
     */
    public function update(Mensagens $objMensagemModel): bool
    {
        error_log("🟢 MensagemDao::update()");

        $sql = "
            UPDATE mensagens
            SET
                titulo = :titulo,
                conteudo = :conteudo,
                usuario_id = :usuario_id,
                data_postagem = :data_postagem
            WHERE id_mensagens = :id_mensagens
        ";

        $parametros = [
            ':titulo' => $objMensagemModel->getTitulo(),
            ':conteudo' => $objMensagemModel->getConteudo(),
            ':usuario_id' => $objMensagemModel->getUsuarioId(),
            ':data_postagem' => $objMensagemModel->getDataPostagem(),
            ':id_mensagens' => $objMensagemModel->getIdMensagens()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    /**
     * Lista todas as mensagens.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 MensagemDao::findAll()");

        $sql = "SELECT * FROM mensagens";

        $stmt = $this->database->getConnection()->query($sql);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mensagens = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $mensagem = new Mensagens();

            $mensagem->setIdMensagens((int) $linhaMatriz['id_mensagens']);
            $mensagem->setTitulo($linhaMatriz['titulo']);
            $mensagem->setConteudo($linhaMatriz['conteudo']);
            $mensagem->setUsuarioId((int) $linhaMatriz['usuario_id']);
            $mensagem->setDataPostagem($linhaMatriz['data_postagem']);

            $mensagens[] = $mensagem;
        }

        return $mensagens;
    }

    /**
     * Conta mensagens.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 MensagemDao::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM mensagens";

        $stmt = $this->database->getConnection()->query($sql);

        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $linhaMatriz['qtd'];
    }

    /**
     * Busca mensagem por ID.
     *
     * @param int $idMensagens
     * @return Mensagens|null
     */
    public function findById(int $idMensagens): ?Mensagens
    {
        error_log("🟢 MensagemDao::findById()");

        $resultado = $this->findByField('id_mensagens', $idMensagens);

        if (!empty($resultado)) {
            return $resultado[0];
        }

        return null;
    }

    /**
     * Busca mensagem por campo.
     *
     * @param string $field
     * @param mixed $value
     * @return array
     * @throws Exception
     */
    public function findByField(string $field, $value): array
    {
        error_log("🟢 MensagemDao::findByField()");

        $camposPermitidos = [
            'id_mensagens',
            'titulo',
            'conteudo',
            'usuario_id',
            'data_postagem'
        ];

        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM mensagens WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute([
            ':value' => $value
        ]);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mensagens = [];

        foreach ($matrizArrays as $linhaMatriz) {

            $mensagem = new Mensagens();

            $mensagem->setIdMensagens((int) $linhaMatriz['id_mensagens']);
            $mensagem->setTitulo($linhaMatriz['titulo']);
            $mensagem->setConteudo($linhaMatriz['conteudo']);
            $mensagem->setUsuarioId((int) $linhaMatriz['usuario_id']);
            $mensagem->setDataPostagem($linhaMatriz['data_postagem']);

            $mensagens[] = $mensagem;
        }

        return $mensagens;
    }
}
