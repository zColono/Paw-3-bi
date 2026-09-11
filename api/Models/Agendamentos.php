<?php

namespace Api\Models;

use InvalidArgumentException;
use JsonSerializable;

class Agendamentos implements JsonSerializable
{
    private ?int $idAgendamentos = null;
    private ?int $usuarioId = null;
    private ?int $campanhaId = null;
    private string $dataAgendamento = "";
    private string $presenca = "";

    public function getIdAgendamentos(): ?int
    {
        return $this->idAgendamentos;
    }

    public function setIdAgendamentos(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("idAgendamentos deve ser maior que zero.");
        }

        $this->idAgendamentos = $value;
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

    public function getCampanhaId(): ?int
    {
        return $this->campanhaId;
    }

    public function setCampanhaId(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("campanhaId deve ser maior que zero.");
        }

        $this->campanhaId = $value;
    }

    public function getDataAgendamento(): ?string
    {
        return $this->dataAgendamento;
    }

    public function setDataAgendamento(string $value): void
    {
        $data = trim($value);

        if ($data === '') {
            throw new InvalidArgumentException("dataAgendamento nao pode ser vazia.");
        }

        $this->dataAgendamento = $data;
    }

    public function getPresenca(): ?string
    {
        return $this->presenca;
    }

    public function setPresenca(string $value): void
    {
        $presenca = trim($value);

        if ($presenca === '') {
            throw new InvalidArgumentException("presenca nao pode ser vazia.");
        }

        $this->presenca = $presenca;
    }

    public function jsonSerialize(): array
    {
        return [
            'idAgendamentos' => $this->getIdAgendamentos(),
            'usuarioId' => $this->getUsuarioId(),
            'campanhaId' => $this->getCampanhaId(),
            'dataAgendamento' => $this->getDataAgendamento(),
            'presenca' => $this->getPresenca()
        ];
    }
}
