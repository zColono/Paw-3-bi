<?php

namespace Api\Models;

use InvalidArgumentException;
use JsonSerializable;

class Especialidades implements JsonSerializable
{
    private int $idEspecialidades;
    private string $nome = "";
    private string $descricao = "";
    private string $diaAtendimento = "";
    private int $idResponsavel;

    public function getIdEspecialidades(): ?int
    {
        return $this->idEspecialidades;
    }

    public function setIdEspecialidades(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("idEspecialidades deve ser maior que zero.");
        }

        $this->idEspecialidades = $value;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $value): void
    {
        $nome = trim($value);

        if ($nome === '') {
            throw new InvalidArgumentException("nome nao pode ser vazio.");
        }

        if (mb_strlen($nome) < 3) {
            throw new InvalidArgumentException("nome deve ter pelo menos 3 caracteres.");
        }

        if (mb_strlen($nome) > 100) {
            throw new InvalidArgumentException("nome muito grande.");
        }

        $this->nome = $nome;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $value): void
    {
        $this->descricao = trim($value);
    }

    public function getDiaAtendimento(): ?string
    {
        return $this->diaAtendimento;
    }

    public function setDiaAtendimento(string $value): void
    {
        $dia = trim($value);

        if ($dia === '') {
            throw new InvalidArgumentException("diaAtendimento nao pode ser vazio.");
        }

        $this->diaAtendimento = $dia;
    }

    public function getIdResponsavel(): ?int
    {
        return $this->idResponsavel;
    }

    public function setIdResponsavel(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("idResponsavel deve ser maior que zero.");
        }

        $this->idResponsavel = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'idEspecialidades' => $this->getIdEspecialidades(),
            'nome' => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'diaAtendimento' => $this->getDiaAtendimento(),
            'idResponsavel' => $this->getIdResponsavel()
        ];
    }
}
