<?php

namespace Api\Models;

use InvalidArgumentException;
use JsonSerializable;

class Campanhas implements JsonSerializable
{
    private int $idCampanha;
    private string $titulo = "";
    private string $descricao = "";
    private string $dataCampanha = "";
    private string $localCampanha = "";
    private int $limiteVagas;
    private string $statusCampanha = "";

    public function getIdCampanha(): ?int
    {
        return $this->idCampanha;
    }

    public function setIdCampanha(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("idCampanha deve ser maior que zero.");
        }

        $this->idCampanha = $value;
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $value): void
    {
        $descricao = trim($value);

        if ($descricao === '') {
            throw new InvalidArgumentException("descricao nao pode ser vazia.");
        }

        $this->descricao = $descricao;
    }

    public function getDataCampanha(): ?string
    {
        return $this->dataCampanha;
    }

    public function setDataCampanha(string $value): void
    {
        $data = trim($value);

        if ($data === '') {
            throw new InvalidArgumentException("dataCampanha nao pode ser vazia.");
        }

        $this->dataCampanha = $data;
    }

    public function getLocalCampanha(): ?string
    {
        return $this->localCampanha;
    }

    public function setLocalCampanha(string $value): void
    {
        $local = trim($value);

        if ($local === '') {
            throw new InvalidArgumentException("localCampanha nao pode ser vazio.");
        }

        $this->localCampanha = $local;
    }

    public function getLimiteVagas(): ?int
    {
        return $this->limiteVagas;
    }

    public function setLimiteVagas(int $value): void
    {
        if ($value < 0) {
            throw new InvalidArgumentException("limiteVagas nao pode ser negativo.");
        }

        $this->limiteVagas = $value;
    }

    public function getStatusCampanha(): ?string
    {
        return $this->statusCampanha;
    }

    public function setStatusCampanha(string $value): void
    {
        $status = trim($value);

        if ($status === '') {
            throw new InvalidArgumentException("statusCampanha nao pode ser vazio.");
        }

        $this->statusCampanha = $status;
    }

    public function jsonSerialize(): array
    {
        return [
            'idCampanha' => $this->getIdCampanha(),
            'titulo' => $this->getTitulo(),
            'descricao' => $this->getDescricao(),
            'dataCampanha' => $this->getDataCampanha(),
            'localCampanha' => $this->getLocalCampanha(),
            'limiteVagas' => $this->getLimiteVagas(),
            'statusCampanha' => $this->getStatusCampanha()
        ];
    }
}
