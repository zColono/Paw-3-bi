<?php

namespace Api\Dao;

use Api\Models\Usuarios;
use Api\Database\MysqlDatabase;
use Exception;

/**
 * Classe responsável pelo acesso aos dados da entidade Cargo.
 *
 * Camadas:
 * Controller -> Service -> DAO -> Banco de Dados
 *
 * Objetivo:
 * Centralizar todas as operações SQL relacionadas à tabela cargo.
 */
class UsuariosDao
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

        error_log("⬆️ UsuariosDAO::__construct()");
    }

    /**
     * Insere um novo usuário no banco.
     *
     * @param Usuarios $objUsuarios
     * @return Usuarios gerado
     * @throws Exception
     */
    public function create(Usuarios $objUsuarios): Usuarios
    {
        error_log("🟢 UsuariosDAO::create()");

        /**
         * SQL de inserção.
         */
        $sql = "
            INSERT INTO usuarios (nome, email, senha, tel, tipo_usuario, data_nasc)
            VALUES (:nome, :email, :senha, :tel, :tipo_usuario, :data_nasc)
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':nome' => $objUsuarios->getNome(),
            ':email' => $objUsuarios->getEmail(),
            ':senha' => $objUsuarios->getSenha(),
            ':tel' => $objUsuarios->getTel(),
            ':tipo_usuario' => $objUsuarios->getTipoUsuario(),
            ':data_nasc' => $objUsuarios->getDataNasc()
        ];

        /**
         * Prepara e executa.
         */
        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar usuário.");
        }

        /**
         * Retorna ID criado.
         */
        $novoID = (int) $this->database->getConnection()->lastInsertId();
        $objUsuarios->setIdUsuarios($novoID);
        return $objUsuarios;
    }

    /**
     * Remove um usuário pelo ID.
     *
     * @param Usuarios $objUsuariosModel
     * @return bool
     */
    public function delete(Usuarios $objUsuariosModel): bool
    {
        error_log("🟢 UsuariosDAO::delete()");

        /**
         * SQL de exclusão.
         */
        $sql = "
            DELETE FROM usuarios
            WHERE id_usuarios = :id_usuarios
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':id_usuarios' => $objUsuariosModel->getIdUsuarios()
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
     * Atualiza um usuário existente.
     *
     * @param Usuarios $objUsuariosModel
     * @return bool
     */
    public function update(Usuarios $objUsuariosModel): bool
    {
        error_log("🟢 UsuariosDAO::update()");

        /**
         * SQL de atualização.
         */
        $sql = "
            UPDATE usuarios
            SET nome = :nome, email = :email, senha = :senha, tel = :tel, tipo_usuario = :tipo_usuario, data_nasc = :data_nasc
            WHERE id_usuarios = :id_usuarios
        ";

        /**
         * Valores da query.
         */
        $parametros = [
            ':nome' => $objUsuariosModel->getNome(),
            ':email' => $objUsuariosModel->getEmail(),
            ':senha' => $objUsuariosModel->getSenha(),
            ':tel' => $objUsuariosModel->getTel(),
            ':tipo_usuario' => $objUsuariosModel->getTipoUsuario(),
            ':data_nasc' => $objUsuariosModel->getDataNasc(),
            ':id_usuarios' => $objUsuariosModel->getIdUsuarios()
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
     * Retorna todos os usuários cadastrados.
     *
     * @return array
     */
    public function findAll(): array
    {
        error_log("🟢 UsuariosDAO::findAll()");

        /**
         * Consulta todos os registros.
         */
        $sql = "SELECT * FROM usuarios";

        /**
         * Executa consulta.
         */
        $stmt = $this->database->getConnection()->query($sql);

        /**
         * Matriz de arrays.
         */
        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        /**
         * Lista final de objetos Usuarios.
         */
        $usuarios = [];

        /**
         * Converte cada linha em objeto Usuarios.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $usuario = new Usuarios();

            $usuario->setIdUsuarios((int) $linhaMatriz['id_usuarios']);
            $usuario->setNomeUsuario($linhaMatriz['nome']);
            $usuario->setEmail($linhaMatriz['email']);
            $usuario->setSenha($linhaMatriz['senha']);
            $usuario->setTel($linhaMatriz['tel']);
            $usuario->setTipoUsuario($linhaMatriz['tipo_usuario']);
            $usuario->setDataNasc($linhaMatriz['data_nasc'] ?? null);

            $usuarios[] = $usuario;
        }

        /**
         * Retorna lista pronta.
         */
        return $usuarios;
    }

    /**
     * Retorna total de usuários cadastrados.
     *
     * @return int
     */
    public function count(): int
    {
        error_log("🟢 UsuariosDAO::count()");

        /**
         * SQL de contagem.
         */
        $sql = "SELECT COUNT(*) AS qtd FROM usuarios";

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
     * Busca usuário pelo ID.
     *
     * @param int $idUsuarios
     * @return Usuarios|null
     */
    public function findById(int $idUsuarios): ?Usuarios
    {
        error_log("🟢 UsuariosDAO::findById()");

        /**
         * Busca reutilizando método genérico.
         */
        $resultado = $this->findByField('id_usuarios', $idUsuarios);

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
        error_log("🟢 UsuariosDAO::findByField()");

        /**
         * Campos permitidos.
         */
        $camposPermitidos = [
            'id_usuarios',
            'nome',
            'email',
            'senha'
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
        $sql = "SELECT * FROM usuarios WHERE $field = :value";

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
         * Lista final de objetos Usuarios.
         */
        $usuarios = [];

        /**
         * Converte linhas em objetos.
         */
        foreach ($matrizArrays as $linhaMatriz) {
            $usuario = new Usuarios();

            $usuario->setIdUsuarios((int) $linhaMatriz['id_usuarios']);
            $usuario->setNomeUsuario($linhaMatriz['nome']);
            $usuario->setEmail($linhaMatriz['email']);
            $usuario->setSenha($linhaMatriz['senha']);
            $usuario->setTel($linhaMatriz['tel'] ?? null);
            $usuario->setTipoUsuario($linhaMatriz['tipo_usuario'] ?? null);
            $usuario->setDataNasc($linhaMatriz['data_nasc'] ?? null);

            $usuarios[] = $usuario;
        }

        /**
         * Retorna lista.
         */
        return $usuarios;
    }
}
