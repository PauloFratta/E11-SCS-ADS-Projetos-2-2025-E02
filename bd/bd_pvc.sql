create database pvc_projeto;
use pvc_projeto;

CREATE TABLE cano_personalizado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo VARCHAR(100),
    comprimento VARCHAR(50),
    diametro VARCHAR(50),
    cor VARCHAR(50),
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

create table produto(
  id_produto int auto_increment primary key,
  localização_unidade VARCHAR(50) not null, 
  nome VARCHAR(200) not null,
  diametro_externo int not null, -- ex: '1/2"', '3/4"', '1"','1½"','2"','3"','4"'
  comprimento int not null,
  espessura double not null,
  quantidade int not null,
  formato enum ('Reta', 'Cotovelo', 'T') not null,
  marca varchar(100)
);

create table usuario(
id_usuario int auto_increment primary key,
cpf int(11),
nome varchar(50) not null,
endereço varchar(60) not null,
email varchar(320) not null,
telefone int(11) not null
);

create table venda(
  id_venda int auto_increment primary key,
  id_usuario int not null,
  id_produto int not null,
  quantidade int not null,
  valor_total decimal(10,2) not null,
  data_venda date,
  foreign key (id_usuario) references usuario(id_usuario),
  foreign key (id_produto) references produto(id_produto)
);
