import ApiService from "./api.js";
import { salvarSessao, getToken } from "./auth.js";

const BASE_URL = window.location.origin;

document.addEventListener("DOMContentLoaded", () => {
  // Se já existe um token salvo, pula direto para o painel.
  if (getToken()) {
    window.location.href = "painel.html";
    return;
  }

  const form = document.getElementById("formLogin");
  const btnEntrar = document.getElementById("btnEntrar");
  const mensagem = document.getElementById("mensagem");
  const api = new ApiService();

  function mostrarMensagem(texto, tipo) {
    mensagem.textContent = texto;
    mensagem.className = `mensagem ${tipo}`;
  }

  form.addEventListener("submit", async (evento) => {
    evento.preventDefault();

    mensagem.className = "mensagem";
    btnEntrar.disabled = true;
    btnEntrar.textContent = "Entrando...";

    const email = document.getElementById("email").value.trim();
    const senha = document.getElementById("senha").value;

    const { ok, corpo } = await api.post(`${BASE_URL}/login`, { email, senha });

    btnEntrar.disabled = false;
    btnEntrar.textContent = "Entrar";

    if (ok && corpo && corpo.success) {
      salvarSessao(corpo.data.token, corpo.data.usuario);
      window.location.href = "painel.html";
      return;
    }

    const msgErro = corpo && corpo.message
      ? corpo.message
      : "Não foi possível fazer login. Tente novamente.";

    mostrarMensagem(msgErro, "erro");
  });
});
