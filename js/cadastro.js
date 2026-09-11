import { exigirLogin, getToken, getUsuarioLogado, logout } from "./auth.js";
import ApiService from "./api.js";
import entidades from "./entidades.js";
import { renderizarCampos, coletarValores } from "./formulario.js";

const BASE_URL = window.location.origin;

document.addEventListener("DOMContentLoaded", () => {
  const token = exigirLogin();
  if (!token) return;

  const usuario = getUsuarioLogado();
  if (usuario) {
    document.getElementById("nomeUsuarioLogado").textContent =
      `${usuario.nomeUsuario} (${usuario.tipoUsuario || "—"})`;
  }
  document.getElementById("btnSair").addEventListener("click", logout);

  const params = new URLSearchParams(window.location.search);
  const chaveEntidade = params.get("entidade");
  const config = entidades[chaveEntidade];

  const form = document.getElementById("formCadastro");
  const mensagem = document.getElementById("mensagem");

  if (!config) {
    document.getElementById("tituloPagina").textContent = "Entidade não encontrada";
    document.getElementById("descricaoPagina").textContent =
      "Volte ao painel e escolha uma opção válida.";
    return;
  }

  document.getElementById("tituloPagina").textContent = `Cadastrar ${config.titulo}`;
  document.getElementById("descricaoPagina").textContent = config.descricao;

  renderizarCampos(form, config.campos, {}, "cad");

  const botoes = document.createElement("div");
  botoes.className = "rodape-form";
  botoes.innerHTML = `
    <button type="submit" class="botao">Salvar cadastro</button>
    <a class="botao secundario" href="listar.html?entidade=${chaveEntidade}">Ver lista</a>
  `;
  form.appendChild(botoes);

  const api = new ApiService(token);

  form.addEventListener("submit", async (evento) => {
    evento.preventDefault();

    const valores = coletarValores(form, config.campos, "cad");
    const corpoRequisicao = { [config.chave]: valores };

    const { ok, corpo } = await api.post(`${BASE_URL}/${config.chave}`, corpoRequisicao);

    if (ok && corpo && corpo.success) {
      mensagem.className = "mensagem sucesso";
      mensagem.textContent = corpo.message || "Cadastro realizado com sucesso!";
      form.reset();
    } else {
      mensagem.className = "mensagem erro";
      mensagem.textContent = (corpo && corpo.message) || "Não foi possível salvar o cadastro.";
    }
  });
});
