import { exigirLogin, getUsuarioLogado, logout } from "./auth.js";
import entidades from "./entidades.js";

document.addEventListener("DOMContentLoaded", () => {
  if (!exigirLogin()) return;

  const usuario = getUsuarioLogado();
  const spanNome = document.getElementById("nomeUsuarioLogado");

  if (usuario) {
    spanNome.textContent = `${usuario.nomeUsuario} (${usuario.tipoUsuario || "—"})`;
  }

  document.getElementById("btnSair").addEventListener("click", logout);

  const grade = document.getElementById("gradeMenu");

  Object.entries(entidades).forEach(([chaveEntidade, config]) => {
    const item = document.createElement("div");
    item.className = "item-menu";
    item.innerHTML = `
      <h3>${config.titulo}</h3>
      <p>${config.descricao}</p>
      <div class="acoes">
        <a class="botao pequeno" href="cadastro.html?entidade=${chaveEntidade}">+ Cadastrar</a>
        <a class="botao secundario pequeno" href="listar.html?entidade=${chaveEntidade}">Ver / Editar / Excluir</a>
      </div>
    `;
    grade.appendChild(item);
  });
});
