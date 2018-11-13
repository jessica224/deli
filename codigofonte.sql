-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 179.188.16.165
-- Generation Time: 27-Out-2018 às 09:05
-- Versão do servidor: 5.6.35-81.0-log
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codigofonte`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `adicionais`
--

CREATE TABLE `adicionais` (
  `ID_CPF_EMPRESA` varchar(11) CHARACTER SET latin1 NOT NULL,
  `adc_codigo` int(11) NOT NULL,
  `adc_descricao` varchar(100) CHARACTER SET latin1 NOT NULL,
  `adc_img` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `adicionais`
--

INSERT INTO `adicionais` (`ID_CPF_EMPRESA`, `adc_codigo`, `adc_descricao`, `adc_img`) VALUES
('22124254822', 2, 'Carne', 'sem_imagem'),
('22124254822', 3, 'Queijo', 'sem_imagem');

-- --------------------------------------------------------

--
-- Estrutura da tabela `adicionaisxprodutos`
--

CREATE TABLE `adicionaisxprodutos` (
  `ID_CPF_EMPRESA` varchar(11) DEFAULT '22124254812',
  `id` int(11) NOT NULL,
  `adc_codigo` int(11) NOT NULL,
  `prd_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `adicionaisxprodutos`
--

INSERT INTO `adicionaisxprodutos` (`ID_CPF_EMPRESA`, `id`, `adc_codigo`, `prd_codigo`) VALUES
('22124254812', 35, 1, 7),
('22124254812', 36, 2, 7),
('22124254812', 37, 3, 7),
('22124254812', 38, 5, 7),
('22124254812', 39, 6, 7),
('22124254812', 40, 1, 8),
('22124254812', 41, 2, 8),
('22124254812', 42, 5, 10),
('22124254812', 43, 6, 10),
('22124254822', 44, 2, 7015),
('22124254822', 45, 3, 7015);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairros`
--

CREATE TABLE `bairros` (
  `ID_CPF_EMPRESA` varchar(11) CHARACTER SET latin1 NOT NULL,
  `bai_codigo` int(11) NOT NULL,
  `bai_nome` varchar(100) CHARACTER SET latin1 NOT NULL,
  `bai_taxa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bairros`
--

INSERT INTO `bairros` (`ID_CPF_EMPRESA`, `bai_codigo`, `bai_nome`, `bai_taxa`) VALUES
('22124254822', 709, 'Aterrado', 7.00),
('22124254822', 710, 'Retiro', 2.00),
('22124254822', 711, 'Niteroi', 4.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `ID_CPF_EMPRESA` varchar(11) CHARACTER SET latin1 NOT NULL,
  `cat_codigo` int(11) NOT NULL,
  `cat_descricao` varchar(100) CHARACTER SET latin1 NOT NULL,
  `cat_imagem` varchar(300) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`ID_CPF_EMPRESA`, `cat_codigo`, `cat_descricao`, `cat_imagem`) VALUES
('22124254822', 14, 'Bebidas', '22124254822_26872_categoria_refrigerante.png'),
('22124254822', 15, 'Lanches', '22124254822_69666_categoria_lanche.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cpedido`
--

CREATE TABLE `cpedido` (
  `ID_CPF_EMPRESA` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `ped_id` int(11) NOT NULL,
  `ped_chave` text CHARACTER SET latin1,
  `ped_valor` decimal(10,2) DEFAULT NULL,
  `ped_dataHora` datetime DEFAULT NULL,
  `ped_tipoPgto` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `ped_observ` text CHARACTER SET latin1,
  `ped_forma_de_entrega` text CHARACTER SET latin1,
  `ped_status` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `cli_codigo` int(11) DEFAULT NULL,
  `cli_nome` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `ped_troco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cpedido`
--

INSERT INTO `cpedido` (`ID_CPF_EMPRESA`, `ped_id`, `ped_chave`, `ped_valor`, `ped_dataHora`, `ped_tipoPgto`, `ped_observ`, `ped_forma_de_entrega`, `ped_status`, `cli_codigo`, `cli_nome`, `ped_troco`) VALUES
('27454226850', 1, '39197404', 37.00, '2018-02-18 13:59:43', 'Mastercard', 'Rápido por favor', 'ENTREGAR', 'ENTREGANDO', 603634, 'Junior Stabile', 0.00),
('36966725803', 2, '66655313', 50.00, '2018-02-18 14:12:32', 'AVISTA DINHEIRO', 'ok', 'ENTREGAR', 'ENTREGANDO', 434891, 'Mariana Teixeira Pimenta', 0.00),
('36966725803', 3, '8939166', 40.00, '2018-02-18 14:22:21', 'Visa', '', 'ENTREGAR', 'PEDIDO ENVIADO', 434891, 'Mariana Teixeira Pimenta', 0.00),
('22124254812', 4, '28515467', 36.00, '2018-03-19 23:42:20', 'Elo', 'criando projeto', 'ENTREGAR', 'PEDIDO ENVIADO', 958726, 'pedro paulo', 0.00),
('22124254812', 5, '3289938', 3.00, '2018-04-09 13:15:21', 'AVISTA DINHEIRO', '', 'ENTREGAR', 'ENTREGANDO', 958726, 'pedro paulo', 20.00),
('22124254812', 6, '88123827', 24.00, '2018-04-11 11:35:47', 'Mastercard', '', 'ENTREGAR', 'PEDIDO ENVIADO', 964700, 'deliveryapp', 0.00),
('22124254812', 7, '1325888', 23.00, '2018-04-11 11:37:02', 'Mastercard', '', 'BUSCAR', 'PEDIDO ENVIADO', 964700, 'deliveryapp', 0.00),
('22124254812', 8, '53684026', 12.00, '2018-05-14 21:32:48', 'AVISTA DINHEIRO', 'Pedro henrique', 'ENTREGAR', 'PEDIDO ENVIADO', 748684, 'pauloceami', 30.00),
('22124254812', 9, '89229', 24.00, '2018-05-15 17:14:44', 'Visa', 'quero que entregue meu pedido rapido', 'ENTREGAR', 'PEDIDO ENVIADO', 748684, 'pauloceami', 0.00),
('22124254812', 10, '6113428', 52.00, '2018-05-15 17:32:26', 'Visa', 'quero meu pedido rapido', 'ENTREGAR', 'ENTREGANDO', 748684, 'pauloceami', 0.00),
('12148743635', 11, '82446321', 5.00, '2018-08-08 23:47:46', 'AVISTA DINHEIRO', 'rapido', 'ENTREGAR', 'PEDIDO RECUSADO', 227738, 'andre wilian', 50.00),
('12148743635', 12, '6066145', 73.50, '2018-08-09 00:02:07', 'Visa', 'vgggh', 'ENTREGAR', 'ENTREGANDO', 227738, 'andre wilian', 0.00),
('12148743635', 13, '3055629', 52.50, '2018-08-09 00:03:45', 'Elo', 'jjj', 'ENTREGAR', 'ENTREGANDO', 227738, 'andre wilian', 0.00),
('22124254812', 14, '2262773', 21.00, '2018-08-25 14:25:51', 'AVISTA DINHEIRO', 'dia de vendas', 'ENTREGAR', 'PEDIDO ENVIADO', 98912, 'pedro dos santos', 50.00),
('12895437718', 15, '71076564', 10.50, '2018-10-24 03:55:52', 'Mastercard', 'sem cebola', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 16, '2390102', 16.50, '2018-10-24 04:25:15', 'AVISTA DINHEIRO', '', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 50.00),
('12895437718', 17, '8711831', 21.50, '2018-10-24 04:36:00', 'AVISTA DINHEIRO', '', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 50.00),
('12895437718', 18, '1059679', 38.00, '2018-10-24 05:54:52', 'Mastercard', 'sem cebola', 'BUSCAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 19, '130801', 21.00, '2018-10-24 14:50:40', 'Mastercard', 'sem cebola', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 20, '5543677', 6.50, '2018-10-24 20:38:24', 'AVISTA DINHEIRO', '', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 10.00),
('12895437718', 21, '2436122', 36.00, '2018-10-24 20:47:11', 'Mastercard', 'sem cebola', 'ENTREGAR', 'ENTREGANDO', 299570, 'Bruno Rober', 0.00),
('12895437718', 22, '7801890', 17.00, '2018-10-25 19:12:36', 'AVISTA DINHEIRO', '', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 20.00),
('12895437718', 23, '9940670', 15.00, '2018-10-25 20:05:05', 'Mastercard', '', 'BUSCAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 24, '4838226', 21.00, '2018-10-25 21:23:17', 'AVISTA DINHEIRO', 'sem cebola', 'ENTREGAR', 'ENTREGANDO', 299570, 'Bruno Rober', 30.00),
('12895437718', 25, '9010907', 6.00, '2018-10-25 21:28:10', 'AVISTA DINHEIRO', '', 'BUSCAR', 'PEDIDO ENVIADO', 299570, 'Bruno Rober', 10.00),
('12895437718', 26, '584605', 20.50, '2018-10-27 04:17:00', 'AVISTA DINHEIRO', 'sem cebola', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 50.00),
('12895437718', 27, '7840428', 10.50, '2018-10-27 04:41:07', 'Mastercard', '', 'BUSCAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 28, '4877202', 10.50, '2018-10-27 04:46:18', 'Mastercard', '', 'BUSCAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 29, '8240978', 10.50, '2018-10-27 04:48:14', 'Mastercard', '', 'ENTREGAR', 'PEDIDO RECUSADO', 299570, 'Bruno Rober', 0.00),
('12895437718', 30, '4871783', 10.50, '2018-10-27 04:51:06', 'Mastercard', '', 'ENTREGAR', 'ENTREGANDO', 299570, 'Bruno Rober', 0.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `dpedido`
--

CREATE TABLE `dpedido` (
  `id` int(11) NOT NULL,
  `pedchave` varchar(100) DEFAULT NULL,
  `ID_CPF_EMPRESA` varchar(11) DEFAULT NULL,
  `prdcodigo` int(11) DEFAULT NULL,
  `prddescricao` varchar(50) DEFAULT NULL,
  `quantidade` decimal(10,2) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `adcpertenceaoproduto` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `dpedido`
--

INSERT INTO `dpedido` (`id`, `pedchave`, `ID_CPF_EMPRESA`, `prdcodigo`, `prddescricao`, `quantidade`, `preco`, `total`, `tipo`, `adcpertenceaoproduto`) VALUES
(459, '39197404', '27454226850', 7002, 'TIGELA 600ml', 1.00, 15.00, 15.00, 'PRODUTO', 0),
(460, '39197404', '27454226850', 7000, 'TIGELA 200ml', 1.00, 5.00, 5.00, 'PRODUTO', 0),
(461, '39197404', '27454226850', 7003, 'TIGELA 750ml', 1.00, 17.00, 17.00, 'PRODUTO', 0),
(462, '66655313', '36966725803', 7003, 'TIGELA 750ml', 1.00, 17.00, 17.00, 'PRODUTO', 0),
(463, '66655313', '36966725803', 7001, 'TIGELA 300ml', 2.00, 9.00, 18.00, 'PRODUTO', 0),
(464, '66655313', '36966725803', 7002, 'TIGELA 600ml', 1.00, 15.00, 15.00, 'PRODUTO', 0),
(465, '8939166', '36966725803', 7004, 'BARCA (P)', 2.00, 20.00, 40.00, 'PRODUTO', 0),
(466, '28515467', '22124254812', 7007, 'Taça Tablito', 2.00, 18.00, 36.00, 'PRODUTO', 0),
(467, '3289938', '22124254812', 7012, 'AGUA S/GAS', 2.00, 1.50, 3.00, 'PRODUTO', 0),
(468, '88123827', '22124254812', 7013, 'X - Egg Frango', 2.00, 12.00, 24.00, 'PRODUTO', 0),
(469, '1325888', '22124254812', 7009, 'ESPECIAL DA CASA', 1.00, 23.00, 23.00, 'PRODUTO', 0),
(470, '53684026', '22124254812', 7008, 'X TUDO', 1.00, 12.00, 12.00, 'PRODUTO', 0),
(471, '89229', '22124254812', 7008, 'X TUDO', 2.00, 12.00, 24.00, 'PRODUTO', 0),
(472, '6113428', '22124254812', 7007, 'Taça Tablito', 2.00, 18.00, 36.00, 'PRODUTO', 0),
(473, '6113428', '22124254812', 7010, 'Guaraná 1 Litro', 1.00, 4.00, 4.00, 'PRODUTO', 0),
(474, '6113428', '22124254812', 7008, 'X TUDO', 1.00, 12.00, 12.00, 'PRODUTO', 0),
(475, '82446321', '12148743635', 7000, 'TIGELA 200ml', 1.00, 5.00, 5.00, 'PRODUTO', 0),
(476, '6066145', '12148743635', 7015, 'Americano', 7.00, 10.50, 73.50, 'PRODUTO', 0),
(477, '3055629', '12148743635', 7015, 'Americano', 5.00, 10.50, 52.50, 'PRODUTO', 0),
(478, '2262773', '22124254812', 7015, 'Americano', 2.00, 10.50, 21.00, 'PRODUTO', 0),
(479, '71076564', '12895437718', 7015, 'Americano', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(480, '2390102', '12895437718', 7017, 'Coca-Cola', 1.00, 6.00, 6.00, 'PRODUTO', 0),
(481, '2390102', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(482, '8711831', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(483, '8711831', '12895437718', 7016, 'X-Bacon', 1.00, 15.00, 15.00, 'PRODUTO', 0),
(484, '1059679', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(485, '1059679', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(486, '1059679', '12895437718', 7016, 'X-Bacon', 1.00, 15.00, 15.00, 'PRODUTO', 0),
(487, '1059679', '12895437718', 7017, 'Coca-Cola', 1.00, 6.00, 6.00, 'PRODUTO', 0),
(488, '130801', '12895437718', 22, 'cheddar', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(489, '130801', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(490, '130801', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(491, '130801', '12895437718', 21, 'Carne Picanha', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(492, '5543677', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(493, '2436122', '12895437718', 21, 'Carne Picanha', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(494, '2436122', '12895437718', 22, 'Cheddar', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(495, '2436122', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(496, '2436122', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(497, '2436122', '12895437718', 7016, 'X-Bacon', 1.00, 15.00, 15.00, 'PRODUTO', 0),
(498, '7801890', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(499, '7801890', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(500, '9940670', '12895437718', 7016, 'X-Bacon', 1.00, 15.00, 15.00, 'PRODUTO', 0),
(501, '4838226', '12895437718', 7018, 'Fanta Uva', 1.00, 6.50, 6.50, 'PRODUTO', 0),
(502, '4838226', '12895437718', 21, 'Carne Picanha', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(503, '4838226', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(504, '4838226', '12895437718', 22, 'Cheddar', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(505, '9010907', '12895437718', 7017, 'Coca-Cola', 1.00, 6.00, 6.00, 'PRODUTO', 0),
(506, '584605', '12895437718', 22, 'Cheddar', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(507, '584605', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(508, '584605', '12895437718', 21, 'Carne Picanha', 1.00, 2.00, 2.00, 'ADICIONAL', 7015),
(509, '584605', '12895437718', 7017, 'Coca-Cola', 1.00, 6.00, 6.00, 'PRODUTO', 0),
(510, '7840428', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(511, '4877202', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(512, '8240978', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0),
(513, '4871783', '12895437718', 7015, 'X-Burguer', 1.00, 10.50, 10.50, 'PRODUTO', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `ID_CPF_EMPRESA` varchar(11) CHARACTER SET latin1 NOT NULL,
  `prd_codigo` int(11) NOT NULL,
  `cat_codigo` int(11) NOT NULL,
  `prd_descricao` text CHARACTER SET latin1 NOT NULL,
  `prd_preco` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prd_img` text CHARACTER SET latin1,
  `prd_prom` char(1) CHARACTER SET latin1 DEFAULT 'N',
  `prd_det_1` text CHARACTER SET latin1,
  `prd_det_2` text CHARACTER SET latin1,
  `prd_ativo` char(1) CHARACTER SET latin1 DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`ID_CPF_EMPRESA`, `prd_codigo`, `cat_codigo`, `prd_descricao`, `prd_preco`, `prd_img`, `prd_prom`, `prd_det_1`, `prd_det_2`, `prd_ativo`) VALUES
('22124254822', 7015, 15, 'X-Burguer', 10.50, '22124254822_47392_produto_xburguer.png', 'N', 'Pão de Hambúrguer, Bife de Hambúrguer, Maionese, Mussarela.', 'Pão de Hambúrguer, Bife de Hambúrguer, Maionese, Mussarela.', 'S'),
('22124254822', 7016, 15, 'X-Bacon', 15.00, '22124254822_17910_produto_semtítulo1.png', 'N', '', 'Pão de Hamburguer, Bife de Hamburguer, Maionese, Mussarela, Bacon.', 'S'),
('22124254822', 7017, 14, 'Coca-Cola', 6.00, '22124254822_88069_produto_coca.png', 'N', '350ml', '350ml', 'S'),
('22124254822', 7018, 14, 'Fanta Uva', 6.50, '22124254822_55778_produto_fanta.png', 'N', '350ml', '350ml', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `sub_adicionais`
--

CREATE TABLE `sub_adicionais` (
  `ID_CPF_EMPRESA` varchar(11) NOT NULL,
  `sub_codigo` int(11) NOT NULL,
  `sub_descricao` varchar(100) NOT NULL,
  `sub_img` varchar(100) NOT NULL,
  `sub_preco` decimal(10,2) NOT NULL,
  `adc_codigo` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `sub_adicionais`
--

INSERT INTO `sub_adicionais` (`ID_CPF_EMPRESA`, `sub_codigo`, `sub_descricao`, `sub_img`, `sub_preco`, `adc_codigo`) VALUES
('22124254822', 22, 'Cheddar', '22124254822_93933_subadicionais_queijocheddarfatiado100g.png', 2.00, 3),
('22124254822', 21, 'Carne Picanha', '22124254822_15796_subadicionais_carnehamburguer.png', 2.00, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_CPF_EMPRESA` varchar(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `usu_codigo` int(11) NOT NULL,
  `usu_nome` varchar(100) NOT NULL,
  `usu_email` varchar(100) NOT NULL,
  `usu_senha` varchar(100) NOT NULL,
  `usu_contato` varchar(100) NOT NULL,
  `usu_endereco` varchar(100) NOT NULL,
  `usu_numeroEnd` varchar(100) NOT NULL,
  `usu_pontoRef` varchar(100) DEFAULT NULL,
  `bai_codigo` int(11) NOT NULL,
  `usu_key` varchar(100) NOT NULL,
  `usu_nivel` varchar(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`ID_CPF_EMPRESA`, `ID`, `usu_codigo`, `usu_nome`, `usu_email`, `usu_senha`, `usu_contato`, `usu_endereco`, `usu_numeroEnd`, `usu_pontoRef`, `bai_codigo`, `usu_key`, `usu_nivel`) VALUES
('27454226850', 38, 603634, 'Junior Stabile', 'juniorstabile@hotmail.com', 'erwerwe', '(16)9-8219-2066', 'Rua Paraíba', '332', 'Entre a Minas e a Padre Euclides', 705, 'd05099a84a398589', 'CLI'),
('22124254812', 43, 748684, 'pauloceami', 'admin@gmail.com', '3242wer', '(16)9-9999-9999', 'Rua Maria g ', '32', 'festa da soja', 705, '7be4d7c674800ccc', 'CLI'),
('36966725803', 39, 434891, 'Mariana Teixeira Pimenta', 'mariana.t.pimenta@hotmail.com', 'ddg4t43', '(16)9-8127-1904', 'Rua Paraíba', '332', 'Santa Casa', 705, '2256bf5901041531', 'CLI'),
('22124254822', 40, 1753630981, 'Administrador', 'admin@gmail.com', '123456', '(24)9-9997-7990', '0', '0', '564564', 0, '0', 'ADM'),
('12148743635', 46, 227738, 'andre wilian', 'carecagmm@gmail.com', '34345er', '(37)9-9944-6757', 'maria das dorea', '114', 'salem', 705, '98b8c9627bde48c0', 'CLI'),
('12895437718', 47, 299570, 'Bruno Rober', 'brun0_trunks@hotmail.com', '12895437718', '(24)9-9997-7990', 'AV Antônio de Almeida', '1800', 'proximo ao supermercado Royal', 710, 'e589fec10e4407cc', 'CLI'),
('22124254822', 48, 243941564, 'teste', 'a@a.com', '123456', '(24)9-9999-9999', '0', '0', NULL, 0, '0', 'USU');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_login_tentativas`
--

CREATE TABLE `usuarios_login_tentativas` (
  `id` int(11) NOT NULL,
  `ip` varchar(15) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `senha` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `origem` varchar(300) COLLATE latin1_general_ci DEFAULT NULL,
  `bloqueado` char(3) COLLATE latin1_general_ci DEFAULT NULL,
  `data_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adicionais`
--
ALTER TABLE `adicionais`
  ADD PRIMARY KEY (`adc_codigo`);

--
-- Indexes for table `adicionaisxprodutos`
--
ALTER TABLE `adicionaisxprodutos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`bai_codigo`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cat_codigo`);

--
-- Indexes for table `cpedido`
--
ALTER TABLE `cpedido`
  ADD PRIMARY KEY (`ped_id`);

--
-- Indexes for table `dpedido`
--
ALTER TABLE `dpedido`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`prd_codigo`),
  ADD KEY `FK_categorias` (`cat_codigo`);

--
-- Indexes for table `sub_adicionais`
--
ALTER TABLE `sub_adicionais`
  ADD PRIMARY KEY (`sub_codigo`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `usuarios_login_tentativas`
--
ALTER TABLE `usuarios_login_tentativas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adicionais`
--
ALTER TABLE `adicionais`
  MODIFY `adc_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `adicionaisxprodutos`
--
ALTER TABLE `adicionaisxprodutos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `bairros`
--
ALTER TABLE `bairros`
  MODIFY `bai_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=712;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cat_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cpedido`
--
ALTER TABLE `cpedido`
  MODIFY `ped_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `dpedido`
--
ALTER TABLE `dpedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `prd_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7019;

--
-- AUTO_INCREMENT for table `sub_adicionais`
--
ALTER TABLE `sub_adicionais`
  MODIFY `sub_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `usuarios_login_tentativas`
--
ALTER TABLE `usuarios_login_tentativas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
