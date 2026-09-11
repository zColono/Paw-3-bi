<?php

namespace Api\Models;

use InvalidArgumentException;
use JsonSerializable;

class Usuarios implements JsonSerializable
{
    private int $idUsuario = 0;
    private string $nomeUsuario = "";
    private string $email = "";
    private string $senha = "";
    private string $tel = "";
    private string $tipoUsuario = "";
    private string $dataNasc = "";

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("idUsuario deve ser maior que zero.");
        }

        $this->idUsuario = $value;
    }

    public function getIdUsuarios(): ?int
    {
        return $this->getIdUsuario();
    }

    public function setIdUsuarios(int $value): void
    {
        $this->setIdUsuario($value);
    }

    public function getNomeUsuario(): ?string
    {
        return $this->nomeUsuario;
    }

    
    public function setNomeUsuario(string $value): void
    {
        $nome = trim($value);

        if ($nome === '') {
            throw new InvalidArgumentException("nomeUsuario nao pode ser vazio.");
        }

        if (mb_strlen($nome) < 3) {
            throw new InvalidArgumentException("nomeUsuario deve ter pelo menos 3 caracteres.");
        }

        if (mb_strlen($nome) > 100) {
            throw new InvalidArgumentException("nomeUsuario muito grande.");
        }

        $this->nomeUsuario = $nome;
    }

    public function getNome(): ?string
    {
        return $this->getNomeUsuario();
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if ($email === '') {
            throw new InvalidArgumentException("email nao pode ser vazio.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("email invalido.");
        }

        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setSenha(string $senha): void
    {
        if (trim($senha) === '') {
            throw new InvalidArgumentException("senha nao pode ser vazia.");
        }

        $this->senha = $senha;
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setTel(?string $tel): void
    {
        $this->tel = trim((string) $tel);
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTipoUsuario(?string $tipoUsuario): void
    {
        $this->tipoUsuario = trim((string) $tipoUsuario);
    }

    public function getTipoUsuario(): ?string
    {
        return $this->tipoUsuario;
    }

    public function setDataNasc(?string $dataNasc): void
    {
        $this->dataNasc = trim((string) $dataNasc);
    }

    public function getDataNasc(): ?string
    {
        return $this->dataNasc;
    }

    public function jsonSerialize(): array
    {
        return [
            'idUsuario' => $this->getIdUsuario(),
            'nomeUsuario' => $this->getNomeUsuario(),
            'email' => $this->getEmail(),
            'tel' => $this->getTel(),
            'tipoUsuario' => $this->getTipoUsuario(),
            'data_nasc' => $this->getDataNasc()
        ];
    }
}
