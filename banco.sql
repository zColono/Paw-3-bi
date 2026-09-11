create database Web;
use web;

create table usuarios(
id_usuarios int primary key auto_increment,
nome varchar(255) not null,
email varchar(255) not null unique,
senha varchar(255) not null,
tel varchar(255) unique,
data_nasc date,
tipo_usuario varchar(255) not null
);

create table especialidades(
id_especialidades int primary key auto_increment,
nome varchar(255) not null,
descricao varchar(255),
dia_atendimento varchar(255) not null,
id_responsavel int,
foreign key (id_responsavel) references usuarios(id_usuarios)
);

create table usuarios_especialidades(
id_usuarios_especialidades int primary key auto_increment,
id_usuario int,
id_especialidade int,
data_entrada date not null,
funcao varchar(255) not null,
foreign key (id_usuario) references usuarios(id_usuarios),
foreign key (id_especialidade) references especialidades(id_especialidades)
);

create table campanhas(
id_campanha int primary key auto_increment,
titulo varchar(255) not null,
descricao varchar(255) not null,
data_campanha date not null,
local_campanha varchar(255) not null,
limite_vagas int not null,
status_campanha varchar(255) not null
);

create table agendamentos(
id_agendamentos int primary key auto_increment,
usuario_id int,
campanha_id int,
data_agendamento date not null,
presenca varchar(255) not null,
foreign key (usuario_id)
references usuarios(id_usuarios),
foreign key (campanha_id) references campanhas(id_campanha),
unique(usuario_id, campanha_id)
);

create table mensagens(
id_mensagens int primary key auto_increment,
titulo varchar(255) not null,
conteudo varchar(255) not null,
usuario_id int,
data_postagem date not null,
foreign key (usuario_id) references usuarios(id_usuarios)
);

-- tabela usuarios
insert into usuarios (nome,email,senha,tel,data_nasc,tipo_usuario)values
("Thales" , "thales@gmail.com",12345,"(12)988139975","2009-07-29","ADM"),
("Pedro" , "Pedro@gmail.com",87459,"(12)98759845","2009-07-21","ADM"),
("Italo" , "italo@gmail.com",97200,"(12)99584575","2009-08-25","ADM"),
-- ATENDENTES
("Lucas" , "lucas@gmail.com",45612,"(12)991234567","2008-03-15","ATENDENTE"),
("Mariana" , "mariana@gmail.com",78945,"(12)992345678","2007-11-02","ATENDENTE"),
("Gabriel" , "gabriel@gmail.com",32178,"(12)993456789","2009-01-19","ATENDENTE"),

-- VETERINARIOS
("Fernanda" , "fernanda@gmail.com",65487,"(12)994567890","1998-06-10","VETERINARIO"),
("Ricardo" , "ricardo@gmail.com",95124,"(12)995678901","1995-09-27","VETERINARIO"),
("Patricia" , "patricia@gmail.com",75319,"(12)996789012","1992-12-05","VETERINARIO"),

-- CLIENTES (tutores de pets)
("Joao" , "joao@gmail.com",14785,"(12)997890123","2010-04-08","CLIENTE"),
("Beatriz" , "beatriz@gmail.com",25896,"(12)998901234","2008-08-30","CLIENTE"),
("Carlos" , "carlos@gmail.com",36914,"(12)999012345","2007-05-14","CLIENTE");

-- tabela especialidades
insert into especialidades (nome, descricao, dia_atendimento, id_responsavel) values
("Clinica Geral", "Consultas de rotina, checkups e orientacao geral para tutores", "Sexta-feira", 7),
("Cirurgia", "Procedimentos cirurgicos, castracoes e pequenas cirurgias", "Sabado", 8),
("Dermatologia", "Diagnostico e tratamento de doencas de pele em caes e gatos", "Domingo", 9),
("Cardiologia", "Avaliacao cardiaca e acompanhamento de animais idosos", "Quarta-feira", 7),
("Odontologia Veterinaria", "Limpeza dentaria e tratamento bucal de animais", "Domingo", 8);

-- usuarios_especialidades
insert into usuarios_especialidades
(id_usuario, id_especialidade, data_entrada, funcao) values

-- Clinica Geral
(1, 1, "2024-01-10", "Veterinario Responsavel"),
(7, 1, "2024-02-15", "Auxiliar Veterinario"),

-- Cirurgia
(5, 2, "2024-03-05", "Enfermeiro Veterinario"),
(8, 2, "2024-03-20", "Auxiliar Cirurgico"),

-- Dermatologia
(6, 3, "2024-04-12", "Veterinario"),
(9, 3, "2024-04-18", "Estagiario"),

-- Cardiologia
(10, 4, "2024-05-01", "Veterinario"),
(1, 4, "2024-05-08", "Responsavel"),

-- Odontologia Veterinaria
(12, 5, "2024-06-02", "Recepcionista"),
(13, 5, "2024-06-10", "Auxiliar");

--  tabela campanhas
insert into campanhas
(titulo, descricao, data_campanha, local_campanha, limite_vagas, status_campanha) values

("Campanha de Vacinacao Antirrabica", "Vacinacao gratuita para caes e gatos do bairro", "2026-07-15", "Patio da Clinica", 80, "ABERTO"),

("Mutirao de Castracao", "Cirurgias de castracao com valor social para tutores cadastrados", "2026-06-20", "Sala Cirurgica 1", 30, "ABERTO"),

("Semana do Check-up Pet", "Consultas preventivas e exames de rotina com desconto", "2026-06-10", "Consultorios 1 e 2", 40, "ENCERRADO"),

("Campanha de Vermifugacao", "Aplicacao gratuita de vermifugo para animais ate 1 ano", "2026-07-01", "Recepcao da Clinica", 100, "ABERTO"),

("Feira de Adocao Responsavel", "Encontro entre animais resgatados e futuros tutores", "2026-08-05", "Estacionamento da Clinica", 50, "PLANEJADO");

-- tabela agendamentos
insert into agendamentos
(usuario_id, campanha_id, data_agendamento, presenca) values

(1, 1, "2026-06-01", "CONFIRMADA"),
(5, 1, "2026-06-02", "CONFIRMADA"),
(6, 1, "2026-06-02", "PENDENTE"),

(7, 2, "2026-06-03", "CONFIRMADA"),
(8, 2, "2026-06-03", "CONFIRMADA"),
(9, 2, "2026-06-04", "AUSENTE"),

(10, 3, "2026-06-05", "CONFIRMADA"),
(11, 3, "2026-06-05", "PENDENTE"),

(12, 4, "2026-06-06", "CONFIRMADA"),
(13, 4, "2026-06-06", "CONFIRMADA"),

(14, 5, "2026-06-07", "PENDENTE"),
(15, 5, "2026-06-07", "CONFIRMADA");

-- tabela mensagens
insert into mensagens
(titulo, conteudo, usuario_id, data_postagem) values

("Bem-vindos", "Sejam todos bem-vindos a clinica veterinaria.", 1, "2026-06-01"),

("Manutencao do Consultorio", "O consultorio 2 ficara fechado para manutencao na sexta-feira.", 10, "2026-06-02"),

("Campanha de Vacinacao", "As inscricoes para a campanha de vacinacao continuam abertas.", 11, "2026-06-03"),

("Mutirao de Castracao", "Vagas limitadas para o mutirao de castracao deste mes.", 12, "2026-06-04"),

("Feira de Adocao", "Neste sabado teremos a feira de adocao responsavel.", 5, "2026-06-05"),

("Aviso aos Tutores", "Os horarios de atendimento da clinica foram atualizados.", 6, "2026-06-06");
