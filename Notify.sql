drop database if exists manajementugas;
Create Database manajementugas;

use manajementugas;

CREATE TABLE Pengguna (	
  ID_Pengguna Varchar(20) PRIMARY KEY not null ,
  Username VARCHAR(255) not null,
  Email VARCHAR(255) not null,
  Password varchar(255) not null

);


CREATE TABLE Kategori (
  ID_Kategori Varchar(20) PRIMARY KEY ,
  Nama_Kategori VARCHAR(255) not null
);

CREATE TABLE Label (
  ID_Label Varchar(20) PRIMARY KEY Not null ,
  Nama_Label VARCHAR(255)
);
insert into label (ID_Label,Nama_Label)
values ('1','Green'),
('2','Yellow'),
('3','Red');
CREATE TABLE `gambar` (
  `id` int(11) NOT NULL primary key,
  `namaGambar` varchar(100) DEFAULT NULL,
  `ID_Pengguna` varchar(20) DEFAULT NULL,
  foreign key(ID_Pengguna) references pengguna(ID_Pengguna)
  );
CREATE TABLE Tugas (
  ID_Tugas Varchar(20) PRIMARY KEY Not null ,
  Judul VARCHAR(255) not null,
  Deskripsi TEXT,
  Deadline DATETIME not null,
  Status int,
  ID_Pengguna  Varchar(20),
  ID_Kategori Varchar(20),
  ID_label Varchar(20),
  foreign key (ID_Label) References Label(ID_Label)
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ID_Pengguna) REFERENCES Pengguna(ID_Pengguna)
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ID_Kategori) REFERENCES Kategori(ID_Kategori)
  ON DELETE CASCADE ON UPDATE CASCADE
);
	DELIMITER $$
CREATE TRIGGER TugasSelesai
AFTER UPDATE ON Tugas
FOR EACH ROW
BEGIN
DECLARE taskDeadline DATETIME;
    DECLARE taskTitle VARCHAR(255);
  IF NEW.Status = 1 AND OLD.Status = 0 THEN
    
    
    SELECT Deadline, Judul INTO taskDeadline, taskTitle
    FROM Tugas
    WHERE ID_Tugas = OLD.ID_Tugas;
    
    IF NEW.Deadline < NOW() THEN
      INSERT INTO History (ID_Tugas, Tanggal, Catatan, ID_Pengguna, Judul)
      VALUES (OLD.ID_Tugas, NOW(), 'Selesai Terlambat', OLD.ID_Pengguna, taskTitle);
    ELSE
      INSERT INTO History (ID_Tugas, Tanggal, Catatan, ID_Pengguna, Judul)
      VALUES (OLD.ID_Tugas, NOW(), 'Tugas selesai', OLD.ID_Pengguna, taskTitle);
    END IF;

    DELETE FROM Penugasan WHERE ID_Tugas = OLD.ID_Tugas;
  END IF;
END$$
DELIMITER ;






CREATE TABLE Penugasan (
  ID_Penugasan Varchar(20) PRIMARY KEY,
  ID_Tugas Varchar(20),
  ID_Tujuan  Varchar(20),
  ID_Pembuat Varchar(20),
  FOREIGN KEY (ID_Tugas) REFERENCES Tugas(ID_Tugas)
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ID_Tujuan) REFERENCES Pengguna(ID_Pengguna)
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ID_Pembuat) REFERENCES Pengguna(ID_Pengguna)
  ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE History (
  ID_History Varchar(20) PRIMARY KEY ,
  ID_Tugas Varchar(20),
  Tanggal DATETIME,
  Judul VARCHAR(255) ,
  Catatan TEXT,	
  ID_Pengguna Varchar(20),
  foreign key(ID_Pengguna) references Pengguna(ID_Pengguna),
  FOREIGN KEY (ID_Tugas) REFERENCES Tugas(ID_Tugas)
);


create table decoy1 (
IDpengguna  int auto_increment primary key);
DELIMITER $$
CREATE TRIGGER decoy1
BEFORE INSERT ON pengguna
FOR EACH ROW
BEGIN
  INSERT INTO decoy1 VALUES (NULL);
  SET NEW.ID_Pengguna = CONCAT('P', LPAD(LAST_INSERT_ID(), 3, '0'));
END$$
DELIMITER ;

create table decoy2 (
IDTugas  int auto_increment primary key);
DELIMITER $$
CREATE TRIGGER decoy2
BEFORE INSERT ON tugas
FOR EACH ROW
BEGIN
  INSERT INTO decoy2 VALUES (NULL);
  SET NEW.ID_Tugas = CONCAT('T', LPAD(LAST_INSERT_ID(), 3, '0'));
END$$
DELIMITER ;

create table decoy3 (
IDPenugasan  int auto_increment primary key);
DELIMITER $$
CREATE TRIGGER decoy3
BEFORE INSERT ON Penugasan
FOR EACH ROW
BEGIN
  INSERT INTO decoy3 VALUES (NULL);
  SET NEW.ID_Penugasan = CONCAT('JT', LPAD(LAST_INSERT_ID(), 3, '0'));
END$$
DELIMITER ;

create table decoy4 (
IDKategori  int auto_increment primary key);
DELIMITER $$
CREATE TRIGGER decoy4
BEFORE INSERT ON kategori
FOR EACH ROW
BEGIN
  INSERT INTO decoy4 VALUES (NULL);
  SET NEW.ID_Kategori = CONCAT('C', LPAD(LAST_INSERT_ID(), 3, '0'));
END$$
DELIMITER ;

create table decoy5 (
IDHistory  int auto_increment primary key);
DELIMITER $$
CREATE TRIGGER decoy5
BEFORE INSERT ON history
FOR EACH ROW
BEGIN
  INSERT INTO decoy5 VALUES (NULL);
  SET NEW.ID_History = CONCAT('H', LPAD(LAST_INSERT_ID(), 3, '0'));
END$$
DELIMITER ;


INSERT INTO Pengguna (Username, Email, Password)
VALUES
  ('Ahmad', 'ahmad@gmail.com', 'password1'),
  ('Budi', 'budi@gmail.com', 'password2'),
  ('Citra', 'citra@gmail.com', 'password3'),
  ('Dewi', 'dewi@gmail.com', 'password4'),
  ('Eka', 'eka@gmail.com', 'password5');
INSERT INTO `gambar` (`id`, `namaGambar`, `ID_Pengguna`) VALUES
(1, 'profile.jpg', 'P001'),
(2, 'profile1.jpg', 'P002'),
(3, 'profile2.jpg', 'P003'),
(4, 'profile3.jpg', 'P004'),
(5, 'profile5.jpg', 'P005');
INSERT INTO Kategori (Nama_Kategori)
VALUES
  ('Rumah'),
  ('Kantor'),
  ('Kuliah');
  
  INSERT INTO Tugas (Judul, Deskripsi, Deadline,Status,ID_Pengguna, ID_Kategori, ID_Label)
VALUES
  ('Tugas Mata Kuliah 1', 'Deskripsi tugas 1', '2023-06-01 12:00:00','0', 'P001', 'C001', '1'),
  ('Tugas Mata Kuliah 2', 'Deskripsi tugas 2', '2023-06-05 15:00:00','0',  'P002', 'C003', '2'),
  ('Tugas Mata Kuliah 3', 'Deskripsi tugas 3', '2023-06-10 09:30:00','0',  'P001', 'C002', '3'),
  ('Tugas Mata Kuliah 4', 'Deskripsi tugas 4', '2023-06-15 14:45:00','0',  'P002', 'C003', '1'),
  ('Tugas Mata Kuliah 5', 'Deskripsi tugas 5', '2023-06-20 10:15:00','0',  'P003', 'C001', '2');
  DELIMITER $$
CREATE TRIGGER SetStatusDefaultValue
BEFORE INSERT ON Tugas
FOR EACH ROW
BEGIN
  SET NEW.Status = 0;
END$$
DELIMITER ;

INSERT INTO Penugasan (ID_Tugas, ID_Tujuan, ID_Pembuat) 
VALUES ('T001', 'P003', 'P001'),
('T003', 'P003', 'P001');
/*PENUGASAN Menampilkan
select distinct Tugas.Judul,Tugas.Deskripsi,Tugas.Deadline from tugas 
join penugasan on Penugasan.ID_Pembuat = Tugas.ID_Pengguna 
where Penugasan.ID_Tujuan = "P003";*/

/*Main Reminder*/
Select tugas.Judul,kategori.Nama_Kategori,tugas.Deadline,label.Nama_Label 
from kategori
join tugas on kategori.ID_Kategori=tugas.ID_Kategori
join label on tugas.ID_Label = Label.ID_Label
where ID_Pengguna = "P001";

/*Reminder Order By Priority*/
Select tugas.Judul,kategori.Nama_Kategori,tugas.Deadline,label.Nama_Label 
from kategori
join tugas on kategori.ID_Kategori=tugas.ID_Kategori
join label on tugas.ID_Label = Label.ID_Label
where ID_Pengguna = "P001" 
order by label.ID_Label desc;


/*Reminder order by Cateogry*/
Select tugas.Judul,kategori.Nama_Kategori,tugas.Deadline,label.Nama_Label 
from kategori
join tugas on kategori.ID_Kategori=tugas.ID_Kategori
join label on tugas.ID_Label = Label.ID_Label
where ID_Pengguna = "P001" 
order by kategori.ID_Kategori asc;

/*Reminder order by Cateogry*/
Select tugas.Judul,kategori.Nama_Kategori,tugas.Deadline,label.Nama_Label 
from kategori
join tugas on kategori.ID_Kategori=tugas.ID_Kategori
join label on tugas.ID_Label = Label.ID_Label
where ID_Pengguna = "P001" 
order by tugas.Deadline ;