/**
 * Renderiza os campos de um formulário dentro de `container` (um <form>),
 * a partir da lista `campos` definida em entidades.js.
 *
 * `valores` (opcional) preenche os campos — usado na edição.
 * `prefixoId` evita colisão de ids quando o mesmo formulário aparece
 * mais de uma vez na página (ex: cadastro + modal de edição).
 */
export function renderizarCampos(container, campos, valores = {}, prefixoId = "campo") {
  container.innerHTML = "";

  campos.forEach((campo) => {
    const wrapper = document.createElement("div");
    const idCampo = `${prefixoId}_${campo.nome}`;

    const label = document.createElement("label");
    label.setAttribute("for", idCampo);
    label.textContent = campo.label + (campo.obrigatorio ? " *" : "");
    wrapper.appendChild(label);

    let input;

    if (campo.tipo === "select") {
      input = document.createElement("select");
      const optVazia = document.createElement("option");
      optVazia.value = "";
      optVazia.textContent = "Selecione...";
      input.appendChild(optVazia);

      campo.opcoes.forEach((opcao) => {
        const opt = document.createElement("option");
        opt.value = opcao;
        opt.textContent = opcao;
        input.appendChild(opt);
      });
    } else if (campo.tipo === "textarea") {
      input = document.createElement("textarea");
    } else {
      input = document.createElement("input");
      input.type = campo.tipo;
    }

    input.id = idCampo;
    input.name = campo.nome;

    if (campo.obrigatorio) {
      input.required = true;
    }

    const valorAtual = valores[campo.nome];
    if (valorAtual !== undefined && valorAtual !== null) {
      input.value = valorAtual;
    }

    wrapper.appendChild(input);

    if (campo.ajuda) {
      const ajuda = document.createElement("small");
      ajuda.style.display = "block";
      ajuda.style.marginTop = "4px";
      ajuda.style.color = "#5b6b64";
      ajuda.textContent = campo.ajuda;
      wrapper.appendChild(ajuda);
    }

    container.appendChild(wrapper);
  });
}

/**
 * Lê os valores atuais de um formulário renderizado por renderizarCampos
 * e devolve um objeto pronto para ser enviado no corpo da requisição,
 * já convertendo campos numéricos.
 */
export function coletarValores(container, campos, prefixoId = "campo") {
  const objeto = {};

  campos.forEach((campo) => {
    const input = container.querySelector(`#${prefixoId}_${campo.nome}`);
    let valor = input.value;

    if (campo.tipo === "number" && valor !== "") {
      valor = Number(valor);
    }

    objeto[campo.nome] = valor;
  });

  return objeto;
}
