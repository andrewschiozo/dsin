CREATE DATABASE u244595210_dsin;
USE u244595210_dsin;
CREATE TABLE usuario(  
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone INT NOT NULL,
    perfil VARCHAR(20) NOT NULL
);

CREATE TABLE servico(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255)
);

CREATE TABLE servico_situacao(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(255)
);

CREATE TABLE agendamento(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    servico_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data DATETIME NOT NULL
);
ALTER TABLE agendamento ADD CONSTRAINT fk_servico_id FOREIGN KEY (servico_id) REFERENCES servico(id);
ALTER TABLE agendamento ADD CONSTRAINT fk_usuario_id FOREIGN KEY (usuario_id) REFERENCES usuario(id);
CREATE TABLE agendamento_log(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    agendamento_id INT NOT NULL,
    servico_situacao_id INT NOT NULL,
    data DATETIME NOT NULL
);
ALTER TABLE agendamento_log ADD CONSTRAINT fk_agendamento_id FOREIGN KEY (agendamento_id) REFERENCES agendamento(id);
ALTER TABLE agendamento_log ADD CONSTRAINT fk_servico_situacao_id FOREIGN KEY (servico_situacao_id) REFERENCES servico_situacao(id);
