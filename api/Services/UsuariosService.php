<?php

namespace Api\Services;

use Api\Models\Usuarios;
use Api\Dao\UsuariosDao;
use Api\Http\ErrorResponse;
use stdClass;

/**
 * Camada de regra de negócio da entidade Usuarios.
 *
 * Fluxo:
 * Controller -> Service -> DAO -> Banco
 */
class UsuariosService
{
    /**
     * DAO responsável pelo acesso aos dados.
     *
     * @var UsuariosDao
     */
    private UsuariosDao $usuariosDAO;

    /**
     * Injeção de dependência.
     *
     * @param UsuariosDao $usuariosDAODependency
     */
    public function __construct(UsuariosDao $usuariosDAODependency)
    {
        error_log("⬆️ UsuariosService::__construct()");
        $this->usuariosDAO = $usuariosDAODependency;
    }

    /**
     * Cria um novo usuário.
     *
     * Regras:
     * - Não permite email duplicado.
     *
     * @param stdClass $objPHP
     * @return Usuarios
     * @throws ErrorResponse
     */
    public function createService(stdClass $objPHP): Usuarios
    {
        error_log("🟣 UsuariosService::createService()");

        $usuario = new Usuarios();

        $usuario->setNomeUsuario($objPHP->usuarios->nomeUsuario);
        $usuario->setEmail($objPHP->usuarios->email);
        $usuario->setSenha($objPHP->usuarios->senha);
        $usuario->setTel($objPHP->usuarios->tel ?? null);
        $usuario->setTipoUsuario($objPHP->usuarios->tipoUsuario ?? null);
        $usuario->setDataNasc($objPHP->usuarios->data_nasc ?? null);

        /**
         * Verifica duplicidade de email.
         */
        $resultado = $this->usuariosDAO->findByField(
            'email',
            $usuario->getEmail()
        );

        if (count($resultado) > 0) {
            throw new ErrorResponse(
                400,
                "Usuário já existe",
                [
                    "message" =>
                        "O email {$usuario->getEmail()} já está cadastrado"
                ]
            );
        }

        return $this->usuariosDAO->create($usuario);
    }

    /**
     * Retorna quantidade total.
     *
     * @return int
     */
    public function countService(): int
    {
        error_log("🟣 UsuariosService::countService()");
        return $this->usuariosDAO->count();
    }

    /**
     * Lista todos os usuários.
     *
     * @return array
     */
    public function findAllService(): array
    {
        error_log("🟣 UsuariosService::findAllService()");
        return $this->usuariosDAO->findAll();
    }

    /**
     * Busca usuário por ID.
     *
     * @param int $idUsuario
     * @return Usuarios|null
     */
    public function findByIdService(int $idUsuario): ?Usuarios
    {
        error_log("🟣 UsuariosService::findByIdService()");

        return $this->usuariosDAO->findById($idUsuario);
    }

    /**
     * Atualiza usuário existente.
     *
     * @param int $idUsuario
     * @param stdClass $objPHP
     * @return Usuarios
     * @throws ErrorResponse
     */
    public function updateService(int $idUsuario, stdClass $objPHP): Usuarios
    {
        error_log("🟣 UsuariosService::updateService()");

        /**
         * Verifica existência.
         */
        $usuarioExistente = $this->usuariosDAO->findById($idUsuario);

        if (!$usuarioExistente) {
            throw new ErrorResponse(
                404,
                "Usuário não encontrado",
                [
                    "message" =>
                        "Não existe usuário com id {$idUsuario}"
                ]
            );
        }

        /**
         * Atualiza dados.
         */
        $usuarioExistente->setNomeUsuario($objPHP->usuarios->nomeUsuario);
        $usuarioExistente->setEmail($objPHP->usuarios->email);
        $usuarioExistente->setSenha($objPHP->usuarios->senha);
        $usuarioExistente->setTel($objPHP->usuarios->tel ?? null);
        $usuarioExistente->setTipoUsuario($objPHP->usuarios->tipoUsuario ?? null);
        $usuarioExistente->setDataNasc($objPHP->usuarios->data_nasc ?? null);

        $this->usuariosDAO->update($usuarioExistente);

        return $usuarioExistente;
    }

    /**
     * Remove usuário existente.
     *
     * @param int $idUsuario
     * @return bool
     * @throws ErrorResponse
     */
    public function deleteService(int $idUsuario): bool
    {
        error_log("🟣 UsuariosService::deleteService()");

        /**
         * Verifica existência.
         */
        $usuarioExistente = $this->usuariosDAO->findById($idUsuario);

        if (!$usuarioExistente) {
            throw new ErrorResponse(
                404,
                "Usuário não encontrado",
                [
                    "message" =>
                        "Não existe usuário com id {$idUsuario}"
                ]
            );
        }

        return $this->usuariosDAO->delete($usuarioExistente);
    }
}
