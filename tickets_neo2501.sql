# Host: localhost  (Version 5.5.5-10.1.13-MariaDB)
# Date: 2019-01-25 16:42:05
# Generator: MySQL-Front 6.0  (Build 2.20)
#

# Structure for table "tickets_padrao"
#

DROP TABLE IF EXISTS `tickets_padrao`;
CREATE TABLE `tickets_padrao` (
  `cod_tickets_padrao` int(11) NOT NULL AUTO_INCREMENT,
  `cod_ticket` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `customerId` int(11) DEFAULT NULL,
  `nome_cliente` varchar(150) DEFAULT NULL,
  `email_cliente` varchar(150) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_update` datetime DEFAULT NULL,
  `prioridade` varchar(20) DEFAULT NULL,
  `data_atualizacao_ticket_prioridade` datetime DEFAULT NULL,
  `causa_prioridade_ticket` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cod_tickets_padrao`)
) ENGINE=InnoDB AUTO_INCREMENT=461 DEFAULT CHARSET=latin1;

#
# Data for table "tickets_padrao"
#



#
# Structure for table "interacoes"
#

DROP TABLE IF EXISTS `interacoes`;
CREATE TABLE `interacoes` (
  `cod_interacao` int(11) NOT NULL AUTO_INCREMENT,
  `assunto` varchar(255) DEFAULT NULL,
  `mensagem` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `remetente` varchar(100) DEFAULT NULL,
  `cod_ticket_padrao_` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_interacao`),
  KEY `cod_ticket_padrao_` (`cod_ticket_padrao_`),
  CONSTRAINT `interacoes_ibfk_1` FOREIGN KEY (`cod_ticket_padrao_`) REFERENCES `tickets_padrao` (`cod_tickets_padrao`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=latin1;

#
# Data for table "interacoes"
#


#
# Structure for table "historico_tickets_prioridade"
#

DROP TABLE IF EXISTS `historico_tickets_prioridade`;
CREATE TABLE `historico_tickets_prioridade` (
  `cod_historico` int(11) NOT NULL AUTO_INCREMENT,
  `data_historico` datetime DEFAULT NULL,
  `cod_ticket` int(11) DEFAULT NULL,
  `prioridade` varchar(20) DEFAULT NULL,
  `assunto_prioridade` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`cod_historico`),
  KEY `cod_ticket` (`cod_ticket`),
  CONSTRAINT `historico_tickets_prioridade_ibfk_1` FOREIGN KEY (`cod_ticket`) REFERENCES `tickets_padrao` (`cod_tickets_padrao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "historico_tickets_prioridade"
#


