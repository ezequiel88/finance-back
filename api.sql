-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 13/06/2021 às 00:27
-- Versão do servidor: 8.0.25
-- Versão do PHP: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `curso_dev`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `CatDespesas`
--

CREATE TABLE `CatDespesas` (
  `CodDespesa` int NOT NULL,
  `DescDespesa` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `CatDespesas`
--

INSERT INTO `CatDespesas` (`CodDespesa`, `DescDespesa`) VALUES
(18, 'CASA'),
(19, 'CARRO'),
(20, 'LAZER');

-- --------------------------------------------------------

--
-- Estrutura para tabela `CatReceitas`
--

CREATE TABLE `CatReceitas` (
  `CodReceita` int NOT NULL,
  `DescReceita` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `CatReceitas`
--

INSERT INTO `CatReceitas` (`CodReceita`, `DescReceita`) VALUES
(16, 'FREELANCE'),
(17, 'BOLOS'),
(18, 'E-COMMERCE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `Movimentos`
--

CREATE TABLE `Movimentos` (
  `CodMovimento` int NOT NULL,
  `DescMovimento` varchar(45) DEFAULT NULL,
  `DataHora` datetime DEFAULT CURRENT_TIMESTAMP,
  `Valor` decimal(10,2) DEFAULT NULL,
  `CodReceita` int DEFAULT NULL,
  `CodDespesa` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `Movimentos`
--

INSERT INTO `Movimentos` (`CodMovimento`, `DescMovimento`, `DataHora`, `Valor`, `CodReceita`, `CodDespesa`) VALUES
(65, 'APP APUS', '2021-06-12 21:06:20', '900.00', 16, NULL),
(66, 'VENDA PRODUTOS', '2021-06-12 21:07:12', '5698.31', 18, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `CatDespesas`
--
ALTER TABLE `CatDespesas`
  ADD PRIMARY KEY (`CodDespesa`);

--
-- Índices de tabela `CatReceitas`
--
ALTER TABLE `CatReceitas`
  ADD PRIMARY KEY (`CodReceita`);

--
-- Índices de tabela `Movimentos`
--
ALTER TABLE `Movimentos`
  ADD PRIMARY KEY (`CodMovimento`),
  ADD KEY `fk_Movimentos_CatReceitas_idx` (`CodReceita`),
  ADD KEY `fk_Movimentos_CatDespesas1_idx` (`CodDespesa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `CatDespesas`
--
ALTER TABLE `CatDespesas`
  MODIFY `CodDespesa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `CatReceitas`
--
ALTER TABLE `CatReceitas`
  MODIFY `CodReceita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `Movimentos`
--
ALTER TABLE `Movimentos`
  MODIFY `CodMovimento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `Movimentos`
--
ALTER TABLE `Movimentos`
  ADD CONSTRAINT `fk_Movimentos_CatDespesas` FOREIGN KEY (`CodDespesa`) REFERENCES `CatDespesas` (`CodDespesa`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_Movimentos_CatReceitas` FOREIGN KEY (`CodReceita`) REFERENCES `CatReceitas` (`CodReceita`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
