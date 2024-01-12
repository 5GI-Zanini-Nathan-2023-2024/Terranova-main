USE sql11676321;

CREATE TABLE Utenti (
    Email varchar(128) NOT NULL PRIMARY KEY,
	Password varchar(128) NOT NULL,
	Nome varchar(20) NOT NULL,
	Cognome varchar(20) NOT NULL,
	Telefono char(10) NOT NULL,
	Citta varchar(30) NOT NULL,
	Via varchar(50) NOT NULL,
	Nascita DATE NOT NULL
)