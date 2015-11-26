-- DROP TABLE Nastroj CASCADE CONSTRAINT;
-- DROP TABLE Hraje_na CASCADE CONSTRAINT;
-- DROP TABLE Hudebnik CASCADE CONSTRAINT;
-- DROP TABLE Vystupuje_na CASCADE CONSTRAINT;
-- DROP TABLE Koncert CASCADE CONSTRAINT;
-- DROP TABLE Slozen_z CASCADE CONSTRAINT;
-- DROP TABLE Skladba CASCADE CONSTRAINT;
-- DROP TABLE Autor CASCADE CONSTRAINT;
-- DROP TABLE Ma_nastudovano CASCADE CONSTRAINT;
-- DROP TABLE Typ CASCADE CONSTRAINT;
-- DROP TABLE Hraje_v CASCADE CONSTRAINT;

CREATE TABLE IF NOT EXISTS Typ(
  ttype VARCHAR(20) NOT NULL,
  PRIMARY KEY (ttype)
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Nastroj(
  datum_vyroby DATE,
  vyrobce VARCHAR(30),
  dat_posl_revize DATE,
  dat_posl_vymeny DATE,
  vymeneno VARCHAR(20),
  vyrobni_cislo INT,
  ttype VARCHAR(20),
  PRIMARY KEY (vyrobni_cislo),
  FOREIGN KEY(ttype) REFERENCES Typ(ttype) ON DELETE CASCADE
) ENGINE=INNODB; 
CREATE TABLE IF NOT EXISTS Hudebnik(
  rodne_cislo CHAR(11),
  jmeno VARCHAR(20),
  prijmeni VARCHAR(20),
  heslo_hash VARCHAR(125),   
  PRIMARY KEY (rodne_cislo)
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Autor(
  ID_autora INT,
  jmeno VARCHAR(40),
  zacatek_tvorby INT,
  konec_tvorby INT,
  styl VARCHAR(20),
  PRIMARY KEY (ID_autora)
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Koncert(
  ID_koncertu INT,
  nazev_koncertu VARCHAR(30),
  datum_a_cas DATETIME,
  mesto VARCHAR(30),
  adresa VARCHAR(50),
  PRIMARY KEY (ID_koncertu)
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Skladba(
  ID_skladby INT,
  nazev VARCHAR(30),
  delka INT,
  ID_autora INT,
  PRIMARY KEY (ID_skladby),
  FOREIGN KEY(ID_autora) REFERENCES Autor(ID_autora) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Hraje_na(
  vyrobni_cislo INT,
  rodne_cislo CHAR(11),
  PRIMARY KEY (vyrobni_cislo, rodne_cislo),
  FOREIGN KEY(vyrobni_cislo) REFERENCES Nastroj(vyrobni_cislo) ON DELETE CASCADE,
  FOREIGN KEY(rodne_cislo) REFERENCES Hudebnik(rodne_cislo) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Vystupuje_na(
  rodne_cislo CHAR(11),
  ID_koncertu INT,
  PRIMARY KEY (rodne_cislo, ID_koncertu),
  FOREIGN KEY(rodne_cislo) REFERENCES Hudebnik(rodne_cislo) ON DELETE CASCADE,
  FOREIGN KEY(ID_koncertu) REFERENCES Koncert(ID_koncertu) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Slozen_z(
  ID_koncertu INT,
  ID_skladby INT,
  poradi INT,
  PRIMARY KEY (ID_koncertu, ID_skladby),
  FOREIGN KEY(ID_koncertu) REFERENCES Koncert(ID_koncertu) ON DELETE CASCADE,
  FOREIGN KEY(ID_skladby) REFERENCES Skladba(ID_skladby) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Ma_nastudovano(
  rodne_cislo CHAR(11),
  ID_skladby INT,
  PRIMARY KEY (rodne_cislo, ID_skladby),
  FOREIGN KEY(rodne_cislo) REFERENCES Hudebnik(rodne_cislo) ON DELETE CASCADE,
  FOREIGN KEY(ID_skladby) REFERENCES Skladba(ID_skladby) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Hraje_v(
  ttype VARCHAR(20),
  ID_skladby INT,
  pocet INT,
  PRIMARY KEY (ttype, ID_skladby),
  FOREIGN KEY(ttype) REFERENCES Typ(ttype) ON DELETE CASCADE,
  FOREIGN KEY(ID_skladby) REFERENCES Skladba(ID_skladby) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE IF NOT EXISTS Uzivatel(
  login VARCHAR(20) NOT NULL,
  heslo_hash VARCHAR(125),   
  role VARCHAR(20), 
  info VARCHAR(50),
  PRIMARY KEY (login)
) ENGINE=INNODB;

INSERT INTO Hudebnik
VALUES ('860504/6584', 'Josef', 'Budan');
INSERT INTO Hudebnik
VALUES ('840312/6584', 'Jaromir', 'Hojný');
INSERT INTO Hudebnik
VALUES ('910318/6584', 'Robert', 'Tarem');
INSERT INTO Hudebnik
VALUES ('720516/6584', 'Pavel', 'Župan');
INSERT INTO Hudebnik
VALUES ('740520/6584', 'Albert', 'Povlek');

INSERT INTO Typ
VALUES ('housle');
INSERT INTO Typ
VALUES ('violoncello');
INSERT INTO Typ
VALUES ('trombon');
INSERT INTO Typ
VALUES ('trubka');
INSERT INTO Typ
VALUES ('tympan');

INSERT INTO Autor
VALUES ( 1,'Johan Sebastian Bach', 1685, 1750, 'baroko');
INSERT INTO Autor
VALUES ( 2,'Amadeus Mozart', 1756, 1791, 'klasicismus');
INSERT INTO Autor
VALUES ( 3,'Veronik von Fitz', 1623, 1674, 'baroko');
INSERT INTO Autor
VALUES ( 4,'Giuseppe Verdi', 1813, 1901, 'francouzska_opera');
INSERT INTO Autor
VALUES ( 5,'Antonio Vivaldi', 1678, 1741, 'baroko');

INSERT INTO Koncert
VALUES (1, STR_TO_DATE('16-05-2012 19:00:00', 'dd-mm-yyyy hh24:mi:ss'), 'Pardubice', 'Dlouhá 54');
INSERT INTO Koncert
VALUES (2, STR_TO_DATE('06-12-2011 20:00:00', 'dd-mm-yyyy hh24:mi:ss'), 'Olomouc', 'Jana Nerudy 26');
INSERT INTO Koncert
VALUES (3, STR_TO_DATE('13-07-2010 17:30:00', 'dd-mm-yyyy hh24:mi:ss'), 'Čáslav', 'Lipová 68');
INSERT INTO Koncert
VALUES (4, STR_TO_DATE('27-11-2012 19:30:00', 'dd-mm-yyyy hh24:mi:ss'), 'Ostrava', 'Palackého 47');
INSERT INTO Koncert
VALUES (5, STR_TO_DATE('09-10-2012 20:30:00', 'dd-mm-yyyy hh24:mi:ss'), 'České Budějovice', 'Mistrova 23');

INSERT INTO Skladba
VALUES (1, 'Malá noční hudba', 1000, 2);
INSERT INTO Skladba
VALUES (2, 'Fuga h-moll', 2080, 1);
INSERT INTO Skladba
VALUES (3, 'Čtyři roční období', 1700, 5);
INSERT INTO Skladba
VALUES (4, 'Shining Moon', 1500, 3);
INSERT INTO Skladba
VALUES (5, 'Ave Maria', 1200, 4);

INSERT INTO Hraje_v
VALUES ('housle', 1);
INSERT INTO Hraje_v
VALUES ('housle', 5);
INSERT INTO Hraje_v
VALUES ('violoncello', 3);
INSERT INTO Hraje_v
VALUES ('trombon', 4);
INSERT INTO Hraje_v
VALUES ('tympan', 2);

INSERT INTO Vystupuje_na
VALUES ('910318/6584', 1);
INSERT INTO Vystupuje_na
VALUES ('720516/6584', 3);
INSERT INTO Vystupuje_na
VALUES ('840312/6584', 4);
INSERT INTO Vystupuje_na
VALUES ('740520/6584', 5);
INSERT INTO Vystupuje_na
VALUES ('860504/6584', 2);

INSERT INTO Slozen_z
VALUES (2, 1);
INSERT INTO Slozen_z
VALUES (5, 4);
INSERT INTO Slozen_z
VALUES (1, 3);
INSERT INTO Slozen_z
VALUES (4, 5);
INSERT INTO Slozen_z
VALUES (3, 2);

INSERT INTO Ma_nastudovano
VALUES ('720516/6584', 2);
INSERT INTO Ma_nastudovano
VALUES ('910318/6584', 5);
INSERT INTO Ma_nastudovano
VALUES ('860504/6584', 4);
INSERT INTO Ma_nastudovano
VALUES ('840312/6584', 1);
INSERT INTO Ma_nastudovano
VALUES ('740520/6584', 3);

INSERT INTO Nastroj
VALUES(STR_TO_DATE('15-02-2005 00:00:00', 'dd-mm-yyyy hh24:mi:ss'), 'King', STR_TO_DATE('12-01-2013 13:43:21', 'dd-mm-yyyy hh24:mi:ss'), STR_TO_DATE('12-01-2013 18:27:42', 'dd-mm-yyyy hh24:mi:ss'), 'klapky', 59732, 'trubka');
INSERT INTO Nastroj
VALUES(STR_TO_DATE('14-01-1856 13:43:21', 'dd-mm-yyyy hh24:mi:ss'), 'Stradivari', STR_TO_DATE('14-01-2013 18:27:42', 'dd-mm-yyyy hh24:mi:ss'), STR_TO_DATE('12-03-2013 12:43:21', 'dd-mm-yyyy hh24:mi:ss'), 'kobylka', 15348, 'housle');
INSERT INTO Nastroj
VALUES(STR_TO_DATE('12-03-1936 19:27:42', 'dd-mm-yyyy hh24:mi:ss'), 'Petlach', STR_TO_DATE('09-01-2013 11:37:42', 'dd-mm-yyyy hh24:mi:ss'), STR_TO_DATE('09-01-2013 18:37:42', 'dd-mm-yyyy hh24:mi:ss'), 'ladici kolik', 59642, 'violoncello');
INSERT INTO Nastroj
VALUES(STR_TO_DATE('07-12-1986 15:23:01', 'dd-mm-yyyy hh24:mi:ss'), 'Lidl Brno', STR_TO_DATE('07-12-2013 18:37:42', 'dd-mm-yyyy hh24:mi:ss'), STR_TO_DATE('07-12-2013 15:23:01', 'dd-mm-yyyy hh24:mi:ss'), 'kvarta', 32471, 'trombon');
INSERT INTO Nastroj
VALUES(STR_TO_DATE('07-12-2002 18:37:42', 'dd-mm-yyyy hh24:mi:ss'), 'Jan Bacher', STR_TO_DATE('14-01-2014 10:35:21', 'dd-mm-yyyy hh24:mi:ss'),STR_TO_DATE('14-01-2014 14:26:42', 'dd-mm-yyyy hh24:mi:ss'), 'blana', 12745, 'tympan');

INSERT INTO Hraje_na
VALUES ( 59732, '860504/6584');
INSERT INTO Hraje_na
VALUES ( 32471, '720516/6584');
INSERT INTO Hraje_na
VALUES ( 12745, '740520/6584');
INSERT INTO Hraje_na
VALUES ( 15348, '910318/6584');
INSERT INTO Hraje_na
VALUES ( 59642, '840312/6584');
