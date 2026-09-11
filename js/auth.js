/**
 * Helpers de autenticação compartilhados por todas as páginas.
 * O token JWT e os dados do usuário logado ficam salvos no localStorage.
 */

const CHAVE_TOKEN = "pawvet_token";
const CHAVE_USUARIO = "pawvet_usuario";

export function salvarSessao(token, usuario) {
  localStorage.setItem(CHAVE_TOKEN, token);
  localStorage.setItem(CHAVE_USUARIO, JSON.stringify(usuario));
}

export function getToken() {
  return localStorage.getItem(CHAVE_TOKEN);
}

export function getUsuarioLogado() {
  const bruto = localStorage.getItem(CHAVE_USUARIO);
  return bruto ? JSON.parse(bruto) : null;
}

export function logout() {
  localStorage.removeItem(CHAVE_TOKEN);
  localStorage.removeItem(CHAVE_USUARIO);
  window.location.href = "login.html";
}

/**
 * Deve ser chamada no início de toda página protegida.
 * Se não houver token salvo, redireciona para o login.
 */
export function exigirLogin() {
  const token = getToken();

  if (!token) {
    window.location.href = "login.html";
    return null;
  }

  return token;
}
