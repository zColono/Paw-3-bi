<?php

namespace Api\Auth;

/**
 * Configurações centrais para geração e validação dos tokens JWT.
 *
 * Mantida em um único lugar para que o AuthController (quem gera o token)
 * e o ValidateJwtToken (quem valida o token) usem sempre a mesma chave.
 */
class JwtConfig
{
    /**
     * Chave secreta usada para assinar/validar os tokens.
     *
     * Observação: em um projeto real essa chave deveria vir de uma
     * variável de ambiente (.env) e nunca ficar hardcoded no código.
     * Para fins didáticos do projeto de PAW, mantemos aqui.
     */
    public const SECRET_KEY = 'paw-clinica-veterinaria-2026-chave-secreta';

    /**
     * Algoritmo de assinatura utilizado.
     */
    public const ALGORITHM = 'HS256';

    /**
     * Tempo de expiração do token, em segundos (2 horas).
     */
    public const EXPIRATION = 7200;
}
