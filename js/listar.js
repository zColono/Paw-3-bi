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

  const tituloPagina = document.getElementById("tituloPagina");
  const descricaoPagina = document.getElementById("descricaoPagina");
  const statusCarregamento = document.getElementById("statusCarregamento");
  const tabela = document.getElementById("tabelaRegistros");
  const linhaCabecalho = document.getElementById("linhaCabecalho");
  const corpoTabela = document.getElementById("corpoTabela");
  const mensagem = document.getElementById("mensagem");
  const linkCadastrar = document.getElementById("linkCadastrar");

  if (!config) {
    tituloPagina.textContent = "Entidade não encontrada";
    descricaoPagina.textContent = "Volte ao painel e escolha uma opção válida.";
    statusCarregamento.style.display = "none";
    return;
  }

  tituloPagina.textContent = config.titulo;
  descricaoPagina.textContent = config.descricao;
  linkCadastrar.href = `cadastro.html?entidade=${chaveEntidade}`;

  // Colunas de tabela: não exibimos campos do tipo "password" (a API não os devolve).
  const camposTabela = config.campos.filter((c) => c.tipo !== "password");

  linhaCabecalho.innerHTML =
    `<th>${config.idCampo}</th>` +
    camposTabela.map((c) => `<th>${c.label}</th>`).join("") +
    `<th>Ações</th>`;

  const api = new ApiService(token);

  // --- Modal de edição ---
  const overlay = document.getElementById("overlayEdicao");
  const formEdicao = document.getElementById("formEdicao");
  const mensagemEdicao = document.getElementById("mensagemEdicao");
  const btnSalvarEdicao = document.getElementById("btnSalvarEdicao");
  const btnCancelarEdicao = document.getElementById("btnCancelarEdicao");
  let idEmEdicao = null;

  function fecharModal() {
    overlay.classList.remove("aberto");
    idEmEdicao = null;
    mensagemEdicao.className = "mensagem";
  }

  btnCancelarEdicao.addEventListener("click", fecharModal);

  function abrirModalEdicao(registro) {
    idEmEdicao = registro[config.idCampo];
    mensagemEdicao.className = "mensagem";
    renderizarCampos(formEdicao, config.campos, registro, "edt");
    overlay.classList.add("aberto");
  }

  btnSalvarEdicao.addEventListener("click", async () => {
    const valores = coletarValores(formEdicao, config.campos, "edt");
    const corpoRequisicao = { [config.chave]: valores };

    btnSalvarEdicao.disabled = true;
    btnSalvarEdicao.textContent = "Salvando...";

    const { ok, corpo } = await api.put(`${BASE_URL}/${config.chave}`, idEmEdicao, corpoRequisicao);

    btnSalvarEdicao.disabled = false;
    btnSalvarEdicao.textContent = "Salvar alterações";

    if (ok && corpo && corpo.success) {
      fecharModal();
      mensagem.className = "mensagem sucesso";
      mensagem.textContent = "Registro atualizado com sucesso!";
      carregarRegistros();
    } else {
      mensagemEdicao.className = "mensagem erro";
      mensagemEdicao.textContent = (corpo && corpo.message) || "Não foi possível salvar as alterações.";
    }
  });

  // --- Exclusão ---
  async function excluirRegistro(id) {
    const confirmar = window.confirm("Tem certeza que deseja excluir este registro?");
    if (!confirmar) return;

    const { ok, corpo } = await api.delete(`${BASE_URL}/${config.chave}`, id);

    if (ok) {
      mensagem.className = "mensagem sucesso";
      mensagem.textContent = "Registro excluído com sucesso!";
      carregarRegistros();
    } else {
      mensagem.className = "mensagem erro";
      mensagem.textContent = (corpo && corpo.message) || "Não foi possível excluir o registro.";
    }
  }

  // --- Listagem ---
  function montarLinha(registro) {
    const tr = document.createElement("tr");

    const celulas = [registro[config.idCampo]]
      .concat(camposTabela.map((c) => registro[c.nome] ?? ""))
      .map((valor) => `<td>${valor === "" || valor === null ? "—" : valor}</td>`)
      .join("");

    tr.innerHTML = celulas;

    const tdAcoes = document.createElement("td");
    tdAcoes.className = "acoes-tabela";

    const btnEditar = document.createElement("button");
    btnEditar.type = "button";
    btnEditar.className = "botao pequeno secundario";
    btnEditar.textContent = "Editar";
    btnEditar.addEventListener("click", () => abrirModalEdicao(registro));

    const btnExcluir = document.createElement("button");
    btnExcluir.type = "button";
    btnExcluir.className = "botao pequeno perigo";
    btnExcluir.textContent = "Excluir";
    btnExcluir.addEventListener("click", () => excluirRegistro(registro[config.idCampo]));

    tdAcoes.appendChild(btnEditar);
    tdAcoes.appendChild(btnExcluir);
    tr.appendChild(tdAcoes);

    return tr;
  }

  async function carregarRegistros() {
    statusCarregamento.style.display = "block";
    statusCarregamento.textContent = "Carregando registros...";
    tabela.style.display = "none";

    const { ok, corpo } = await api.get(`${BASE_URL}/${config.chave}`);

    if (!ok || !corpo || !corpo.success) {
      statusCarregamento.textContent =
        (corpo && corpo.message) || "Não foi possível carregar os registros.";
      return;
    }

    const registros = corpo.data[config.chave] || [];

    corpoTabela.innerHTML = "";

    if (registros.length === 0) {
      statusCarregamento.textContent = "Nenhum registro cadastrado ainda.";
      return;
    }

    registros.forEach((registro) => corpoTabela.appendChild(montarLinha(registro)));

    statusCarregamento.style.display = "none";
    tabela.style.display = "table";
  }

  carregarRegistros();
});
