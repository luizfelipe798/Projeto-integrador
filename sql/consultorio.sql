CREATE DATABASE IF NOT EXISTS sailus;
USE sailus;
DROP DATABASE sailus;

CREATE TABLE Usuario
(
	id           INT                AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(150)       NOT NULL,
    email        VARCHAR(100)       NOT NULL UNIQUE,
    tipo         VARCHAR(20)        NOT NULL,
    telefone     CHAR(15)           NOT NULL,
    senha        VARCHAR(255)       NOT NULL,
    ativo        INT                NOT NULL
);

CREATE TABLE Funcionario
(
	id                        INT                 PRIMARY KEY,
    dataContratacao           DATE                NOT NULL,
	turno                     VARCHAR(5)          NOT NULL,
    
    FOREIGN KEY(id)    REFERENCES    Usuario(id)
);

CREATE TABLE Medico 
(
    id                          INT              PRIMARY KEY,
    crm                         CHAR(10)         NOT NULL UNIQUE,
    especialidade               VARCHAR(40)      NOT NULL,
    plantonista                 VARCHAR(3)       NOT NULL,
    
    FOREIGN KEY(id)      REFERENCES       Usuario(id)
);

CREATE TABLE Paciente
(
    id                 INT              AUTO_INCREMENT PRIMARY KEY,
    nome               VARCHAR(250)     NOT NULL,
    email              VARCHAR(100)     NOT NULL UNIQUE,
    telefone           CHAR(15)         NOT NULL,
    dataNascimento     DATE             NOT NULL,
    cpf                CHAR(14)         NOT NULL UNIQUE,
    genero             VARCHAR(15)      NOT NULL,
    excluido           BOOL             NOT NULL DEFAULT FALSE
);

/*Consulta - Relacionamento entre Medico e Paciente e que se relaciona com Funcionario*/
CREATE TABLE Consulta
(
    id              INT             AUTO_INCREMENT PRIMARY KEY,
    horario         CHAR(5)         NOT NULL,
    observacao      VARCHAR(500)    NOT NULL,
    stattus         VARCHAR(20)     NOT NULL,
    valor           NUMERIC(5,2)    NOT NULL,
    tipo            VARCHAR(20)     NOT NULL,
    idMedico        INT             NOT NULL,
    idPaciente      INT             NOT NULL,
    excluida        BOOL            NOT NULL DEFAULT FALSE,

    FOREIGN KEY(idMedico)     REFERENCES   Medico(id),
    FOREIGN KEY(idPaciente)   REFERENCES   Paciente(id)
);

CREATE TABLE Atestado 
(
    id              INT             AUTO_INCREMENT PRIMARY KEY,
    dtEmissao       DATE            NOT NULL,
    descricao       VARCHAR(500)    NOT NULL,
    motivo          VARCHAR(150)    NOT NULL,
    numeroDias      INT             NOT NULL,
    idPaciente      INT             NOT NULL,
    idMedico        INT             NOT NULL,

    FOREIGN KEY(idPaciente)  REFERENCES  Paciente(id),
    FOREIGN KEY(idMedico)    REFERENCES  Medico(id)
);

/*Relacionamento entre Funcionario e Consulta*/
CREATE TABLE HistFuncConsulta
(
	id                    INT              AUTO_INCREMENT PRIMARY KEY,
    tipoAcao              VARCHAR(50)      NOT NULL,
    dtAcao                DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idFuncionario         INT              NOT NULL,
    idConsulta            INT              NOT NULL,
    
    FOREIGN KEY(idFuncionario)    REFERENCES    Funcionario(id),
    FOREIGN KEY(idConsulta)       REFERENCES    Consulta(id)
);

/*Relacionamento entre Funcionario e Paciente*/
CREATE TABLE HistFuncPaciente
(
	id                    INT              AUTO_INCREMENT PRIMARY KEY,
    tipoAcao              VARCHAR(50)      NOT NULL,
    dtAcao                DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idFuncionario         INT              NOT NULL,
    idPaciente            INT              NOT NULL,
    
    FOREIGN KEY(idFuncionario)    REFERENCES    Funcionario(id),
    FOREIGN KEY(idPaciente)       REFERENCES    Paciente(id)
);

DESC Usuario;
DESC Funcionario;
DESC Medico;
DESC Paciente;
DESC Consulta;
DESC Atestado;
DESC HistFuncConsulta;
DESC HistFuncPaciente;

SELECT * FROM Usuario;
SELECT * FROM Funcionario;
SELECT * FROM Medico;
SELECT * FROM Paciente WHERE excluido = 0;
SELECT * FROM Paciente WHERE excluido = 1;
SELECT * FROM Consulta;
SELECT * FROM Atestado;
SELECT * FROM HistFuncConsulta;
SELECT * FROM HistFuncPaciente;

SELECT id FROM Usuario;

DELETE FROM Usuario;
DELETE FROM Funcionario;
DELETE FROM Medico;
DELETE FROM Paciente;
DELETE FROM Consulta;
DELETE FROM Atestado;
DELETE FROM HistFuncConsulta;
DELETE FROM HistFuncPaciente;

DROP TABLE Usuario;
DROP TABLE Funcionario;
DROP TABLE Medico;
DROP TABLE Paciente;
DROP TABLE Consulta;
DROP TABLE Atestado;
DROP TABLE HistFuncConsulta;
DROP TABLE HistFuncPaciente;

/* INSERT PACIENTES */

INSERT INTO Paciente (nome, email, telefone, dataNascimento, cpf, genero) 
VALUES
('Ana Clara da Silva', 'ana.clara.silva@saudeonline.com.br', '(11) 98765-4321', '1995-03-10', '123.456.789-01', 'Feminino'),
('João Miguel Souza', 'joao.miguel.souza@farmaemail.com.br', '(21) 99123-4567', '1988-11-25', '987.654.321-09', 'Masculino'),
('Maria Eduarda Santos', 'maria.eduarda@telemedicina.com.br', '(31) 98345-6789', '1976-07-14', '456.789.012-34', 'Feminino'),
('Pedro Henrique Lima', 'pedro.lima@cuidadosja.com.br', '(41) 99567-8901', '2001-01-20', '789.012.345-67', 'Masculino'),
('Gabriela Ferreira Costa', 'gabi.ferreira@medgrupo.com.br', '(51) 98678-9012', '1965-04-03', '012.345.678-90', 'Feminino'),
('Rafael de Oliveira Junior', 'rafael.ojunior@vidaplena.com.br', '(61) 98234-5678', '1980-09-18', '345.678.901-23', 'Masculino'),
('Larissa Gomes Andrade', 'lari.andrade@consultoriox.com.br', '(71) 99456-7890', '1999-12-05', '678.901.234-56', 'Feminino'),
('Lucas Vieira Rocha', 'lucas.rocha@bemestar.com.br', '(81) 98111-2222', '1959-06-22', '901.234.567-89', 'Masculino'),
('Isabela Freitas Melo', 'isabela.melo@saudebr.com.br', '(92) 99333-4444', '1970-02-28', '234.567.890-12', 'Feminino'),
('Daniel Alves Martins', 'daniel.martins@meudoutor.com.br', '(17) 98555-6666', '1985-08-01', '567.890.123-45', 'Masculino'),
('Camila Ribeiro Souza', 'camilarsouza@bemtevi.com.br', '(19) 98777-8888', '1992-05-12', '890.123.456-78', 'Feminino'),
('Matheus Lacerda Pinto', 'matheuslpinto@teleconsulta.com.br', '(24) 99222-3333', '2003-10-09', '023.456.789-01', 'Masculino'),
('Viviane Torres Neves', 'viviane.neves@cuidadoaqui.com.br', '(27) 98123-4000', '1955-01-30', '356.789.012-34', 'Feminino'),
('Gustavo Morais Barreto', 'gustavo.morais@clinicageral.com.br', '(34) 99345-6000', '1972-04-16', '689.012.345-67', 'Masculino'),
('Helena Castro Nunes', 'helena.nunes@servicosaude.com.br', '(47) 98567-8000', '1983-11-08', '923.456.789-01', 'Feminino'),
('Eduardo Barbosa Farias', 'eduardo.farias@onlinecare.com.br', '(48) 99678-9000', '1990-03-24', '156.789.012-34', 'Masculino'),
('Juliana Mendes Freitas', 'ju.mendes@saudeconecta.com.br', '(62) 98789-0100', '1968-12-19', '489.012.345-67', 'Feminino'),
('Felipe Viana Queiroz', 'felipe.queiroz@grupomedico.com.br', '(65) 99101-2300', '1981-06-07', '723.456.789-01', 'Masculino'),
('Renata Cardoso Nogueira', 'renata.nogueira@minhasaude.com.br', '(79) 98234-5600', '1997-02-11', '056.789.012-34', 'Feminino'),
('Thiago Silva Couto', 'thiago.couto@apoiosaudavel.com.br', '(83) 99456-7800', '2005-08-29', '389.012.345-67', 'Masculino'),
('Bruna Almeida Pires', 'bruna.pires@teleatendimento.com.br', '(85) 98567-8900', '1962-10-02', '623.456.789-01', 'Feminino'),
('Ricardo Campos Assis', 'ricardo.assis@cuidarbem.com.br', '(91) 99678-9000', '1977-05-17', '956.789.012-34', 'Masculino'),
('Alice Dantas Coelho', 'alice.dantas@saudeurgente.com.br', '(98) 98123-4500', '1993-01-06', '189.012.345-67', 'Feminino'),
('Vinicius Gomes Dias', 'vinicius.g.dias@ajudamedica.com.br', '(13) 99234-5600', '1986-07-27', '423.456.789-01', 'Masculino'),
('Sophia Lopes Vieira', 'sophia.vieira@suasaude.com.br', '(16) 98345-6700', '1957-09-04', '756.789.012-34', 'Feminino'),
('Guilherme Neves Brito', 'guilherme.brito@atendimentohumano.com.br', '(22) 99456-7800', '1974-03-21', '089.012.345-67', 'Masculino'),
('Eloá Pires Sampaio', 'eloa.sampaio@pazesaude.com.br', '(28) 98567-8900', '2000-11-15', '323.456.789-01', 'Feminino'),
('Antônio Carlos Santos', 'antonio.santos@consultavital.com.br', '(38) 99678-9000', '1969-04-26', '656.789.012-34', 'Masculino'),
('Laura Martins Silveira', 'laura.silveira@saudemaxima.com.br', '(43) 98789-0100', '1982-12-03', '989.012.345-67', 'Feminino'),
('Caio Mendes Barroso', 'caio.barroso@primeiropasso.com.br', '(44) 99101-2300', '1998-06-10', '223.456.789-01', 'Masculino'),
('Brenda Rocha Aguiar', 'brenda.aguiar@caminhosuave.com.br', '(54) 98234-5600', '1961-08-20', '556.789.012-34', 'Feminino'),
('Erick Souza Freitas', 'erick.freitas@conexaomedica.com.br', '(55) 99345-6700', '1975-02-09', '889.012.345-67', 'Masculino'),
('Marina Costa Pereira', 'marina.pereira@saudelivre.com.br', '(67) 98567-8900', '1991-10-23', '122.345.678-90', 'Feminino'),
('Thiago Fernandes Reis', 'thiago.reis@saudeleve.com.br', '(69) 99678-9000', '2004-04-01', '455.678.901-23', 'Masculino'),
('Luana Sales Oliveira', 'luana.oliveira@appsaude.com.br', '(73) 98789-0100', '1966-07-16', '788.901.234-56', 'Feminino'),
('Diego Abreu Teixeira', 'diego.teixeira@doconline.com.br', '(75) 99101-2300', '1984-05-25', '021.234.567-89', 'Masculino'),
('Vanessa Lopes Martins', 'vanessa.martins@seucuidado.com.br', '(84) 98234-5600', '1996-09-07', '354.567.890-12', 'Feminino'),
('Alexandre Bezerra Cruz', 'alexandre.cruz@bemtequero.com.br', '(88) 99345-6700', '1963-01-08', '687.890.123-45', 'Masculino'),
('Patrícia Neves Lins', 'patricia.lins@clinicasol.com.br', '(93) 98567-8900', '1979-11-29', '920.123.456-78', 'Feminino'),
('Fábio Vilar Borges', 'fabio.borges@cuidadototal.com.br', '(96) 99678-9000', '1994-04-05', '153.456.789-01', 'Masculino'),
('Silvia Pires Machado', 'silvia.machado@saudepresente.com.br', '(12) 98789-0100', '1958-12-11', '486.789.012-34', 'Feminino'),
('Paulo Cesar Gomes', 'paulo.gomes@meubemestar.com.br', '(14) 99101-2300', '1973-06-27', '819.012.345-67', 'Masculino'),
('Tais Ramos Vasconcelos', 'tais.vasconcelos@conectasaude.com.br', '(29) 98234-5600', '2002-08-14', '042.345.678-90', 'Feminino'),
('Mauro Diniz Freitas', 'mauro.freitas@cuidedevc.com.br', '(33) 99345-6700', '1967-03-02', '375.678.901-23', 'Masculino'),
('Julia Mendes Silva', 'julia.silva@telemedico.com.br', '(45) 98567-8900', '1987-09-19', '708.901.234-56', 'Feminino'),
('Vitor Hugo Nogueira', 'vitor.hugo@saudecerta.com.br', '(49) 99678-9000', '1999-01-28', '941.234.567-89', 'Masculino'),
('Ariel Xavier Dutra', 'ariel.dutra@clinicaboa.com.br', '(53) 98789-0100', '1971-10-06', '274.567.890-12', 'Não-Binário'),
('André Martins Ramos', 'andre.ramos@bemviver.com.br', '(56) 99101-2300', '1960-05-24', '607.890.123-45', 'Masculino'),
('Cláudia Regina Barros', 'claudia.barros@vidasaudavel.com.br', '(77) 98234-5600', '1989-11-04', '840.123.456-78', 'Feminino'),
('Júlio César Azevedo', 'julio.azevedo@atendimentotop.com.br', '(86) 99345-6700', '1956-02-13', '173.456.789-01', 'Masculino');

INSERT INTO Paciente (nome, email, telefone, dataNascimento, cpf, genero) VALUES
('Heloísa Bernardes Lopes', 'heloisa.lopes@saudefortaleza.com.br', '(85) 98701-1000', '1990-06-15', '307.214.569-11', 'Feminino'),
('Bernardo Gonçalves Reis', 'bernardo.reis@vidacerta.com.br', '(71) 99112-2000', '1984-09-03', '741.852.963-22', 'Masculino'),
('Cecília Duarte Aguiar', 'cecilia.aguiar@consultoriavital.com.br', '(62) 98323-3000', '1978-02-28', '159.357.864-33', 'Feminino'),
('Lorenzo da Costa Moura', 'lorenzo.moura@medprime.com.br', '(51) 99534-4000', '2000-12-19', '852.741.963-44', 'Masculino'),
('Alícia Teixeira Ramos', 'alicia.ramos@bemestarhoje.com.br', '(41) 98645-5000', '1967-04-11', '258.963.147-55', 'Feminino'),
('Gabriel Pires Cavalheiro', 'gabriel.cavalheiro@saudexpress.com.br', '(31) 98256-6000', '1981-08-27', '963.147.258-66', 'Masculino'),
('Manuela Nunes Carvalho', 'manuela.carvalho@conexaosocial.com.br', '(21) 99467-7000', '1993-01-09', '369.258.147-77', 'Feminino'),
('Davi Rocha Guedes', 'davi.guedes@clinicapopular.com.br', '(11) 99578-8000', '1958-05-21', '693.471.258-88', 'Masculino'),
('Lívia Dias Cordeiro', 'livia.cordeiro@teleconsulta24h.com.br', '(83) 98789-9000', '1975-10-04', '951.753.846-99', 'Feminino'),
('Igor Barroso Fonseca', 'igor.fonseca@saudeintegrada.com.br', '(92) 99100-1111', '2004-03-25', '147.258.369-00', 'Masculino'),
('Emanuela Gomes Toledo', 'emanuela.toledo@suaclinica.com.br', '(91) 98311-2222', '1969-11-02', '456.789.123-10', 'Feminino'),
('Hugo Sales Rodrigues', 'hugo.rodrigues@vidaleve.com.br', '(81) 99422-3333', '1987-07-16', '789.123.456-20', 'Masculino'),
('Giovanna Mendes Freitas', 'gio.freitas@medavancada.com.br', '(79) 98633-4444', '1998-05-30', '012.345.678-30', 'Feminino'),
('Vicente Ferreira Mattos', 'vicente.mattos@cuidadointegral.com.br', '(67) 99744-5555', '1956-12-08', '345.678.901-40', 'Masculino'),
('Rebeca Lima Azevedo', 'rebeca.azevedo@saudebrazil.com.br', '(61) 98555-6666', '1973-09-17', '678.901.234-50', 'Feminino'),
('Henrique Pereira Dutra', 'henrique.dutra@bemestarglobal.com.br', '(54) 99666-7777', '1995-02-05', '901.234.567-60', 'Masculino'),
('Clarice Rocha Monteiro', 'clarice.monteiro@cuidadovip.com.br', '(47) 98277-8888', '2001-08-01', '234.567.890-70', 'Feminino'),
('Benício Souza Tavares', 'benicio.tavares@atendimentodigital.com.br', '(48) 99388-9999', '1964-03-14', '567.890.123-80', 'Masculino'),
('Beatriz Costa Oliveira', 'beatriz.oliveira@saudeagora.com.br', '(34) 98599-0000', '1989-10-22', '890.123.456-90', 'Feminino'),
('Marcelo Nogueira Guedes', 'marcelo.guedes@foconasaude.com.br', '(38) 99600-1010', '1970-01-29', '123.456.789-02', 'Masculino'),
('Brenda Silva Ferreira', 'brenda.ferreira@appmedico.com.br', '(27) 98711-2020', '1955-07-07', '456.789.012-12', 'Feminino'),
('Samuel Toledo Camargo', 'samuel.camargo@clinicaexata.com.br', '(22) 99822-3030', '1997-12-03', '789.012.345-23', 'Masculino'),
('Tatiana Viana Castro', 'tatiana.castro@saudecapital.com.br', '(19) 99933-4040', '1986-04-20', '012.345.678-34', 'Feminino'),
('Murilo Dias Almeida', 'murilo.almeida@cuidedoseuproximo.com.br', '(17) 98044-5050', '2003-11-10', '345.678.901-45', 'Masculino'),
('Enzo Freitas Rocha', 'enzo.rocha@teleatendimento.com.br', '(21) 99266-7070', '1976-03-18', '901.234.567-67', 'Masculino'),
('Maitê Barros Pinto', 'maite.pinto@grupocuidados.com.br', '(31) 98377-8080', '1991-07-24', '234.567.890-78', 'Feminino'),
('Otávio Pires Santana', 'otavio.santana@saudegeral.com.br', '(41) 99488-9090', '1968-10-05', '567.890.123-89', 'Masculino'),
('Pâmela Souza Cunha', 'pamela.cunha@medicoagora.com.br', '(51) 98599-0101', '1983-04-12', '890.123.456-91', 'Feminino'),
('Luan Alves Machado', 'luan.machado@saudeparaiba.com.br', '(83) 99600-1212', '2002-09-29', '123.456.789-13', 'Masculino'),
('Sofia Duarte Barros', 'sofia.barros@cuidamos.com.br', '(71) 98711-2323', '1959-12-26', '456.789.012-24', 'Feminino'),
('Rayan Gomes Teixeira', 'rayan.teixeira@saudefamiliar.com.br', '(62) 99822-3434', '1971-06-08', '789.012.345-35', 'Masculino'),
('Flávia Ribeiro Lemos', 'flavia.lemos@clinicabem.com.br', '(55) 99933-4545', '1994-01-03', '012.345.678-46', 'Feminino'),
('Guilherme Neves Brito', 'gui.brito@atendimentomedico.com.br', '(44) 98044-5656', '1980-05-19', '345.678.901-57', 'Masculino'),
('Luiza Martins Assis', 'luiza.assis@bemestartotal.com.br', '(33) 98155-6767', '1966-02-14', '678.901.234-68', 'Feminino'),
('Caleb Diniz Freitas', 'caleb.freitas@minhaclinica.com.br', '(28) 99266-7878', '1996-10-28', '901.234.567-79', 'Masculino'),
('Yasmin Torres Vieira', 'yasmin.vieira@vidasaude.com.br', '(16) 99377-8989', '2005-04-07', '234.567.890-80', 'Feminino'),
('Ariel Oliveira Castro', 'ariel.castro@saudeprime.com.br', '(98) 98488-9091', '1974-11-23', '567.890.123-92', 'Não-Binário'),
('Ícaro Lins Barreto', 'icaro.barreto@saudefacil.com.br', '(96) 99599-0202', '1963-08-06', '890.123.456-03', 'Masculino'),
('Melissa Ramos Costa', 'melissa.costa@telecuidado.com.br', '(93) 98600-1313', '1988-03-29', '123.456.789-14', 'Feminino'),
('Noah Lima Silva', 'noah.silva@vidanova.com.br', '(84) 99711-2424', '2001-01-01', '456.789.012-25', 'Masculino'),
('Diana Cunha Lopes', 'diana.lopes@consultasja.com.br', '(88) 98822-3535', '1957-07-13', '789.012.345-36', 'Feminino'),
('Rodrigo Alves Barreto', 'rodrigo.barreto@cuidedevoce.com.br', '(99) 99933-4646', '1979-04-26', '012.345.678-47', 'Masculino'),
('Camile Souza Pires', 'camile.pires@grupomedicobr.com.br', '(73) 98044-5757', '1992-12-07', '345.678.901-58', 'Feminino'),
('Elias Martins Neves', 'elias.neves@atendimentohumanizado.com.br', '(75) 99155-6868', '1965-10-10', '678.901.234-69', 'Masculino'),
('Leticia Gomes Vieira', 'leticia.vieira@saudeebem.com.br', '(68) 98266-7979', '1985-02-18', '901.234.567-70', 'Feminino'),
('Alex Bezerra Cunha', 'alex.cunha@vidaboa.com.br', '(63) 99377-8080', '2003-05-09', '234.567.890-81', 'Masculino'),
('Isadora Pires Mello', 'isa.mello@conexaocare.com.br', '(53) 98488-9191', '1970-03-01', '567.890.123-93', 'Feminino'),
('Caetano Dias Lins', 'caetano.lins@bemestarvirtual.com.br', '(54) 99599-0303', '1961-11-20', '890.123.456-04', 'Masculino'),
('Eva Rocha Santana', 'eva.santana@cuidadoeconfianca.com.br', '(49) 98600-1414', '1998-07-25', '123.456.789-15', 'Feminino');