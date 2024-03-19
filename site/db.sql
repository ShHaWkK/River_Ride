 Création de la table Utilisateurs
CREATE TABLE Utilisateurs (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50),
    Prenom VARCHAR(50),
    Email VARCHAR(100),
    MotDePasse VARCHAR(255),
    Adresse VARCHAR(255),
    NumeroTelephone VARCHAR(20),
    DateCreation DATETIME
);

-- Création de la table Points d'arrêt
CREATE TABLE PointsArret (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    NomPointArret VARCHAR(100),
    Description TEXT,
    Latitude FLOAT,
    Longitude FLOAT
);

-- Création de la table Hébergements
CREATE TABLE Hebergements (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    PointArretID INT,
    NomHebergement VARCHAR(100),
    Description TEXT,
    Capacite INT,
    Disponibilite BOOLEAN,
    PrixNuit DECIMAL(10, 2),
    FOREIGN KEY (PointArretID) REFERENCES PointsArret(ID)
);

-- Création de la table Itinéraires
CREATE TABLE Itineraires (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    UtilisateurID INT,
    NomItineraire VARCHAR(100),
    Duree INT,
    PrixTotal DECIMAL(10, 2),
    DateCreation DATETIME,
    FOREIGN KEY (UtilisateurID) REFERENCES Utilisateurs(ID)
);

-- Création de la table Étapes
CREATE TABLE Etapes (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    ItineraireID INT,
    PointArretID INT,
    OrdreEtape INT,
    DureeEtape INT,
    FOREIGN KEY (ItineraireID) REFERENCES Itineraires(ID),
    FOREIGN KEY (PointArretID) REFERENCES PointsArret(ID)
);

-- Création de la table Réservations
CREATE TABLE Reservations (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    UtilisateurID INT,
    HebergementID INT,
    DateArrivee DATE,
    DateDepart DATE,
    NombrePersonnes INT,
    PrixTotal DECIMAL(10, 2),
    DateReservation DATETIME,
    FOREIGN KEY (UtilisateurID) REFERENCES Utilisateurs(ID),
    FOREIGN KEY (HebergementID) REFERENCES Hebergements(ID)
);

-- Création de la table Offres promotionnelles
CREATE TABLE OffresPromotionnelles (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    NomOffre VARCHAR(100),
    Description TEXT,
    PeriodeValidite VARCHAR(100),
    CodeReduction VARCHAR(50)
);

-- Création de la table Tarifs
CREATE TABLE Tarifs (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    PointArretID INT,
    DateDebut DATE,
    DateFin DATE,
    TarifNuit DECIMAL(10, 2),
    FOREIGN KEY (PointArretID) REFERENCES PointsArret(ID)
);

-- Création de la table Occupation hôtelière
CREATE TABLE OccupationHoteliere (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    HebergementID INT,
    DateOccupation DATE,
    TauxOccupation DECIMAL(5, 2),
    FOREIGN KEY (HebergementID) REFERENCES Hebergements(ID)
);