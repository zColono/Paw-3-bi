/**
 * Configuração declarativa de cada entidade da API.
 *
 * - chave: nome usado tanto na URL (/usuarios) quanto na chave do JSON
 *          de request/response ("usuarios": {...}), conforme API.md.
 * - idCampo: nome do campo de identificador único retornado pela API.
 * - campos: define o formulário de cadastro/edição e as colunas da tabela.
 *     tipo pode ser: text | email | password | number | date | select | textarea
 */

const entidades = {
  usuarios: {
    chave: "usuarios",
    idCampo: "idUsuario",
    titulo: "Usuários",
    descricao: "Equipe da clínica e clientes cadastrados.",
    campos: [
      { nome: "nomeUsuario", label: "Nome completo", tipo: "text", obrigatorio: true },
      { nome: "email", label: "Email", tipo: "email", obrigatorio: true },
      {
        nome: "senha",
        label: "Senha",
        tipo: "password",
        obrigatorio: true,
        ajuda: "Por segurança a API não devolve a senha salva — digite-a novamente para confirmar o cadastro/atualização."
      },
      { nome: "tel", label: "Telefone", tipo: "text", obrigatorio: false },
      {
        nome: "tipoUsuario",
        label: "Tipo de usuário",
        tipo: "select",
        obrigatorio: false,
        opcoes: ["ADM", "VETERINARIO", "ATENDENTE", "CLIENTE"]
      },
      { nome: "data_nasc", label: "Data de nascimento", tipo: "date", obrigatorio: false }
    ]
  },

  especialidades: {
    chave: "especialidades",
    idCampo: "idEspecialidades",
    titulo: "Especialidades",
    descricao: "Setores/especialidades atendidas pela clínica.",
    campos: [
      { nome: "nome", label: "Nome", tipo: "text", obrigatorio: true },
      { nome: "descricao", label: "Descrição", tipo: "textarea", obrigatorio: false },
      { nome: "diaAtendimento", label: "Dia de atendimento", tipo: "text", obrigatorio: true },
      {
        nome: "idResponsavel",
        label: "ID do usuário responsável",
        tipo: "number",
        obrigatorio: true,
        ajuda: "Informe o idUsuario do veterinário responsável (veja a lista de Usuários)."
      }
    ]
  },

  campanhas: {
    chave: "campanhas",
    idCampo: "idCampanha",
    titulo: "Campanhas",
    descricao: "Campanhas de saúde animal (vacinação, castração etc.).",
    campos: [
      { nome: "titulo", label: "Título", tipo: "text", obrigatorio: true },
      { nome: "descricao", label: "Descrição", tipo: "textarea", obrigatorio: true },
      { nome: "dataCampanha", label: "Data da campanha", tipo: "date", obrigatorio: true },
      { nome: "localCampanha", label: "Local", tipo: "text", obrigatorio: true },
      { nome: "limiteVagas", label: "Limite de vagas", tipo: "number", obrigatorio: true },
      {
        nome: "statusCampanha",
        label: "Status",
        tipo: "select",
        obrigatorio: true,
        opcoes: ["ABERTO", "FECHADO"]
      }
    ]
  },

  agendamentos: {
    chave: "agendamentos",
    idCampo: "idAgendamentos",
    titulo: "Agendamentos",
    descricao: "Inscrições de clientes nas campanhas.",
    campos: [
      {
        nome: "usuarioId",
        label: "ID do usuário",
        tipo: "number",
        obrigatorio: true,
        ajuda: "idUsuario do cliente que fez o agendamento."
      },
      {
        nome: "campanhaId",
        label: "ID da campanha",
        tipo: "number",
        obrigatorio: true,
        ajuda: "idCampanha em que o cliente está se inscrevendo."
      },
      { nome: "dataAgendamento", label: "Data do agendamento", tipo: "date", obrigatorio: true },
      {
        nome: "presenca",
        label: "Presença",
        tipo: "select",
        obrigatorio: true,
        opcoes: ["PENDENTE", "CONFIRMADA", "REALIZADA", "CANCELADA"]
      }
    ]
  },

  mensagens: {
    chave: "mensagens",
    idCampo: "idMensagens",
    titulo: "Mensagens",
    descricao: "Avisos e comunicados enviados aos usuários.",
    campos: [
      { nome: "titulo", label: "Título", tipo: "text", obrigatorio: true },
      { nome: "conteudo", label: "Conteúdo", tipo: "textarea", obrigatorio: true },
      {
        nome: "usuarioId",
        label: "ID do autor",
        tipo: "number",
        obrigatorio: true,
        ajuda: "idUsuario de quem está publicando o aviso."
      },
      { nome: "dataPostagem", label: "Data da postagem", tipo: "date", obrigatorio: true }
    ]
  }
};

export default entidades;
