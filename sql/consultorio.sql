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
    email              VARCHAR(100)     NOT NULL,
    telefone           CHAR(14)         NOT NULL,
    dataNascimento     DATE             NOT NULL,
    cpf                CHAR(14)         NOT NULL,
    genero             VARCHAR(15)      NOT NULL
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
    dtAcao                DATE             NOT NULL,
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
    dtAcao                DATE             NOT NULL,
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
SELECT * FROM Paciente;
SELECT * FROM Consulta;
SELECT * FROM Atestado;
SELECT * FROM HistFuncConsulta;
SELECT * FROM HistFuncPaciente;

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