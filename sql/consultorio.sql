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
    ativo        TINYINT(4)         NOT NULL
);

DESC Usuario;
SELECT * FROM Usuario;
DELETE FROM Usuario;
DROP TABLE Usuario;

CREATE TABLE Funcionario
(
	id                        INT                 PRIMARY KEY,
    dataContratacao           DATE                NOT NULL,
	turno                     VARCHAR(5)          NOT NULL,
    
    FOREIGN KEY(id)    REFERENCES    Usuario(id)
);

DESC Funcionario;
SELECT * FROM Funcionario;
DELETE FROM Funcionario;

CREATE TABLE Medico 
(
    id                          INT              PRIMARY KEY,
    crm                         CHAR(10)         NOT NULL UNIQUE,
    especialidade               VARCHAR(40)      NOT NULL,
    plantonista                 VARCHAR(3)       NOT NULL,
    
    FOREIGN KEY(id)      REFERENCES       Usuario(id)
);

DESC Medico;
SELECT * FROM Medico;
DELETE FROM Medico;

CREATE TABLE Paciente
(
    id                 INT              AUTO_INCREMENT PRIMARY KEY,
    nome               VARCHAR(250)     NOT NULL,
    email              VARCHAR(100)     NOT NULL,
    telefone           CHAR(14)         NOT NULL,
    dataNascimento     DATE             NOT NULL,
    cpf                CHAR(14)         NOT NULL,
    genero             VARCHAR(15)      NOT NULL
);

DESC Paciente;
SELECT * FROM Paciente;
DELETE FROM Paciente;

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

    FOREIGN KEY(idMedico)     REFERENCES   Medico(id),
    FOREIGN KEY(idPaciente)   REFERENCES   Paciente(id)
);

DESC Consulta;
SELECT * FROM Consulta;
DELETE FROM Consulta;

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

DESC Atestado;
SELECT * FROM Atestado;
DELETE FROM Atestado;

/*Relacionamento entre Funcionario e Consulta*/
CREATE TABLE HistFuncConsulta
(
	id                    INT              AUTO_INCREMENT PRIMARY KEY,
    tipoAcao              VARCHAR(50)      NOT NULL,
    dtAcao                DATE             NOT NULL,
    idFuncionario         INT              NOT NULL,
    idConsulta            INT              NOT NULL,
    
    FOREIGN KEY(idFuncionario)    REFERENCES    Funcionario(id),
    FOREIGN KEY(idConsulta)       REFERENCES    Consulta(id)
);

DESC HistFuncConsulta;
SELECT * FROM HistFuncConsulta;
DELETE FROM HistFuncConsulta;

/*Relacionamento entre Funcionario e Paciente*/
CREATE TABLE HistFuncPaciente
(
	id                    INT              AUTO_INCREMENT PRIMARY KEY,
    tipoAcao              VARCHAR(50)      NOT NULL,
    dtAcao                DATE             NOT NULL,
    idFuncionario         INT              NOT NULL,
    idPaciente            INT              NOT NULL,
    
    FOREIGN KEY(idFuncionario)    REFERENCES    Funcionario(id),
    FOREIGN KEY(idPaciente)       REFERENCES    Paciente(id)
);

DESC HistFuncPaciente;
SELECT * FROM HistFuncPaciente;
DELETE FROM HistFuncPaciente;