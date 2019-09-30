-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 19-Jun-2018 às 01:32
-- Versão do servidor: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trabalho-progweb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `amigos`
--

CREATE TABLE `amigos` (
  `id` int(11) NOT NULL,
  `dataSolicitacao` date NOT NULL,
  `idSolicitante` int(11) NOT NULL,
  `dataConfirmacao` date DEFAULT NULL,
  `idSolicitado` int(11) NOT NULL,
  `situacao` varchar(1) COLLATE utf8_unicode_ci DEFAULT 'P'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `amigos`
--

INSERT INTO `amigos` (`id`, `dataSolicitacao`, `idSolicitante`, `dataConfirmacao`, `idSolicitado`, `situacao`) VALUES
(30, '2018-06-18', 1, '2018-06-18', 2, 'A'),
(33, '2018-06-18', 6, '2018-06-18', 1, 'A'),
(34, '2018-06-18', 6, NULL, 2, 'P'),
(37, '2018-06-18', 1, '2018-06-18', 7, 'A'),
(38, '2018-06-18', 7, '2018-06-18', 4, 'A'),
(40, '2018-06-18', 8, '2018-06-18', 1, 'A');

-- --------------------------------------------------------

--
-- Estrutura da tabela `publicacao`
--

CREATE TABLE `publicacao` (
  `id` int(11) NOT NULL,
  `conteudo` text COLLATE utf8_unicode_ci NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `dataHora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `publicacao`
--

INSERT INTO `publicacao` (`id`, `conteudo`, `idUsuario`, `dataHora`) VALUES
(1, 'Hoje é dia dos namorados', 1, '2018-06-12 15:10:56'),
(7, 'Comentário Calors', 2, '2018-06-12 20:25:15'),
(11, 'teste', 2, '2018-06-12 23:41:10'),
(13, 'tUDO BOM PAULIN', 3, '2018-06-12 23:58:19'),
(23, 'Testando', 1, '2018-06-13 01:05:42'),
(24, 'paulin', 3, '2018-06-13 01:06:21'),
(25, 'teste duplicado, escrevi uma vez', 1, '2018-06-13 01:07:07'),
(28, 'Escrevi outra', 1, '2018-06-13 01:11:15'),
(29, 'oi', 1, '2018-06-17 22:36:17'),
(30, 'Ola Mundooo!!!!!', 4, '2018-06-17 23:05:42'),
(31, 'oi', 1, '2018-06-18 01:07:11'),
(32, 'oi', 1, '2018-06-18 01:12:02'),
(33, 'Testando se vai', 4, '2018-06-18 01:27:23'),
(35, 'OLA', 1, '2018-06-18 02:54:17'),
(36, 'Faala galera do Meu canal hoje é dia de copa!!!', 4, '2018-06-18 11:43:20'),
(37, 'Fala galerinha', 1, '2018-06-18 12:53:32'),
(39, 'Oláaa', 6, '2018-06-18 16:10:56'),
(41, 'Teste hora', 7, '2018-06-18 17:27:47'),
(43, 'Olá Mundo', 8, '2018-06-18 18:36:06'),
(44, 'Teste horário', 8, '2018-06-18 18:36:51'),
(45, 'Teste', 8, '2018-06-18 18:37:03'),
(46, 'Fala brunooo manda nudes', 1, '2018-06-18 18:41:13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sobrenome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataNascimento` date NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apelido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dataIngresso` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `sobrenome`, `dataNascimento`, `email`, `apelido`, `senha`, `dataIngresso`) VALUES
(1, 'Elder', 'Costa', '1993-03-23', 'eldercosta@outlook.com', 'eldinhoec', '$2y$10$rHIabkAqZ/5flAxwP46dN.TtG6NJcfSaq64vpp9qyYg7/t5hSeaOS', '2018-06-12'),
(2, 'Carlos', 'Martins', '1993-03-23', 'carlos@outlook.com', 'carlosptf', '$2y$10$4hGHS4R2g/VQjTW6akwRJ.EeoYwrzQvf9uxVIvisKiPswUXmYU38m', '2018-06-12'),
(3, 'Paulo', 'Pitta', '1993-03-23', 'paulopitta97@gmail.com', 'paulinpitta', '$2y$10$gzXg.cML6Dj5s.jDk0nuHO28oHFkuo3OOw5KzBYMJ1d2K8RKV9kQq', '2018-06-13'),
(4, 'Flavio', 'Materazi', '1993-03-23', 'flavinm@gmail.com', 'falvinm', '$2y$10$6DmRzcM4b/jUXM.NU6VQMuw/i/v6NPgGsWO4P9YD/aYzXTRoPJMjq', '2018-06-18'),
(5, 'Flavio', 'Augusto', '2018-06-18', 'flavin@gmail.com', 'flavioAugusto', '*CBB29D7E213B4ED27538E81748E6558476AAF155', '2018-06-18'),
(6, 'Marina', 'Costa', '1993-03-23', 'marina@gmail.com', 'moricosta', '$2y$10$W9ITpp6Ig83f5UUz1nH3qu5lc3NI1u3CbLTX1i8SrX4KBjze4KvDO', '2018-06-18'),
(7, 'Joao', 'Pedro', '1993-03-23', 'jpedrolorenzo@gmail.com', 'jpedro', '$2y$10$68HnM7tTSOFBV2vNd.yRmeg/i.9NJUKCblOsqeN7LRY5O1x2ybbrG', '2018-06-18'),
(8, 'Bruno', '', '1993-03-23', 'bruno@gmail.com', 'brunoprof', '$2y$10$3F7M1N3BCUoRpDMGO68Ooegjxn8ukVUUDg2iRN0aZk5iTFiUG4fLa', '2018-06-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amigos`
--
ALTER TABLE `amigos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_amigos__usuario_id` (`idSolicitante`),
  ADD KEY `fk_amigos__publicacao_id` (`idSolicitado`);

--
-- Indexes for table `publicacao`
--
ALTER TABLE `publicacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_publicacao_usuario_id` (`idUsuario`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `apelido` (`apelido`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amigos`
--
ALTER TABLE `amigos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `publicacao`
--
ALTER TABLE `publicacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `amigos`
--
ALTER TABLE `amigos`
  ADD CONSTRAINT `fk_amigos__publicacao_id` FOREIGN KEY (`idSolicitado`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_amigos__usuario_id` FOREIGN KEY (`idSolicitante`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `publicacao`
--
ALTER TABLE `publicacao`
  ADD CONSTRAINT `fk_publicacao_usuario_id` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
