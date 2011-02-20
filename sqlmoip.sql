/******************************************************
****   SCRIPT DE GERAÇÃO DAS TABELAS PARA MYSQL    ****
******************************************************/


CREATE TABLE IF NOT EXISTS `moiptransacoes` (
  `TransacaoID` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `Valor` int(9) NOT NULL,
  `StatusPagamento` int(2) NOT NULL,
  `cod_moip` int(20) NOT NULL,
  `forma_pagamento` int(2) DEFAULT NULL,
  `tipo_pagamento` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email_consumidor` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`TransacaoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
