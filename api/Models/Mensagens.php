<?php

namespace Api\Models;

use InvalidArgumentException;
use JsonSerializable;

class Mensagens implements JsonSerializable
{
    private int $idMensagens;
    private string $titulo = "";
    private string $conteudo = "";
    private int $usuarioId;
    private string $dataPostagem = "";

    public function getIdMensagens(): ?int
    {
        return $this->idMensagens;
    }

    public function setIdMensagens(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("idMensagens deve ser maior que zero.");
        }

        $this->idMensagens = $value;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $value): void
    {
        $titulo = trim($value);

        if ($titulo === '') {
            throw new InvalidArgumentException("titulo nao pode ser vazio.");
        }

        if (mb_strlen($titulo) < 3) {
            throw new InvalidArgumentException("titulo deve ter pelo menos 3 caracteres.");
        }

        if (mb_strlen($titulo) > 100) {
            throw new InvalidArgumentException("titulo muito grande.");
        }

        $this->titulo = $titulo;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(string $value): void
    {
        $conteudo = trim($value);

        if ($conteudo === '') {
            throw new InvalidArgumentException("conteudo nao pode ser vazio.");
        }

        $this->conteudo = $conteudo;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }

    public function setUsuarioId(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("usuarioId deve ser maior que zero.");
        }

        $this->usuarioId = $value;
    }

    public function getDataPostagem(): ?string
    {
        return $this->dataPostagem;
    }

    public function setDataPostagem(string $value): void
    {
        $data = trim($value);

        if ($data === '') {
            throw new InvalidArgumentException("dataPostagem nao pode ser vazia.");
        }

        $this->dataPostagem = $data;
    }

    public function jsonSerialize(): array
    {
        return [
            'idMensagens' => $this->getIdMensagens(),
            'titulo' => $this->getTitulo(),
            'conteudo' => $this->getConteudo(),
            'usuarioId' => $this->getUsuarioId(),
            'dataPostagem' => $this->getDataPostagem()
        ];
    }
}
