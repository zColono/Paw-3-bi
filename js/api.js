/**
 * ApiService
 *
 * Classe responsável por centralizar as chamadas HTTP (GET, POST, PUT, DELETE)
 * à API REST da Clínica Veterinária, incluindo automaticamente o token JWT
 * (header Authorization: Bearer <token>) quando disponível.
 *
 * Baseada no padrão apresentado em aula (ApiService.js / paw03x04).
 */
export default class ApiService {
  #token;

  constructor(token = null) {
    this.#token = token;
  }

  get token() {
    return this.#token;
  }

  set token(value) {
    this.#token = value;
  }

  #montarHeaders() {
    const headers = { "Content-Type": "application/json" };

    if (this.#token) {
      headers["Authorization"] = `Bearer ${this.#token}`;
    }

    return headers;
  }

  /**
   * Interpreta a resposta da API. Se o token estiver ausente/expirado
   * (401), limpa a sessão e redireciona para a tela de login.
   */
  async #tratarResposta(response) {
    let corpo = null;

    try {
      corpo = await response.json();
    } catch (erro) {
      corpo = null;
    }

    if (response.status === 401) {
      localStorage.removeItem("pawvet_token");
      localStorage.removeItem("pawvet_usuario");

      if (!window.location.pathname.endsWith("login.html")) {
        window.location.href = "login.html";
      }
    }

    return { ok: response.ok, status: response.status, corpo };
  }

  async get(uri) {
    const response = await fetch(uri, {
      method: "GET",
      headers: this.#montarHeaders()
    });

    return this.#tratarResposta(response);
  }

  async getById(uri, id) {
    return this.get(`${uri}/${id}`);
  }

  async post(uri, jsonObject) {
    const response = await fetch(uri, {
      method: "POST",
      headers: this.#montarHeaders(),
      body: JSON.stringify(jsonObject)
    });

    return this.#tratarResposta(response);
  }

  async put(uri, id, jsonObject) {
    const response = await fetch(`${uri}/${id}`, {
      method: "PUT",
      headers: this.#montarHeaders(),
      body: JSON.stringify(jsonObject)
    });

    return this.#tratarResposta(response);
  }

  async delete(uri, id) {
    const response = await fetch(`${uri}/${id}`, {
      method: "DELETE",
      headers: this.#montarHeaders()
    });

    return this.#tratarResposta(response);
  }
}
