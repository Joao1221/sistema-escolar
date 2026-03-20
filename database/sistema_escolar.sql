-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/03/2026 às 17:31
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_escolar`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acompanhamentos_pedagogicos_aluno`
--

CREATE TABLE `acompanhamentos_pedagogicos_aluno` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_coordenador_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nivel_rendimento` varchar(30) NOT NULL DEFAULT 'em_atencao',
  `situacao_risco` varchar(30) NOT NULL DEFAULT 'baixo',
  `percentual_frequencia` decimal(5,2) DEFAULT NULL,
  `indicativos_aprendizagem` text NOT NULL,
  `fatores_risco` text DEFAULT NULL,
  `encaminhamentos` text DEFAULT NULL,
  `precisa_intervencao` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `alimentos`
--

CREATE TABLE `alimentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categoria_alimento_id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `unidade_medida` varchar(20) NOT NULL,
  `estoque_minimo` decimal(10,3) NOT NULL DEFAULT 0.000,
  `controla_validade` tinyint(1) NOT NULL DEFAULT 1,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alimentos`
--

INSERT INTO `alimentos` (`id`, `categoria_alimento_id`, `nome`, `unidade_medida`, `estoque_minimo`, `controla_validade`, `observacoes`, `ativo`, `created_at`, `updated_at`) VALUES
(2, 2, 'Banana', 'DZ', 100.000, 1, 'Frutas devem ser servidas o mais breve possível', 1, '2026-03-18 02:34:00', '2026-03-18 02:34:00'),
(3, 5, 'Carne bovina músculo', 'kg', 500.000, 1, 'Carne congelada parte do músculo para sopas e cozidos', 1, '2026-03-18 02:34:43', '2026-03-18 02:34:43'),
(4, 9, 'Sal', 'kg', 50.000, 1, NULL, 1, '2026-03-18 02:35:01', '2026-03-18 02:35:01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rgm` varchar(255) NOT NULL COMMENT 'Registro Geral de Matrícula',
  `nome_completo` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` enum('M','F','O') NOT NULL DEFAULT 'O',
  `cpf` varchar(14) DEFAULT NULL,
  `nis` varchar(15) DEFAULT NULL,
  `nome_mae` varchar(255) NOT NULL,
  `nome_pai` varchar(255) DEFAULT NULL,
  `responsavel_nome` varchar(255) NOT NULL,
  `responsavel_cpf` varchar(14) NOT NULL,
  `responsavel_telefone` varchar(15) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `uf` char(2) NOT NULL,
  `certidao_nascimento` varchar(255) DEFAULT NULL,
  `rg_numero` varchar(255) DEFAULT NULL,
  `rg_orgao` varchar(255) DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `medicamentos` text DEFAULT NULL,
  `restricoes_alimentares` text DEFAULT NULL,
  `obs_saude` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`id`, `rgm`, `nome_completo`, `data_nascimento`, `sexo`, `cpf`, `nis`, `nome_mae`, `nome_pai`, `responsavel_nome`, `responsavel_cpf`, `responsavel_telefone`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `certidao_nascimento`, `rg_numero`, `rg_orgao`, `alergias`, `medicamentos`, `restricoes_alimentares`, `obs_saude`, `ativo`, `created_at`, `updated_at`) VALUES
(3, '20260001', 'João Rezende', '1974-08-11', 'M', '712.775.605-87', '4588723123', 'mae', 'pai', 'mae', '22222222222', '79 77777-7777', '49040-700', 'Rua Quirino', '1100', 'apto 002', 'Inácio Barbosa', 'Aracaju', 'SE', '25632541254521452125452214', '256.562-85', 'SSP/se', 'nenhuma', 'não', 'não', 'Saúde perfeita', 1, '2026-03-18 01:51:28', '2026-03-18 01:51:28'),
(4, '20260002', 'antonio carlos gonzaga', '2015-06-11', 'M', '66666666666', '24500125420', 'ana júlia', 'josé simeão', 'ana júlia', '44444444444', '(79) 99999-9999', '49700-000', 'Rua Nova', '451', 'Casa', 'Centro', 'Capela', 'SE', '65412398785002450022014005', '632.001-45', 'ssp/se', 'Não possui', 'Não faz uso', 'Não possui', 'Saúde plena', 1, '2026-03-20 00:14:25', '2026-03-20 00:14:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendidos_externos`
--

CREATE TABLE `atendidos_externos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `aluno_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `tipo_vinculo` varchar(30) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_psicossociais`
--

CREATE TABLE `atendimentos_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED NOT NULL,
  `profissional_responsavel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `atendivel_type` varchar(255) NOT NULL,
  `atendivel_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_publico` varchar(20) NOT NULL,
  `tipo_atendimento` varchar(30) NOT NULL,
  `natureza` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL,
  `data_agendada` datetime NOT NULL,
  `data_realizacao` datetime DEFAULT NULL,
  `local_atendimento` varchar(255) DEFAULT NULL,
  `motivo_demanda` text NOT NULL,
  `resumo_sigiloso` text DEFAULT NULL,
  `observacoes_restritas` text DEFAULT NULL,
  `nivel_sigilo` varchar(20) NOT NULL DEFAULT 'muito_restrito',
  `requer_acompanhamento` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `atendimentos_psicossociais`
--

INSERT INTO `atendimentos_psicossociais` (`id`, `escola_id`, `usuario_registro_id`, `profissional_responsavel_id`, `atendivel_type`, `atendivel_id`, `tipo_publico`, `tipo_atendimento`, `natureza`, `status`, `data_agendada`, `data_realizacao`, `local_atendimento`, `motivo_demanda`, `resumo_sigiloso`, `observacoes_restritas`, `nivel_sigilo`, `requer_acompanhamento`, `created_at`, `updated_at`) VALUES
(2, 6, 11, 5, 'App\\Models\\Aluno', 4, 'aluno', 'psicologia', 'agendado', 'realizado', '2026-03-19 10:00:00', '2026-03-20 15:08:53', 'Secretaria de Educação', 'eyJpdiI6IkxwMDNIa0wwR0F0WCtuVnZCRU1laWc9PSIsInZhbHVlIjoiOFJsL3VSS1pCZmFmTlBuTjIva2pZTktWWVA1S0l5U1Q2OGFrOEJnaVZRND0iLCJtYWMiOiI5Yzg2ZWM1MDg0Y2FkNWViMzk5YTI4MDg0MDFkNTlmOTBmOTU1YmNkY2VjOWE3YWEzMzM4ZTg2NzdlYmNlNGVlIiwidGFnIjoiIn0=', 'eyJpdiI6Ind0NzgxVitObFQrMkxHTVJka2tab1E9PSIsInZhbHVlIjoiL3BJMzR3NDk0SThmY29haDNibWIvZS8vbkFZR1dTUWFqUlp4Sm5hclJzaU1odkZaOFRmeEZMMlJqTVpheDd6SiIsIm1hYyI6ImE0YWU0YjU0MDAxNWQ4M2M4ZGU0ZjM3ZTgxNTQwMTM1ZTUxMTNjYjBmZWM1NGI4ODFjM2RkMjkzNzQxZWEyODIiLCJ0YWciOiIifQ==', 'eyJpdiI6IklwNkloR0hZVHZCY0tmRGIwdERYVXc9PSIsInZhbHVlIjoiUXU5dlBsOGZBaWlEN05jRG1CVCs4K1Vkalc5UmVSZkZwQVdKd3NZMU02VzM2elluU3o1alVjNFV2REdId3gwM1JFYUxCS2x1N1F0UHNLK1JmTDhJYnk4L1JBZ2xmOEh2b2gyNkhkN0RkQTB5NTh3UkdEODVBdEVOL1RmVDdMTm8iLCJtYWMiOiIwZjlhODFjMThjN2RiYThlMThiYzlkZTE0YWY4ODlmODIyMDkwNWZiZDFlYmM5ZDY5OTIxZGViZWM3OWViODUzIiwidGFnIjoiIn0=', 'restrito', 1, '2026-03-20 17:45:46', '2026-03-20 18:08:53'),
(3, 6, 11, 5, 'App\\Models\\Aluno', 4, 'aluno', 'psicologia', 'retorno', 'realizado', '2026-03-20 09:00:00', '2026-03-20 09:00:00', 'Secretaria de Educação', 'eyJpdiI6Ik5FRnZvb2JKS0dxYXBlME4yVG4raFE9PSIsInZhbHVlIjoiSE50NVhjTUFYTmVqZ09yczFXM1JyV3NSM1dRaDFpck9zZWsyVFJkTlUxc3FkcTlVZlFJUnBlMW9FNXRpdlFOdzI1L0Y1NnljNjFBaW9LVk5ocXkybUE9PSIsIm1hYyI6IjI5Y2NjMDVkNjgwN2RiZThiZDczN2FmYmE0Y2YwN2VjNzkyODBhZDk5ZDkzZmI1MGEzN2JjM2RmNDBlNTVhMGQiLCJ0YWciOiIifQ==', 'eyJpdiI6Im9CY3UvM0NWaSt4blZCcUlhT095T2c9PSIsInZhbHVlIjoiK0grTkJHWFZNTGU3Qm5mZXduVDRyQTdVOVlQSVZNeHNINlk0dDVSTXhpaW1TWHpabmJ2ZHhWWkMzcC9lOHk5U0VFYytRQnVyb0ExL01ZVEVHK3AvWlE9PSIsIm1hYyI6ImFhMzRkNGJkMGRkNGQzMDkwZTAzYjU5OWMzNzEwNzcwMDlhZDY1MmRkNzgzMjRlOGNmM2I3ZmI2ODg0MjZjNzMiLCJ0YWciOiIifQ==', 'eyJpdiI6Ik1uZG9iajJxNURNbzhSa1BRSlIwK1E9PSIsInZhbHVlIjoiRmVMc3NpZmt2dkh3Q0ZNZzZyODFvbDJ4dm81K3BSUUJUS1ZmNHQwaVJPWT0iLCJtYWMiOiJiNTUzOTAxNGQ0MDA1OTRmYWRlZjU0MzA1NDg3NjgwZTY3ZWFkZDYwNWUzYzU0Y2YzMjYyYzVkMjU0Y2EzMjE4IiwidGFnIjoiIn0=', 'restrito', 1, '2026-03-20 18:07:16', '2026-03-20 18:07:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:163:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:17:\"visualizar alunos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"criar aluno\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"editar aluno\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"detalhar aluno\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:21:\"ativar inativar aluno\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"consultar turmas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"detalhar turma\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:16:\"cadastrar turmas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"editar turmas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"excluir turmas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:21:\"consultar matrículas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:20:\"cadastrar matrícula\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:33:\"visualizar detalhes da matrícula\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:8:\"enturmar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:10:\"transferir\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"rematricular\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:21:\"gerenciar disciplinas\";s:1:\"c\";s:3:\"web\";}i:17;a:3:{s:1:\"a\";i:18;s:1:\"b\";s:18:\"gerenciar matrizes\";s:1:\"c\";s:3:\"web\";}i:18;a:3:{s:1:\"a\";i:19;s:1:\"b\";s:18:\"consultar matrizes\";s:1:\"c\";s:3:\"web\";}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"consultar diarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:6;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:13:\"criar diarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:15:\"registrar aulas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:17:\"lancar frequencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:23:\"gerenciar planejamentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:33:\"registrar observacoes pedagogicas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:33:\"registrar ocorrencias pedagogicas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:33:\"gerenciar pendencias do professor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:3;i:1;i:6;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:34:\"acompanhar diarios pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:26:\"validar planejamento anual\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:28:\"validar planejamento semanal\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:25:\"validar aulas registradas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:32:\"acompanhar frequencia pedagogica\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:32:\"acompanhar rendimento pedagogico\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:26:\"acompanhar alunos em risco\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:29:\"gerenciar pendencias docentes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:12:\"ver horarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:29:\"acompanhar diarios da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:33:\"validar planejamento pela direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:26:\"validar aulas pela direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:27:\"justificar faltas de alunos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:27:\"liberar prazo de lancamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:32:\"registrar faltas de funcionarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:25:\"iniciar fechamento letivo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:26:\"concluir fechamento letivo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:32:\"validar planejamento por periodo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:39:\"consultar notas e conceitos pedagogicos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:37:\"alterar notas e conceitos pedagogicos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:34:\"consultar horarios pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:34:\"cadastrar horarios pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:31:\"editar horarios pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:36:\"reorganizar horarios pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:31:\"consultar aulas pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:29:\"ajustar aulas pedagogicamente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:4;i:1;i:6;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:45:\"validar planejamento por periodo pela direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:38:\"consultar notas e conceitos da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:36:\"alterar notas e conceitos da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:29:\"consultar horarios da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:29:\"cadastrar horarios da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:26:\"editar horarios da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:31:\"reorganizar horarios da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:26:\"consultar aulas da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:24:\"ajustar aulas da direcao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:29:\"consultar alimentacao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:19:\"cadastrar alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:16:\"editar alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:30:\"registrar entrada de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:28:\"registrar saida de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:22:\"lancar cardapio diario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:30:\"consultar estoque de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:36:\"consultar movimentacoes de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:33:\"cadastrar categorias de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:35:\"cadastrar fornecedores de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:30:\"editar categorias de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:32:\"editar fornecedores de alimentos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:7;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:31:\"acessar portal da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:36:\"consultar alimentos da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:37:\"consultar categorias da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:39:\"consultar fornecedores da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:36:\"consultar cardapios da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:34:\"consultar estoque da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:35:\"consultar validade da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:40:\"consultar movimentacoes da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:50:\"consultar comparativo de alimentacao entre escolas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:46:\"consultar relatorios gerenciais da alimentacao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:27:\"acessar modulo psicossocial\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:29:\"consultar agenda psicossocial\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:36:\"registrar atendimentos psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:32:\"consultar historico psicossocial\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:45:\"registrar planos de intervencao psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:39:\"registrar encaminhamentos psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:39:\"registrar casos disciplinares sigilosos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:40:\"emitir relatorios tecnicos psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:37:\"acessar dados sigilosos psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:30:\"consultar documentos escolares\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:30:\"emitir declaracao de matricula\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:31:\"emitir declaracao de frequencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:31:\"emitir comprovante de matricula\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:31:\"emitir ficha cadastral do aluno\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:32:\"emitir ficha individual do aluno\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:28:\"emitir guia de transferencia\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:24:\"emitir historico escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:18:\"emitir ata escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:21:\"emitir oficio escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:43:\"consultar documentos institucionais da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:35:\"emitir oficio institucional da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:35:\"emitir modelo institucional da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:39:\"consultar documentos da direcao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:36:\"emitir documentos da direcao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:32:\"consultar documentos pedagogicos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:4;}}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:29:\"emitir documentos pedagogicos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:4;}}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:33:\"consultar documentos do professor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:3;}}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:30:\"emitir documentos do professor\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:3;}}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:34:\"consultar documentos psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:113;a:4:{s:1:\"a\";i:114;s:1:\"b\";s:31:\"emitir documentos psicossociais\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:114;a:4:{s:1:\"a\";i:115;s:1:\"b\";s:28:\"consultar relatorios da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:115;a:4:{s:1:\"a\";i:116;s:1:\"b\";s:38:\"emitir relatorio institucional da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:116;a:4:{s:1:\"a\";i:117;s:1:\"b\";s:38:\"emitir relatorio de matriculas da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:117;a:4:{s:1:\"a\";i:118;s:1:\"b\";s:42:\"emitir relatorio de situacao de matriculas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:118;a:4:{s:1:\"a\";i:119;s:1:\"b\";s:30:\"emitir relatorio de alunos aee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:119;a:4:{s:1:\"a\";i:120;s:1:\"b\";s:43:\"emitir relatorio quantitativo de matriculas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:120;a:4:{s:1:\"a\";i:121;s:1:\"b\";s:31:\"emitir relatorio mapa de turmas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:121;a:4:{s:1:\"a\";i:122;s:1:\"b\";s:41:\"emitir relatorio de professores por turma\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:122;a:4:{s:1:\"a\";i:123;s:1:\"b\";s:29:\"emitir relatorio de auditoria\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:123;a:4:{s:1:\"a\";i:124;s:1:\"b\";s:30:\"consultar relatorios escolares\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:124;a:4:{s:1:\"a\";i:125;s:1:\"b\";s:43:\"emitir relatorios administrativos escolares\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:125;a:4:{s:1:\"a\";i:126;s:1:\"b\";s:42:\"emitir relatorio de frequencia consolidada\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:126;a:4:{s:1:\"a\";i:127;s:1:\"b\";s:34:\"emitir relatorio historico escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:127;a:4:{s:1:\"a\";i:128;s:1:\"b\";s:33:\"emitir relatorio ficha individual\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;}}i:128;a:4:{s:1:\"a\";i:129;s:1:\"b\";s:39:\"emitir relatorio de alimentacao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:129;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:41:\"consultar notas e conceitos em relatorios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:130;a:4:{s:1:\"a\";i:131;s:1:\"b\";s:32:\"consultar relatorios pedagogicos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:4;}}i:131;a:4:{s:1:\"a\";i:132;s:1:\"b\";s:29:\"emitir relatorios pedagogicos\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:4;}}i:132;a:4:{s:1:\"a\";i:133;s:1:\"b\";s:39:\"consultar relatorios da direcao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:133;a:4:{s:1:\"a\";i:134;s:1:\"b\";s:36:\"emitir relatorios da direcao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:134;a:4:{s:1:\"a\";i:135;s:1:\"b\";s:37:\"consultar relatorios da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:135;a:4:{s:1:\"a\";i:136;s:1:\"b\";s:34:\"emitir relatorios da nutricionista\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:136;a:4:{s:1:\"a\";i:137;s:1:\"b\";s:45:\"consultar relatorios tecnicos do psicossocial\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:137;a:4:{s:1:\"a\";i:138;s:1:\"b\";s:42:\"emitir relatorios tecnicos do psicossocial\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:138;a:4:{s:1:\"a\";i:139;s:1:\"b\";s:27:\"consultar auditoria da rede\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:139;a:4:{s:1:\"a\";i:140;s:1:\"b\";s:27:\"consultar auditoria escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:140;a:4:{s:1:\"a\";i:141;s:1:\"b\";s:30:\"consultar auditoria pedagogica\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:4;}}i:141;a:4:{s:1:\"a\";i:142;s:1:\"b\";s:38:\"consultar auditoria da direcao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:5;}}i:142;a:4:{s:1:\"a\";i:143;s:1:\"b\";s:47:\"consultar auditoria do proprio trabalho docente\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:3;}}i:143;a:4:{s:1:\"a\";i:144;s:1:\"b\";s:42:\"consultar auditoria da alimentacao escolar\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:7;}}i:144;a:4:{s:1:\"a\";i:145;s:1:\"b\";s:41:\"consultar auditoria psicossocial sigilosa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:8;}}i:145;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:39:\"visualizar dados sensiveis de auditoria\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:5;i:1;i:6;i:2;i:8;}}i:146;a:4:{s:1:\"a\";i:147;s:1:\"b\";s:14:\"acessar painel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:147;a:4:{s:1:\"a\";i:148;s:1:\"b\";s:19:\"visualizar usuarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:148;a:4:{s:1:\"a\";i:149;s:1:\"b\";s:13:\"criar usuario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:149;a:4:{s:1:\"a\";i:150;s:1:\"b\";s:14:\"editar usuario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:150;a:4:{s:1:\"a\";i:151;s:1:\"b\";s:23:\"ativar inativar usuario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:151;a:4:{s:1:\"a\";i:152;s:1:\"b\";s:22:\"visualizar instituicao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:152;a:4:{s:1:\"a\";i:153;s:1:\"b\";s:18:\"editar instituicao\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:153;a:4:{s:1:\"a\";i:154;s:1:\"b\";s:24:\"visualizar configuracoes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:154;a:4:{s:1:\"a\";i:155;s:1:\"b\";s:20:\"editar configuracoes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:155;a:4:{s:1:\"a\";i:156;s:1:\"b\";s:18:\"visualizar escolas\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:156;a:4:{s:1:\"a\";i:157;s:1:\"b\";s:12:\"criar escola\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:157;a:4:{s:1:\"a\";i:158;s:1:\"b\";s:13:\"editar escola\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:158;a:4:{s:1:\"a\";i:159;s:1:\"b\";s:22:\"ativar inativar escola\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:159;a:4:{s:1:\"a\";i:160;s:1:\"b\";s:23:\"visualizar funcionarios\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:160;a:4:{s:1:\"a\";i:161;s:1:\"b\";s:17:\"criar funcionario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:161;a:4:{s:1:\"a\";i:162;s:1:\"b\";s:18:\"editar funcionario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}i:162;a:4:{s:1:\"a\";i:163;s:1:\"b\";s:27:\"ativar inativar funcionario\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:6;}}}s:5:\"roles\";a:8:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:19:\"Secretário Escolar\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:23:\"Administrador da Escola\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:23:\"Coordenador Pedagógico\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:15:\"Diretor Escolar\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:21:\"Administrador da Rede\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"Professor\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"Nutricionista\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:25:\"Psicologia/Psicopedagogia\";s:1:\"c\";s:3:\"web\";}}}', 1774110608);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapios_diarios`
--

CREATE TABLE `cardapios_diarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `data_cardapio` date NOT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cardapio_diario_itens`
--

CREATE TABLE `cardapio_diario_itens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cardapio_diario_id` bigint(20) UNSIGNED NOT NULL,
  `alimento_id` bigint(20) UNSIGNED NOT NULL,
  `refeicao` varchar(30) NOT NULL,
  `quantidade_prevista` decimal(10,3) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `casos_disciplinares_sigilosos`
--

CREATE TABLE `casos_disciplinares_sigilosos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_psicossocial_id` bigint(20) UNSIGNED DEFAULT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `aluno_id` bigint(20) UNSIGNED DEFAULT NULL,
  `funcionario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `data_ocorrencia` date NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao_sigilosa` text NOT NULL,
  `medidas_adotadas` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'aberto',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `casos_disciplinares_sigilosos`
--

INSERT INTO `casos_disciplinares_sigilosos` (`id`, `atendimento_psicossocial_id`, `escola_id`, `aluno_id`, `funcionario_id`, `usuario_id`, `data_ocorrencia`, `titulo`, `descricao_sigilosa`, `medidas_adotadas`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 6, 4, NULL, 11, '2026-03-20', 'Caso disciplinar vinculado ao atendimento', 'eyJpdiI6Im4wRi9EQ0JDSFVUSGdVczdOMmIvdGc9PSIsInZhbHVlIjoiWlhLbHE2MjJEeXRDK2N2N0xLY2tqZWszbVJ2L3NteGtnSzFPd2trZmNaM2NLeEgrQ2p5NzM5QnRsWFNXbW80cUlVZmM4VmpJODJLNmg5TUdLWStxa2c9PSIsIm1hYyI6IjMzMTNmYzc5OGVlZjA0NzQxN2I0Yjc0M2JhZWIwNjNkYzJjZmZhMmM3YTNjMzcyYTZlZTY2OWIzZDIyZDdmZTkiLCJ0YWciOiIifQ==', NULL, 'aberto', '2026-03-20 17:48:39', '2026-03-20 17:48:39'),
(2, 2, 6, 4, NULL, 11, '2026-03-20', 'Caso disciplinar vinculado ao atendimento', 'eyJpdiI6Ild2Q2FLNTJnT1RPS1VwRlhWYkZ2Y1E9PSIsInZhbHVlIjoiUmh6VzM5NVlTekFuRDdpSkhta0NnMGl0dXBVb05xWldRWGg0bm90RmpGMnZPMFNQajJsMFpzNmUrR2NTait3UTJNci9nNlhzWHM5djZkSklyeDhqU2c9PSIsIm1hYyI6ImQ2YTZkMzZkZWJhOWUwZmRhMzI0ZTgxYzZkNzY3Y2M2YjU4ZmRkYjg3ZmQwNTE5ZjJlODVkZjcyZTNjMWIzMGQiLCJ0YWciOiIifQ==', NULL, 'aberto', '2026-03-20 18:13:27', '2026-03-20 18:13:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_alimentos`
--

CREATE TABLE `categorias_alimentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias_alimentos`
--

INSERT INTO `categorias_alimentos` (`id`, `nome`, `descricao`, `ativo`, `created_at`, `updated_at`) VALUES
(2, 'Frutas', 'Frutas frescas servidas in natura ou em preparações', 1, '2026-03-18 02:31:07', '2026-03-18 02:31:07'),
(3, 'Verduras e Legumes', 'Hortalicas e legumes para preparo diario', 1, '2026-03-18 02:31:30', '2026-03-18 02:31:30'),
(4, 'Graos e Cereais', 'Arroz, milho, aveia, flocos e derivados', 1, '2026-03-18 02:31:41', '2026-03-18 02:31:41'),
(5, 'Proteinas', 'Carnes, ovos, frango, peixe e outras fontes proteicas', 1, '2026-03-18 02:31:52', '2026-03-18 02:31:52'),
(6, 'Laticinios', 'Leite, queijo, iogurte e derivados', 1, '2026-03-18 02:32:03', '2026-03-18 02:32:03'),
(7, 'Panificacao', 'Pao, biscoitos e massas usadas na alimentacao escolar', 1, '2026-03-18 02:32:14', '2026-03-18 02:32:14'),
(8, 'Bebidas', 'Sucos, leite e outras bebidas servidas', 1, '2026-03-18 02:32:25', '2026-03-18 02:32:25'),
(9, 'Temperos e Complementos', 'Sal, alho, cebola, oleo, acucar e itens de apoio ao preparo', 1, '2026-03-18 02:32:43', '2026-03-18 02:32:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `demandas_psicossociais`
--

CREATE TABLE `demandas_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED NOT NULL,
  `profissional_responsavel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_atendimento` enum('psicologia','psicopedagogia','psicossocial') NOT NULL DEFAULT 'psicologia',
  `origem_demanda` enum('coordenacao','direcao','professor','familia','triagem_interna','demanda_espontanea','outro') NOT NULL,
  `tipo_publico` enum('aluno','professor','funcionario','responsavel') NOT NULL,
  `aluno_id` bigint(20) UNSIGNED DEFAULT NULL,
  `funcionario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `responsavel_nome` varchar(255) DEFAULT NULL,
  `responsavel_telefone` varchar(255) DEFAULT NULL,
  `responsavel_vinculo` varchar(255) DEFAULT NULL,
  `motivo_inicial` text DEFAULT NULL,
  `prioridade` enum('baixa','media','alta','urgente') NOT NULL DEFAULT 'media',
  `status` enum('aberta','em_triagem','em_atendimento','encaminhada','observacao','encerrada') NOT NULL DEFAULT 'aberta',
  `data_solicitacao` date NOT NULL,
  `observacoes` text DEFAULT NULL,
  `encaminhado_para_atendimento` tinyint(1) NOT NULL DEFAULT 0,
  `atendimento_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `devolutivas_psicossociais`
--

CREATE TABLE `devolutivas_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_responsavel_id` bigint(20) UNSIGNED NOT NULL,
  `destinatario` enum('familia','professor','coordenacao','direcao','funcionario','outro') NOT NULL,
  `nome_destinatario` varchar(255) DEFAULT NULL,
  `data_devolutiva` date NOT NULL,
  `resumo_devolutiva` text DEFAULT NULL,
  `orientacoes` text DEFAULT NULL,
  `encaminhamentos_combinados` text DEFAULT NULL,
  `necessita_acompanhamento` tinyint(1) NOT NULL DEFAULT 0,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `diarios_professor`
--

CREATE TABLE `diarios_professor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `turma_id` bigint(20) UNSIGNED NOT NULL,
  `disciplina_id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `ano_letivo` smallint(5) UNSIGNED NOT NULL,
  `periodo_tipo` varchar(30) NOT NULL,
  `periodo_referencia` varchar(30) NOT NULL,
  `situacao` varchar(30) NOT NULL DEFAULT 'em_andamento',
  `observacoes_gerais` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `diarios_professor`
--

INSERT INTO `diarios_professor` (`id`, `escola_id`, `turma_id`, `disciplina_id`, `professor_id`, `ano_letivo`, `periodo_tipo`, `periodo_referencia`, `situacao`, `observacoes_gerais`, `created_at`, `updated_at`) VALUES
(2, 6, 3, 3, 6, 2026, 'bimestre', '1', 'em_andamento', NULL, '2026-03-20 00:22:41', '2026-03-20 00:22:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `carga_horaria_sugerida` int(11) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nome`, `codigo`, `carga_horaria_sugerida`, `ativo`, `created_at`, `updated_at`) VALUES
(2, 'PORTUGUES', 'POR', 120, 1, '2026-03-18 01:27:46', '2026-03-18 01:27:46'),
(3, 'MATEMATICA', 'MAT', 120, 1, '2026-03-18 01:27:58', '2026-03-18 01:27:58'),
(4, 'GEOGRAFIA', 'GEO', 80, 1, '2026-03-18 01:28:10', '2026-03-18 01:28:10'),
(5, 'HISTORIA', 'HIS', 80, 1, '2026-03-18 01:28:21', '2026-03-18 01:28:21'),
(6, 'CIENCIAS', 'CIE', 80, 1, '2026-03-18 01:28:31', '2026-03-18 01:28:31'),
(7, 'ARTES', 'ART', 40, 1, '2026-03-18 01:28:44', '2026-03-18 01:28:44'),
(8, 'INGLES', 'ING', 40, 1, '2026-03-18 01:28:54', '2026-03-18 01:28:54'),
(9, 'EDUCACAO FISICA', 'EDF', 80, 1, '2026-03-18 01:29:11', '2026-03-18 01:29:11'),
(10, 'REDACAO', 'RED', 40, 1, '2026-03-18 01:29:25', '2026-03-18 01:29:25'),
(11, 'RELIGIAO', 'REL', 40, 1, '2026-03-18 01:29:40', '2026-03-18 01:29:40'),
(12, 'SOCIEDADE E CULTURA', 'SCU', 80, 1, '2026-03-18 01:29:55', '2026-03-18 01:29:55');

-- --------------------------------------------------------

--
-- Estrutura para tabela `encaminhamentos_psicossociais`
--

CREATE TABLE `encaminhamentos_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_psicossocial_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `profissional_destino` varchar(255) DEFAULT NULL,
  `instituicao_destino` varchar(255) DEFAULT NULL,
  `motivo` text NOT NULL,
  `orientacoes_sigilosas` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'emitido',
  `data_encaminhamento` date NOT NULL,
  `retorno_previsto_em` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `encaminhamentos_psicossociais`
--

INSERT INTO `encaminhamentos_psicossociais` (`id`, `atendimento_psicossocial_id`, `usuario_id`, `tipo`, `destino`, `profissional_destino`, `instituicao_destino`, `motivo`, `orientacoes_sigilosas`, `status`, `data_encaminhamento`, `retorno_previsto_em`, `created_at`, `updated_at`) VALUES
(1, 3, 11, 'externo', 'Secretaria de Saúde', 'Psiquiatra', 'Secretaria Municipal de Saúde', 'eyJpdiI6Img0Z3dnOEUvWXBubUxPazdZOVYrMkE9PSIsInZhbHVlIjoiVmdCT3c4RmRpb3dOaThNT24yMGRwZ1RLdDBHbHlHK3ptbUtRMjJyUVNPTGk1SmJkb1phTnI1UzNHR0lpdVZlRlFXRGt1RDRDVWErYWFrOHZWNXVoc3c9PSIsIm1hYyI6ImM1OTNjMTczMzRlNTVlMjhkMzQyMDJhNDI3OTIxYjhhZjY0NmYwZjE3MDRmNWU0NzdiZTNkMjFkOWU4MzUxNTAiLCJ0YWciOiIifQ==', 'eyJpdiI6IjJHNVAyRFVhNlFzazNESXYxMnloMWc9PSIsInZhbHVlIjoiZEhNcG1XRk43dVM1WVJ4cjhKMC9HRVZnL05WZWZyZlBUeisvZlF6K281MHpOMXJ4SkRkNUsxVS9iZjhoZVNDeCIsIm1hYyI6IjkxNjQ3ZjIxYjQ5Zjg5ZDYxNWE0Yjk0MzlmNDExMjI2MTQyNzQ0OTk4NDg2MjdhYTIwMmE3Y2E3ZTliOGY2YmYiLCJ0YWciOiIifQ==', 'concluido', '2026-03-20', '2026-03-20', '2026-03-20 18:24:37', '2026-03-20 18:24:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas`
--

CREATE TABLE `escolas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `cep` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `uf` varchar(255) DEFAULT NULL,
  `nome_gestor` varchar(255) DEFAULT NULL,
  `cpf_gestor` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `escolas`
--

INSERT INTO `escolas` (`id`, `nome`, `cnpj`, `email`, `telefone`, `cep`, `endereco`, `bairro`, `cidade`, `uf`, `nome_gestor`, `cpf_gestor`, `ativo`, `created_at`, `updated_at`) VALUES
(5, 'Escola Municipal Zózimo Lima', '00.000.000/0001-00', 'zozimo@gmail.com', '(79) 0000-0000', '49700-000', 'Rua Principal, 100', 'Centro', 'Capela', 'SE', 'Gestora da Zózimo Lima', '44444444444', 1, '2026-03-18 01:23:19', '2026-03-19 22:07:49'),
(6, 'Escola Municipal Major Honorino Leal', '36.564.071/0001-64', 'major@gmail.com', '(79) 55555-5555', '49700-000', 'Rua Coelho e Campos, 1201', 'Centro', 'Capela', 'SE', 'Nome da Gestora da Major', '66666666666', 1, '2026-03-19 22:09:12', '2026-03-19 22:09:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `faltas_funcionarios`
--

CREATE TABLE `faltas_funcionarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `funcionario_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED NOT NULL,
  `data_falta` date NOT NULL,
  `turno` varchar(20) NOT NULL,
  `tipo_falta` varchar(30) NOT NULL,
  `justificada` tinyint(1) NOT NULL DEFAULT 0,
  `motivo` varchar(180) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fechamentos_letivos`
--

CREATE TABLE `fechamentos_letivos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `ano_letivo` smallint(5) UNSIGNED NOT NULL,
  `usuario_direcao_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL,
  `resumo` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `iniciado_em` timestamp NULL DEFAULT NULL,
  `concluido_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores_alimentos`
--

CREATE TABLE `fornecedores_alimentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `contato_nome` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `frequencias_aula`
--

CREATE TABLE `frequencias_aula` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registro_aula_id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED NOT NULL,
  `situacao` varchar(30) NOT NULL DEFAULT 'presente',
  `justificativa` text DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `cpf`, `email`, `telefone`, `cargo`, `ativo`, `created_at`, `updated_at`) VALUES
(2, 'Jose da Silva', '12345678901', 'jose@gmail.com', '79 98139-5097', 'Secretário Escolar', 1, '2026-03-18 01:36:17', '2026-03-18 01:36:17'),
(3, 'Ane Ravenala da Silva', '10230652314', 'nutricionista@sistema.local', '79999248114', 'Nutricionista', 1, '2026-03-18 02:09:10', '2026-03-18 02:09:10'),
(4, 'Ane Ravenala da Silva', '36589658741', 'nutricionista@sistema.local', '79999248114', 'Nutricionista', 0, '2026-03-18 02:09:20', '2026-03-18 02:10:14'),
(5, 'Naíne Ferreira dos Santos', '33333333333', 'naine@gmail.com', '(99) 99999-9999', 'Psicólogo', 1, '2026-03-19 20:49:58', '2026-03-19 20:49:58'),
(6, 'Prefessor Amado Silva', '77777777777', 'professor@gmail.com', '(79) 44444-4444', 'Professor', 1, '2026-03-19 23:15:10', '2026-03-19 23:15:10'),
(7, 'Coordenadora Escolar', '99999999999', 'coordenadora@gmail.com', '(79) 66666-6666', 'Coordenador', 1, '2026-03-20 00:02:17', '2026-03-20 00:02:17'),
(8, 'Assistente Administrativo Escolar', '88888888888', 'assistente@gmail.com', '(79) 22222-2222', 'Secretário Escolar', 1, '2026-03-20 00:09:18', '2026-03-20 00:09:18');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario_escola`
--

CREATE TABLE `funcionario_escola` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `funcionario_id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `funcionario_escola`
--

INSERT INTO `funcionario_escola` (`id`, `funcionario_id`, `escola_id`, `created_at`, `updated_at`) VALUES
(1, 2, 5, NULL, NULL),
(2, 3, 5, NULL, NULL),
(3, 4, 5, NULL, NULL),
(4, 5, 5, NULL, NULL),
(5, 6, 6, NULL, NULL),
(6, 7, 6, NULL, NULL),
(7, 8, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `horario_aulas`
--

CREATE TABLE `horario_aulas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `turma_id` bigint(20) UNSIGNED NOT NULL,
  `disciplina_id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dia_semana` int(11) NOT NULL COMMENT '1=Domingo, 2=Segunda, 3=Terça, 4=Quarta, 5=Quinta, 6=Sexta, 7=Sábado',
  `horario_inicial` time NOT NULL,
  `horario_final` time NOT NULL,
  `ordem_aula` int(11) DEFAULT NULL COMMENT 'Ex: 1 para 1ª aula, 2 para 2ª aula',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `horario_aulas`
--

INSERT INTO `horario_aulas` (`id`, `escola_id`, `turma_id`, `disciplina_id`, `professor_id`, `dia_semana`, `horario_inicial`, `horario_final`, `ordem_aula`, `ativo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, 3, 3, 6, 2, '12:30:00', '14:10:00', 1, 1, '2026-03-20 00:20:39', '2026-03-20 00:20:39', NULL),
(2, 6, 3, 5, 6, 2, '14:10:00', '15:00:00', NULL, 1, '2026-03-20 00:20:39', '2026-03-20 00:20:39', NULL),
(3, 6, 3, 6, 6, 2, '15:15:00', '16:05:00', NULL, 1, '2026-03-20 00:20:39', '2026-03-20 00:20:39', NULL),
(4, 6, 3, 9, 6, 2, '16:05:00', '16:55:00', NULL, 1, '2026-03-20 00:20:39', '2026-03-20 00:20:39', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicoes`
--

CREATE TABLE `instituicoes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome_prefeitura` varchar(255) DEFAULT NULL,
  `cnpj_prefeitura` varchar(255) DEFAULT NULL,
  `nome_prefeito` varchar(255) DEFAULT NULL,
  `nome_secretaria` varchar(255) DEFAULT NULL,
  `sigla_secretaria` varchar(255) DEFAULT NULL,
  `nome_secretario` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `municipio` varchar(255) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `cep` varchar(255) DEFAULT NULL,
  `brasao_path` varchar(255) DEFAULT NULL,
  `logo_prefeitura_path` varchar(255) DEFAULT NULL,
  `logo_secretaria_path` varchar(255) DEFAULT NULL,
  `textos_institucionais` text DEFAULT NULL,
  `assinaturas_cargos` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `instituicoes`
--

INSERT INTO `instituicoes` (`id`, `nome_prefeitura`, `cnpj_prefeitura`, `nome_prefeito`, `nome_secretaria`, `sigla_secretaria`, `nome_secretario`, `endereco`, `telefone`, `email`, `municipio`, `uf`, `cep`, `brasao_path`, `logo_prefeitura_path`, `logo_secretaria_path`, `textos_institucionais`, `assinaturas_cargos`, `created_at`, `updated_at`) VALUES
(5, 'Prefeitura Municipal de Capela', '13.119.961/0001-61', 'Carlos Milton Tourinho Júnior', 'Secretaria Municipal de Educação', 'Semed', 'Amarilda Santana de Almeida', 'Praça 15 de novembro, 234', '79 3263-5241', 'semecapela19@gmail.com', 'Capela', 'SE', '49700-000', 'institucional/1xDt0qdWeeNkIdq1PucPaS2cc6GSo3NQ8nL9PpeF.png', 'institucional/E4pXhzmUy7duvCsPzwSMeiSdQArL4nEvieHH6T5O.jpg', 'institucional/7FBIARLtpZ7apr6KHAVGHlBS3Weycy1sfUrb0mQG.jpg', NULL, 'Amarilda Santana de Almeida - Secretária Municipal de Educação', '2026-03-18 01:26:41', '2026-03-18 01:26:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `justificativas_falta_aluno`
--

CREATE TABLE `justificativas_falta_aluno` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `frequencia_aula_id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_direcao_id` bigint(20) UNSIGNED NOT NULL,
  `situacao_anterior` varchar(30) NOT NULL,
  `situacao_atual` varchar(30) NOT NULL DEFAULT 'falta_justificada',
  `motivo` text NOT NULL,
  `documento_comprobatorio` varchar(255) DEFAULT NULL,
  `deferida_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lancamentos_avaliativos`
--

CREATE TABLE `lancamentos_avaliativos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_avaliacao` varchar(20) NOT NULL,
  `avaliacao_referencia` varchar(80) DEFAULT NULL,
  `valor_numerico` decimal(5,2) DEFAULT NULL,
  `conceito` varchar(50) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `liberacoes_prazo_professor`
--

CREATE TABLE `liberacoes_prazo_professor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_direcao_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_lancamento` varchar(40) NOT NULL,
  `data_limite` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ativa',
  `motivo` varchar(180) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas`
--

CREATE TABLE `matriculas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aluno_id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `turma_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ano_letivo` year(4) NOT NULL,
  `tipo` enum('regular','aee') NOT NULL DEFAULT 'regular',
  `status` enum('ativa','concluida','cancelada','transferida','rematriculada') NOT NULL DEFAULT 'ativa',
  `matricula_regular_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_matricula` date NOT NULL,
  `data_encerramento` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `matriculas`
--

INSERT INTO `matriculas` (`id`, `aluno_id`, `escola_id`, `turma_id`, `ano_letivo`, `tipo`, `status`, `matricula_regular_id`, `data_matricula`, `data_encerramento`, `observacoes`, `created_at`, `updated_at`) VALUES
(2, 3, 5, 2, '2026', 'regular', 'ativa', NULL, '2026-03-17', NULL, NULL, '2026-03-18 01:59:30', '2026-03-18 01:59:30'),
(3, 4, 5, 2, '2026', 'regular', 'ativa', NULL, '2026-03-20', NULL, NULL, '2026-03-20 19:10:50', '2026-03-20 19:10:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `matricula_historicos`
--

CREATE TABLE `matricula_historicos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED NOT NULL,
  `acao` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `matricula_historicos`
--

INSERT INTO `matricula_historicos` (`id`, `matricula_id`, `acao`, `descricao`, `usuario_id`, `created_at`) VALUES
(1, 2, 'criacao', 'Matrícula regular realizada para o ano letivo 2026.', 9, '2026-03-18 01:59:30'),
(2, 3, 'criacao', 'Matrícula regular realizada para o ano letivo 2026.', 8, '2026-03-20 19:10:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `matrizes_curriculares`
--

CREATE TABLE `matrizes_curriculares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `modalidade_id` bigint(20) UNSIGNED NOT NULL,
  `serie_etapa` varchar(255) NOT NULL,
  `escola_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ano_vigencia` int(11) NOT NULL,
  `ativa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `matrizes_curriculares`
--

INSERT INTO `matrizes_curriculares` (`id`, `nome`, `modalidade_id`, `serie_etapa`, `escola_id`, `ano_vigencia`, `ativa`, `created_at`, `updated_at`) VALUES
(2, 'Matriz Ensino Fundamental 2º ao 5º Ano', 2, '2º Ano', NULL, 2026, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(3, 'Matriz Ensino Fundamental 6º ao 9º Ano', 3, '6º Ano', NULL, 2026, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriz_disciplina`
--

CREATE TABLE `matriz_disciplina` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matriz_id` bigint(20) UNSIGNED NOT NULL,
  `disciplina_id` bigint(20) UNSIGNED NOT NULL,
  `carga_horaria` int(11) NOT NULL,
  `obrigatoria` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `matriz_disciplina`
--

INSERT INTO `matriz_disciplina` (`id`, `matriz_id`, `disciplina_id`, `carga_horaria`, `obrigatoria`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 120, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(2, 2, 3, 120, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(3, 2, 4, 80, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(4, 2, 5, 80, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(5, 2, 9, 80, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(6, 2, 6, 80, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(7, 2, 11, 40, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(8, 2, 7, 40, 1, '2026-03-18 01:31:26', '2026-03-18 01:31:26'),
(9, 3, 2, 200, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(10, 3, 3, 200, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(11, 3, 4, 120, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(12, 3, 5, 120, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(13, 3, 6, 120, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(14, 3, 9, 80, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(15, 3, 10, 80, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(16, 3, 8, 80, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(17, 3, 7, 80, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(18, 3, 12, 40, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41'),
(19, 3, 11, 40, 1, '2026-03-18 01:33:41', '2026-03-18 01:33:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_16_001019_create_permission_tables', 1),
(5, '2026_03_16_001049_create_escolas_table', 1),
(6, '2026_03_16_001135_create_usuarios_escolas_table', 1),
(7, '2026_03_16_002029_add_ativo_to_usuarios_table', 1),
(8, '2026_03_16_005838_create_instituicaos_table', 1),
(9, '2026_03_16_011012_create_parametro_redes_table', 1),
(10, '2026_03_16_011016_create_modalidade_ensinos_table', 1),
(11, '2026_03_16_012014_add_gestor_fields_to_escolas_table', 1),
(12, '2026_03_16_012714_create_funcionarios_table', 1),
(13, '2026_03_16_012715_create_funcionario_escola_table', 1),
(14, '2026_03_16_113000_create_alunos_table', 1),
(15, '2026_03_16_114000_add_alunos_permissions', 1),
(16, '2026_03_16_120000_create_turmas_table', 1),
(17, '2026_03_16_121000_add_turmas_permissions', 1),
(18, '2026_03_16_152920_create_matriculas_table', 1),
(19, '2026_03_16_152930_create_matricula_historicos_table', 1),
(20, '2026_03_16_160000_fix_school_portal_permissions', 1),
(21, '2026_03_16_185002_create_disciplinas_table', 1),
(22, '2026_03_16_185003_create_matrizes_curriculares_table', 1),
(23, '2026_03_16_185004_create_matriz_disciplina_table', 1),
(24, '2026_03_16_190404_add_curriculum_permissions', 1),
(25, '2026_03_16_191508_add_matriz_id_to_turmas_table', 1),
(26, '2026_03_16_203115_create_horario_aulas_table', 1),
(27, '2026_03_16_210000_add_funcionario_id_to_usuarios_table', 1),
(28, '2026_03_16_210100_create_diarios_professor_table', 1),
(29, '2026_03_16_210200_add_diario_professor_permissions', 1),
(30, '2026_03_16_231000_create_coordenacao_pedagogica_tables', 1),
(31, '2026_03_16_231100_add_coordenacao_pedagogica_permissions', 1),
(32, '2026_03_16_235000_create_direcao_escolar_tables', 1),
(33, '2026_03_16_235100_add_direcao_escolar_permissions', 1),
(34, '2026_03_17_010000_expand_diario_professor_for_revised_stage_14', 1),
(35, '2026_03_17_020000_expand_coordenacao_pedagogica_permissions_stage_16', 1),
(36, '2026_03_17_030000_expand_direcao_escolar_permissions_stage_17', 1),
(37, '2026_03_17_040000_create_alimentacao_escolar_tables', 1),
(38, '2026_03_17_040100_add_alimentacao_escolar_permissions', 1),
(39, '2026_03_17_050000_add_nutricionista_portal_permissions', 1),
(40, '2026_03_17_060000_create_psicossocial_tables', 1),
(41, '2026_03_17_060100_add_psicossocial_permissions', 1),
(42, '2026_03_17_070000_add_documentos_portal_permissions', 1),
(43, '2026_03_17_080000_add_relatorios_portal_permissions', 1),
(44, '2026_03_17_090000_create_registros_auditoria_table', 1),
(45, '2026_03_17_090100_add_auditoria_permissions', 1),
(46, '2026_03_17_101000_expand_nutricionista_management_permissions', 2),
(47, '2026_03_19_000001_add_theme_to_users_table', 3),
(48, '2026_03_20_153318_create_demandas_psicossociais_table', 4),
(49, '2026_03_20_153343_create_triagens_psicossociais_table', 4),
(50, '2026_03_20_153412_create_sessoes_atendimentos_table', 4),
(51, '2026_03_20_153444_create_devolutivas_psicossociais_table', 4),
(52, '2026_03_20_153511_create_reavaliacoes_psicossociais_table', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `modalidades_ensino`
--

CREATE TABLE `modalidades_ensino` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `estrutura_avaliativa` varchar(255) DEFAULT NULL,
  `tipo_avaliacao` varchar(255) DEFAULT NULL,
  `carga_horaria_minima` int(11) NOT NULL DEFAULT 800,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `modalidades_ensino`
--

INSERT INTO `modalidades_ensino` (`id`, `nome`, `estrutura_avaliativa`, `tipo_avaliacao`, `carga_horaria_minima`, `ativo`, `created_at`, `updated_at`) VALUES
(2, 'Fundamental I - 2º a 5º Ano', 'Bimestral', 'Nota', 800, 1, '2026-03-18 01:27:16', '2026-03-18 01:27:16'),
(3, 'Fundamental II - 6º a 9º Ano', 'Bimestral', 'Nota', 800, 1, '2026-03-18 01:27:25', '2026-03-18 01:27:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelo_has_perfis`
--

CREATE TABLE `modelo_has_perfis` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `modelo_has_perfis`
--

INSERT INTO `modelo_has_perfis` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Usuario', 9),
(1, 'App\\Models\\Usuario', 14),
(3, 'App\\Models\\Usuario', 12),
(4, 'App\\Models\\Usuario', 13),
(6, 'App\\Models\\Usuario', 8),
(7, 'App\\Models\\Usuario', 10),
(8, 'App\\Models\\Usuario', 11);

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelo_has_permissoes`
--

CREATE TABLE `modelo_has_permissoes` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_alimentos`
--

CREATE TABLE `movimentacoes_alimentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `alimento_id` bigint(20) UNSIGNED NOT NULL,
  `fornecedor_alimento_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `quantidade` decimal(10,3) NOT NULL,
  `saldo_resultante` decimal(10,3) NOT NULL,
  `data_movimentacao` date NOT NULL,
  `data_validade` date DEFAULT NULL,
  `lote` varchar(255) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `movimentacoes_alimentos`
--

INSERT INTO `movimentacoes_alimentos` (`id`, `escola_id`, `alimento_id`, `fornecedor_alimento_id`, `usuario_id`, `tipo`, `quantidade`, `saldo_resultante`, `data_movimentacao`, `data_validade`, `lote`, `valor_unitario`, `observacoes`, `created_at`, `updated_at`) VALUES
(4, 5, 3, NULL, 10, 'entrada', 500.000, 500.000, '2026-03-17', '2026-06-25', '00256325', 55.00, 'Carne bovina para preparo de sopas e cozidos', '2026-03-18 02:36:28', '2026-03-18 02:36:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `observacoes_aluno`
--

CREATE TABLE `observacoes_aluno` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_observacao` date NOT NULL,
  `categoria` varchar(50) NOT NULL DEFAULT 'pedagogica',
  `descricao` text NOT NULL,
  `encaminhamento` text DEFAULT NULL,
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ocorrencias_diario`
--

CREATE TABLE `ocorrencias_diario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `matricula_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_ocorrencia` date NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `providencias` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'aberta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `parametros_rede`
--

CREATE TABLE `parametros_rede` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ano_letivo_vigente` int(11) DEFAULT NULL,
  `dias_letivos_minimos` int(11) NOT NULL DEFAULT 200,
  `media_minima` decimal(5,2) NOT NULL DEFAULT 6.00,
  `frequencia_minima` int(11) NOT NULL DEFAULT 75,
  `parametros_documentos` text DEFAULT NULL,
  `parametros_upload` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `parametros_rede`
--

INSERT INTO `parametros_rede` (`id`, `ano_letivo_vigente`, `dias_letivos_minimos`, `media_minima`, `frequencia_minima`, `parametros_documentos`, `parametros_upload`, `created_at`, `updated_at`) VALUES
(1, 2026, 200, 5.00, 75, NULL, NULL, '2026-03-18 01:26:44', '2026-03-18 01:27:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pendencias_professor`
--

CREATE TABLE `pendencias_professor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `origem` varchar(50) NOT NULL DEFAULT 'diario',
  `prazo` date DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'aberta',
  `resolvida_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_has_permissoes`
--

CREATE TABLE `perfil_has_permissoes` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfil_has_permissoes`
--

INSERT INTO `perfil_has_permissoes` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(20, 1),
(20, 2),
(20, 4),
(20, 5),
(20, 6),
(21, 3),
(21, 6),
(22, 3),
(22, 6),
(23, 3),
(23, 6),
(24, 3),
(24, 6),
(25, 3),
(25, 6),
(26, 3),
(26, 6),
(27, 3),
(27, 6),
(28, 4),
(28, 6),
(29, 4),
(29, 6),
(30, 4),
(30, 6),
(31, 4),
(31, 6),
(32, 4),
(32, 6),
(33, 4),
(33, 6),
(34, 4),
(34, 6),
(35, 4),
(35, 6),
(36, 5),
(36, 6),
(37, 5),
(37, 6),
(38, 5),
(38, 6),
(39, 5),
(39, 6),
(40, 5),
(40, 6),
(41, 5),
(41, 6),
(42, 5),
(42, 6),
(43, 5),
(43, 6),
(44, 5),
(44, 6),
(45, 4),
(45, 6),
(46, 4),
(46, 6),
(47, 4),
(47, 6),
(48, 4),
(48, 6),
(49, 4),
(49, 6),
(50, 4),
(50, 6),
(51, 4),
(51, 6),
(52, 4),
(52, 6),
(53, 4),
(53, 6),
(54, 5),
(54, 6),
(55, 5),
(55, 6),
(56, 5),
(56, 6),
(57, 5),
(57, 6),
(58, 5),
(58, 6),
(59, 5),
(59, 6),
(60, 5),
(60, 6),
(61, 5),
(61, 6),
(62, 5),
(62, 6),
(63, 1),
(63, 2),
(63, 5),
(64, 1),
(64, 2),
(64, 5),
(64, 7),
(65, 1),
(65, 2),
(65, 5),
(65, 7),
(66, 1),
(66, 2),
(66, 5),
(66, 7),
(67, 1),
(67, 2),
(67, 5),
(67, 7),
(68, 1),
(68, 2),
(68, 5),
(68, 7),
(69, 1),
(69, 2),
(69, 5),
(70, 1),
(70, 2),
(70, 5),
(71, 1),
(71, 2),
(71, 5),
(71, 7),
(72, 1),
(72, 2),
(72, 5),
(72, 7),
(73, 1),
(73, 2),
(73, 5),
(73, 7),
(74, 1),
(74, 2),
(74, 5),
(74, 7),
(75, 7),
(76, 7),
(77, 7),
(78, 7),
(79, 7),
(80, 7),
(81, 7),
(82, 7),
(83, 7),
(84, 7),
(85, 8),
(86, 8),
(87, 8),
(88, 8),
(89, 8),
(90, 8),
(91, 8),
(92, 8),
(93, 8),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 6),
(105, 6),
(106, 6),
(107, 5),
(108, 5),
(109, 4),
(110, 4),
(111, 3),
(112, 3),
(113, 8),
(114, 8),
(115, 6),
(116, 6),
(117, 6),
(118, 6),
(119, 1),
(119, 2),
(119, 6),
(120, 1),
(120, 2),
(120, 6),
(121, 1),
(121, 2),
(121, 6),
(122, 1),
(122, 2),
(122, 6),
(123, 6),
(124, 1),
(124, 2),
(125, 1),
(125, 2),
(126, 1),
(126, 2),
(127, 1),
(127, 2),
(127, 5),
(128, 1),
(128, 2),
(128, 4),
(128, 5),
(129, 1),
(129, 2),
(130, 1),
(130, 2),
(131, 4),
(132, 4),
(133, 5),
(134, 5),
(135, 7),
(136, 7),
(137, 8),
(138, 8),
(139, 6),
(140, 1),
(140, 2),
(141, 4),
(142, 5),
(143, 3),
(144, 7),
(145, 8),
(146, 5),
(146, 6),
(146, 8),
(147, 6),
(148, 6),
(149, 6),
(150, 6),
(151, 6),
(152, 6),
(153, 6),
(154, 6),
(155, 6),
(156, 6),
(157, 6),
(158, 6),
(159, 6),
(160, 6),
(161, 6),
(162, 6),
(163, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfis`
--

CREATE TABLE `perfis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfis`
--

INSERT INTO `perfis` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Secretário Escolar', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(2, 'Administrador da Escola', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(3, 'Professor', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(4, 'Coordenador Pedagógico', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(5, 'Diretor Escolar', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(6, 'Administrador da Rede', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(7, 'Nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(8, 'Psicologia/Psicopedagogia', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'visualizar alunos', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(2, 'criar aluno', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(3, 'editar aluno', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(4, 'detalhar aluno', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(5, 'ativar inativar aluno', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(6, 'consultar turmas', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(7, 'detalhar turma', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(8, 'cadastrar turmas', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(9, 'editar turmas', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(10, 'excluir turmas', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(11, 'consultar matrículas', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(12, 'cadastrar matrícula', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(13, 'visualizar detalhes da matrícula', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(14, 'enturmar', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(15, 'transferir', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(16, 'rematricular', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(17, 'gerenciar disciplinas', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(18, 'gerenciar matrizes', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(19, 'consultar matrizes', 'web', '2026-03-18 01:20:34', '2026-03-18 01:20:34'),
(20, 'consultar diarios', 'web', '2026-03-18 01:20:35', '2026-03-18 01:20:35'),
(21, 'criar diarios', 'web', '2026-03-18 01:20:35', '2026-03-18 01:20:35'),
(22, 'registrar aulas', 'web', '2026-03-18 01:20:35', '2026-03-18 01:20:35'),
(23, 'lancar frequencia', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(24, 'gerenciar planejamentos', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(25, 'registrar observacoes pedagogicas', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(26, 'registrar ocorrencias pedagogicas', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(27, 'gerenciar pendencias do professor', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(28, 'acompanhar diarios pedagogicamente', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(29, 'validar planejamento anual', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(30, 'validar planejamento semanal', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(31, 'validar aulas registradas', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(32, 'acompanhar frequencia pedagogica', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(33, 'acompanhar rendimento pedagogico', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(34, 'acompanhar alunos em risco', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(35, 'gerenciar pendencias docentes', 'web', '2026-03-18 01:20:36', '2026-03-18 01:20:36'),
(36, 'ver horarios', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(37, 'acompanhar diarios da direcao', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(38, 'validar planejamento pela direcao', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(39, 'validar aulas pela direcao', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(40, 'justificar faltas de alunos', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(41, 'liberar prazo de lancamento', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(42, 'registrar faltas de funcionarios', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(43, 'iniciar fechamento letivo', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(44, 'concluir fechamento letivo', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(45, 'validar planejamento por periodo', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(46, 'consultar notas e conceitos pedagogicos', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(47, 'alterar notas e conceitos pedagogicos', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(48, 'consultar horarios pedagogicamente', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(49, 'cadastrar horarios pedagogicamente', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(50, 'editar horarios pedagogicamente', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(51, 'reorganizar horarios pedagogicamente', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(52, 'consultar aulas pedagogicamente', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(53, 'ajustar aulas pedagogicamente', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(54, 'validar planejamento por periodo pela direcao', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(55, 'consultar notas e conceitos da direcao', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(56, 'alterar notas e conceitos da direcao', 'web', '2026-03-18 01:20:37', '2026-03-18 01:20:37'),
(57, 'consultar horarios da direcao', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(58, 'cadastrar horarios da direcao', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(59, 'editar horarios da direcao', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(60, 'reorganizar horarios da direcao', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(61, 'consultar aulas da direcao', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(62, 'ajustar aulas da direcao', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(63, 'consultar alimentacao escolar', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(64, 'cadastrar alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(65, 'editar alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(66, 'registrar entrada de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(67, 'registrar saida de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(68, 'lancar cardapio diario', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(69, 'consultar estoque de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(70, 'consultar movimentacoes de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(71, 'cadastrar categorias de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(72, 'cadastrar fornecedores de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(73, 'editar categorias de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(74, 'editar fornecedores de alimentos', 'web', '2026-03-18 01:20:38', '2026-03-18 01:20:38'),
(75, 'acessar portal da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(76, 'consultar alimentos da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(77, 'consultar categorias da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(78, 'consultar fornecedores da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(79, 'consultar cardapios da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(80, 'consultar estoque da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(81, 'consultar validade da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(82, 'consultar movimentacoes da nutricionista', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(83, 'consultar comparativo de alimentacao entre escolas', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(84, 'consultar relatorios gerenciais da alimentacao', 'web', '2026-03-18 01:20:39', '2026-03-18 01:20:39'),
(85, 'acessar modulo psicossocial', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(86, 'consultar agenda psicossocial', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(87, 'registrar atendimentos psicossociais', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(88, 'consultar historico psicossocial', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(89, 'registrar planos de intervencao psicossociais', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(90, 'registrar encaminhamentos psicossociais', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(91, 'registrar casos disciplinares sigilosos', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(92, 'emitir relatorios tecnicos psicossociais', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(93, 'acessar dados sigilosos psicossociais', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(94, 'consultar documentos escolares', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(95, 'emitir declaracao de matricula', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(96, 'emitir declaracao de frequencia', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(97, 'emitir comprovante de matricula', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(98, 'emitir ficha cadastral do aluno', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(99, 'emitir ficha individual do aluno', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(100, 'emitir guia de transferencia', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(101, 'emitir historico escolar', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(102, 'emitir ata escolar', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(103, 'emitir oficio escolar', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(104, 'consultar documentos institucionais da rede', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(105, 'emitir oficio institucional da rede', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(106, 'emitir modelo institucional da rede', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(107, 'consultar documentos da direcao escolar', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(108, 'emitir documentos da direcao escolar', 'web', '2026-03-18 01:20:40', '2026-03-18 01:20:40'),
(109, 'consultar documentos pedagogicos', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(110, 'emitir documentos pedagogicos', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(111, 'consultar documentos do professor', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(112, 'emitir documentos do professor', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(113, 'consultar documentos psicossociais', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(114, 'emitir documentos psicossociais', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(115, 'consultar relatorios da rede', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(116, 'emitir relatorio institucional da rede', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(117, 'emitir relatorio de matriculas da rede', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(118, 'emitir relatorio de situacao de matriculas', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(119, 'emitir relatorio de alunos aee', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(120, 'emitir relatorio quantitativo de matriculas', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(121, 'emitir relatorio mapa de turmas', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(122, 'emitir relatorio de professores por turma', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(123, 'emitir relatorio de auditoria', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(124, 'consultar relatorios escolares', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(125, 'emitir relatorios administrativos escolares', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(126, 'emitir relatorio de frequencia consolidada', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(127, 'emitir relatorio historico escolar', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(128, 'emitir relatorio ficha individual', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(129, 'emitir relatorio de alimentacao escolar', 'web', '2026-03-18 01:20:41', '2026-03-18 01:20:41'),
(130, 'consultar notas e conceitos em relatorios', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(131, 'consultar relatorios pedagogicos', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(132, 'emitir relatorios pedagogicos', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(133, 'consultar relatorios da direcao escolar', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(134, 'emitir relatorios da direcao escolar', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(135, 'consultar relatorios da nutricionista', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(136, 'emitir relatorios da nutricionista', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(137, 'consultar relatorios tecnicos do psicossocial', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(138, 'emitir relatorios tecnicos do psicossocial', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(139, 'consultar auditoria da rede', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(140, 'consultar auditoria escolar', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(141, 'consultar auditoria pedagogica', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(142, 'consultar auditoria da direcao escolar', 'web', '2026-03-18 01:20:42', '2026-03-18 01:20:42'),
(143, 'consultar auditoria do proprio trabalho docente', 'web', '2026-03-18 01:20:43', '2026-03-18 01:20:43'),
(144, 'consultar auditoria da alimentacao escolar', 'web', '2026-03-18 01:20:43', '2026-03-18 01:20:43'),
(145, 'consultar auditoria psicossocial sigilosa', 'web', '2026-03-18 01:20:43', '2026-03-18 01:20:43'),
(146, 'visualizar dados sensiveis de auditoria', 'web', '2026-03-18 01:20:43', '2026-03-18 01:20:43'),
(147, 'acessar painel', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(148, 'visualizar usuarios', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(149, 'criar usuario', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(150, 'editar usuario', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(151, 'ativar inativar usuario', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(152, 'visualizar instituicao', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(153, 'editar instituicao', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(154, 'visualizar configuracoes', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(155, 'editar configuracoes', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(156, 'visualizar escolas', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(157, 'criar escola', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(158, 'editar escola', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(159, 'ativar inativar escola', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(160, 'visualizar funcionarios', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(161, 'criar funcionario', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(162, 'editar funcionario', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(163, 'ativar inativar funcionario', 'web', '2026-03-18 01:23:19', '2026-03-18 01:23:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planejamentos_anuais`
--

CREATE TABLE `planejamentos_anuais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `periodo_vigencia_inicio` date DEFAULT NULL,
  `periodo_vigencia_fim` date DEFAULT NULL,
  `tema_gerador` varchar(255) DEFAULT NULL,
  `objetivos_gerais` text NOT NULL,
  `competencias_habilidades` text DEFAULT NULL,
  `conteudos` text DEFAULT NULL,
  `metodologia` text DEFAULT NULL,
  `recursos_didaticos` text DEFAULT NULL,
  `estrategias_pedagogicas` text DEFAULT NULL,
  `instrumentos_avaliacao` text DEFAULT NULL,
  `adequacoes_inclusao` text DEFAULT NULL,
  `estrategias_metodologicas` text DEFAULT NULL,
  `criterios_avaliacao` text DEFAULT NULL,
  `cronograma_previsto` text DEFAULT NULL,
  `referencias` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planejamentos_periodo`
--

CREATE TABLE `planejamentos_periodo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_planejamento` varchar(20) NOT NULL,
  `periodo_referencia` varchar(120) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `objetivos_aprendizagem` text NOT NULL,
  `habilidades_competencias` text DEFAULT NULL,
  `conteudos` text NOT NULL,
  `metodologia` text DEFAULT NULL,
  `recursos_didaticos` text DEFAULT NULL,
  `estrategias_pedagogicas` text DEFAULT NULL,
  `instrumentos_avaliacao` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `adequacoes_inclusao` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planejamentos_semanais`
--

CREATE TABLE `planejamentos_semanais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `data_inicio_semana` date NOT NULL,
  `data_fim_semana` date NOT NULL,
  `objetivos_semana` text NOT NULL,
  `conteudos_previstos` text NOT NULL,
  `estrategias` text DEFAULT NULL,
  `avaliacao_prevista` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos_intervencao_psicossociais`
--

CREATE TABLE `planos_intervencao_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_psicossocial_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `objetivo_geral` text NOT NULL,
  `objetivos_especificos` text DEFAULT NULL,
  `estrategias` text NOT NULL,
  `responsaveis_execucao` text DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ativo',
  `observacoes_sigilosas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `planos_intervencao_psicossociais`
--

INSERT INTO `planos_intervencao_psicossociais` (`id`, `atendimento_psicossocial_id`, `usuario_id`, `objetivo_geral`, `objetivos_especificos`, `estrategias`, `responsaveis_execucao`, `data_inicio`, `data_fim`, `status`, `observacoes_sigilosas`, `created_at`, `updated_at`) VALUES
(1, 2, 11, 'eyJpdiI6InJ1eTdLV3Y0amcvMm5MaUxGY2RzRVE9PSIsInZhbHVlIjoiejU3bmxwOWZqbVdZbSs1RjR1WTlUSDNJS000Q1RFYjFkSGwzOFhWQlROTT0iLCJtYWMiOiI4YTVmZGY2YmMxYmIzNWZmMmM3OTY2ODAyZTgxZDNiYjhiNjg1MTg3ZTU5Mzg4YTVlYTVmMzVhNWE5MTU0NzY2IiwidGFnIjoiIn0=', NULL, 'eyJpdiI6IlQwWlRWc3BWOUNmNWF5N0tFNVlaVUE9PSIsInZhbHVlIjoiYWRxYUc5SW1BMWlZQVd1MW9ubnp0K01WRFErVmUzNk9UNDhUYXVTVGc5TGpSVHZuZnRGdExiSzZONXM5ZFkwY2orMWZNY1VKbjgxbG1UTWpzK0cwa0dNVk4rc00rNG40amsyVDhON0srVkdDRzBOWm52QWRVWml0bWhMYm9IV0ciLCJtYWMiOiJjNmExMTI3ZWY2NGQ3Y2UxNWZkYTc2ZTJjNGIzMGY5OTE0MWEzOTJkNzY2MjVlYTQxNmVlNTlkMGJhNmYzYWM5IiwidGFnIjoiIn0=', NULL, '2026-03-19', NULL, 'concluido', NULL, '2026-03-20 17:48:20', '2026-03-20 17:48:20'),
(2, 2, 11, 'eyJpdiI6IlBMVldVN3p2b01iRU9WejJ1c0owVXc9PSIsInZhbHVlIjoiem1obm1TYU9nR3NZSjlkUWp2a09CSVVad0ZGNjI2b3d6OEpDWkdicVdwUT0iLCJtYWMiOiIyYWY1YjBmZGJjZDJhZGFhMThlNjYwZWFlOTE4ODlkY2E5OTI1ZTVjNGJiZmE2NjBhMzE2YzczY2FkMDU2NTkxIiwidGFnIjoiIn0=', NULL, 'eyJpdiI6ImVJWklyU0FIcVdDdmRSQ3BsUXdVSkE9PSIsInZhbHVlIjoiWVorOTRzUVNkTHJVVXpwamh5WForYVBmMk1QUmdSbzFvVjdiN0s0OFFKRDdyeUthUDZTc1dYUlZROWlYQWQ0dzI1azRSZGNUeXdUYjdPZUZBTEpMK1E9PSIsIm1hYyI6IjkxNmUxYjRjOTY1ZmFhYTc1ZDBlZDkyYzQ1ZTAwMjEzNzIxNWFlOWYwOWYzODQ0NjYyODdmNGU5NDk1ODg5MTMiLCJ0YWciOiIifQ==', NULL, '2026-03-20', NULL, 'em_acompanhamento', NULL, '2026-03-20 18:10:59', '2026-03-20 18:10:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reavaliacoes_psicossociais`
--

CREATE TABLE `reavaliacoes_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_responsavel_id` bigint(20) UNSIGNED NOT NULL,
  `data_reavaliacao` date NOT NULL,
  `progresso_observado` text DEFAULT NULL,
  `dificuldades_persistentes` text DEFAULT NULL,
  `ajuste_plano` text DEFAULT NULL,
  `frequencia_nova` enum('semanal','quinzenal','mensal','outra') DEFAULT NULL,
  `decisao` enum('manter_plano','ajustar_plano','suspender','encaminhar','encerrar') NOT NULL,
  `justificativa` text DEFAULT NULL,
  `proxima_reavaliacao` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `registros_auditoria`
--

CREATE TABLE `registros_auditoria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `escola_id` bigint(20) UNSIGNED DEFAULT NULL,
  `modulo` varchar(50) NOT NULL,
  `acao` varchar(50) NOT NULL,
  `tipo_registro` varchar(120) NOT NULL,
  `registro_type` varchar(190) DEFAULT NULL,
  `registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nivel_sensibilidade` varchar(20) NOT NULL DEFAULT 'medio',
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `valores_antes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_antes`)),
  `valores_depois` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_depois`)),
  `contexto` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contexto`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `registros_auditoria`
--

INSERT INTO `registros_auditoria` (`id`, `usuario_id`, `escola_id`, `modulo`, `acao`, `tipo_registro`, `registro_type`, `registro_id`, `nivel_sensibilidade`, `ip`, `user_agent`, `valores_antes`, `valores_depois`, `contexto`, `created_at`, `updated_at`) VALUES
(31, NULL, NULL, 'escolas', 'criacao', 'Escola', 'App\\Models\\Escola', 5, 'medio', '127.0.0.1', 'Symfony', NULL, '{\"email\":\"contato@sme.gov.br\",\"nome\":\"Secretaria Municipal de Educa\\u00e7\\u00e3o\",\"cnpj\":\"00.000.000\\/0001-00\",\"telefone\":\"(00) 0000-0000\",\"cidade\":\"Cidade Base\",\"uf\":\"UF\",\"ativo\":true}', '{\"metodo_http\":\"GET\"}', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(32, NULL, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 8, 'alto', '127.0.0.1', 'Symfony', NULL, '{\"email\":\"admin@sistema.com\",\"name\":\"Administrador\",\"ativo\":true}', '{\"metodo_http\":\"GET\"}', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(33, 8, NULL, 'instituicao', 'criacao', 'Dados Institucionais', 'App\\Models\\Instituicao', 5, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome_prefeitura\":\"Prefeitura Municipal de Capela\",\"cnpj_prefeitura\":\"13.119.961\\/0001-61\",\"nome_secretaria\":\"Secretaria Municipal de Educa\\u00e7\\u00e3o\",\"sigla_secretaria\":\"Semed\",\"nome_secretario\":\"Amarilda Santana de Almeida\",\"telefone\":\"79 3263-5241\",\"email\":\"semecapela19@gmail.com\",\"municipio\":\"Capela\",\"uf\":\"SE\"}', '{\"rota\":\"secretaria.instituicao.update\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"PUT\"}', '2026-03-18 01:26:41', '2026-03-18 01:26:41'),
(34, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 2, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Jose da Silva\",\"cpf\":\"12345678901\",\"email\":\"jose@gmail.com\",\"telefone\":\"79 98139-5097\",\"cargo\":\"Secret\\u00e1rio Escolar\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-18 01:36:17', '2026-03-18 01:36:17'),
(35, 8, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 9, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"name\":\"Jos\\u00e9 da Silva\",\"email\":\"jose@gmail.com\",\"ativo\":\"1\",\"funcionario_id\":\"2\"}', '{\"rota\":\"secretaria.usuarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-18 01:37:13', '2026-03-18 01:37:13'),
(36, 9, NULL, 'alunos', 'criacao', 'Aluno', 'App\\Models\\Aluno', 3, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome_completo\":\"Jo\\u00e3o Rezende\",\"data_nascimento\":\"1974-08-11 00:00:00\",\"responsavel_nome\":\"mae\",\"responsavel_telefone\":\"79 77777-7777\",\"cidade\":\"Aracaju\",\"uf\":\"SE\",\"rgm\":\"20260001\"}', '{\"rota\":\"secretaria-escolar.alunos.store\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\"}', '2026-03-18 01:51:28', '2026-03-18 01:51:28'),
(37, 9, 5, 'turmas', 'criacao', 'Turma', 'App\\Models\\Turma', 2, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"escola_id\":5,\"modalidade_id\":\"3\",\"nome\":\"9\\u00ba Ano - a\",\"turno\":\"Noturno\",\"ano_letivo\":\"2026\",\"vagas\":\"36\"}', '{\"rota\":\"secretaria-escolar.turmas.store\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\"}', '2026-03-18 01:58:24', '2026-03-18 01:58:24'),
(38, 9, 5, 'matriculas', 'criacao', 'Matricula', 'App\\Models\\Matricula', 2, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"aluno_id\":\"3\",\"escola_id\":5,\"turma_id\":\"2\",\"ano_letivo\":\"2026\",\"tipo\":\"regular\",\"status\":\"ativa\",\"data_matricula\":\"2026-03-17 00:00:00\"}', '{\"rota\":\"secretaria-escolar.matriculas.store\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"aluno_id\":\"3\",\"turma_id\":\"2\",\"ano_letivo\":2026,\"tipo_matricula\":\"regular\"}', '2026-03-18 01:59:30', '2026-03-18 01:59:30'),
(39, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"declaracao-matricula\",\"titulo_documento\":\"Declaracao de Matricula\"}', '2026-03-18 02:01:10', '2026-03-18 02:01:10'),
(40, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"declaracao-frequencia\",\"titulo_documento\":\"Declaracao de Frequencia\"}', '2026-03-18 02:02:14', '2026-03-18 02:02:14'),
(41, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"ficha-individual-aluno\",\"titulo_documento\":\"Ficha Individual do Aluno\"}', '2026-03-18 02:02:32', '2026-03-18 02:02:32'),
(42, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"ficha-cadastral-aluno\",\"titulo_documento\":\"Ficha Cadastral do Aluno\"}', '2026-03-18 02:02:58', '2026-03-18 02:02:58'),
(43, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"guia-transferencia\",\"titulo_documento\":\"Guia de Transferencia\"}', '2026-03-18 02:03:34', '2026-03-18 02:03:34'),
(44, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"historico-escolar\",\"titulo_documento\":\"Historico Escolar\"}', '2026-03-18 02:04:00', '2026-03-18 02:04:00'),
(45, 9, 5, 'documentos', 'emissao_documento', 'Emissao de Documento', NULL, NULL, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"secretaria-escolar.documentos.preview\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"portal\":\"secretaria-escolar\",\"tipo_documento\":\"ata-escolar\",\"titulo_documento\":\"Ata Escolar\"}', '2026-03-18 02:05:04', '2026-03-18 02:05:04'),
(46, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 3, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Ane Ravenala da Silva\",\"cpf\":\"10230652314\",\"email\":\"nutricionista@sistema.local\",\"telefone\":\"79999248114\",\"cargo\":\"Nutricionista\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-18 02:09:10', '2026-03-18 02:09:10'),
(47, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 4, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Ane Ravenala da Silva\",\"cpf\":\"36589658741\",\"email\":\"nutricionista@sistema.local\",\"telefone\":\"79999248114\",\"cargo\":\"Nutricionista\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-18 02:09:20', '2026-03-18 02:09:20'),
(48, 8, NULL, 'funcionarios', 'alteracao', 'Funcionario', 'App\\Models\\Funcionario', 4, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"ativo\":1}', '{\"ativo\":false}', '{\"rota\":\"secretaria.funcionarios.toggle\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"PATCH\"}', '2026-03-18 02:10:14', '2026-03-18 02:10:14'),
(49, 8, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 10, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"name\":\"Ane Ravenala da Silva\",\"email\":\"nutricionista@sistema.local\",\"ativo\":\"1\",\"funcionario_id\":\"3\"}', '{\"rota\":\"secretaria.usuarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-18 02:10:56', '2026-03-18 02:10:56'),
(50, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 2, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Frutas\",\"descricao\":\"Frutas frescas servidas in natura ou em prepara\\u00e7\\u00f5es\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:31:07', '2026-03-18 02:31:07'),
(51, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 3, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Verduras e Legumes\",\"descricao\":\"Hortalicas e legumes para preparo diario\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:31:30', '2026-03-18 02:31:30'),
(52, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 4, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Graos e Cereais\",\"descricao\":\"Arroz, milho, aveia, flocos e derivados\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:31:41', '2026-03-18 02:31:41'),
(53, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 5, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Proteinas\",\"descricao\":\"Carnes, ovos, frango, peixe e outras fontes proteicas\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:31:52', '2026-03-18 02:31:52'),
(54, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 6, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Laticinios\",\"descricao\":\"Leite, queijo, iogurte e derivados\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:32:03', '2026-03-18 02:32:03'),
(55, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 7, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Panificacao\",\"descricao\":\"Pao, biscoitos e massas usadas na alimentacao escolar\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:32:14', '2026-03-18 02:32:14'),
(56, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 8, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Bebidas\",\"descricao\":\"Sucos, leite e outras bebidas servidas\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:32:25', '2026-03-18 02:32:25'),
(57, 10, NULL, 'alimentacao', 'criacao', 'Categoria de Alimento', 'App\\Models\\CategoriaAlimento', 9, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Temperos e Complementos\",\"descricao\":\"Sal, alho, cebola, oleo, acucar e itens de apoio ao preparo\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.categorias.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:32:43', '2026-03-18 02:32:43'),
(58, 10, NULL, 'alimentacao', 'criacao', 'Alimento', 'App\\Models\\Alimento', 2, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"categoria_alimento_id\":\"2\",\"nome\":\"Banana\",\"unidade_medida\":\"DZ\",\"estoque_minimo\":\"100\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.alimentos.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:34:00', '2026-03-18 02:34:00'),
(59, 10, NULL, 'alimentacao', 'criacao', 'Alimento', 'App\\Models\\Alimento', 3, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"categoria_alimento_id\":\"5\",\"nome\":\"Carne bovina m\\u00fasculo\",\"unidade_medida\":\"kg\",\"estoque_minimo\":\"500\",\"controla_validade\":\"1\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.alimentos.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:34:43', '2026-03-18 02:34:43'),
(60, 10, NULL, 'alimentacao', 'criacao', 'Alimento', 'App\\Models\\Alimento', 4, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"categoria_alimento_id\":\"9\",\"nome\":\"Sal\",\"unidade_medida\":\"kg\",\"estoque_minimo\":\"50\",\"controla_validade\":\"1\",\"ativo\":\"1\"}', '{\"rota\":\"nutricionista.alimentos.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:35:01', '2026-03-18 02:35:01'),
(61, 10, 5, 'alimentacao', 'criacao', 'Movimentacao de Alimento', 'App\\Models\\MovimentacaoAlimento', 4, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"5\",\"alimento_id\":\"3\",\"fornecedor_alimento_id\":null,\"tipo\":\"entrada\",\"quantidade\":\"500\",\"saldo_resultante\":500,\"data_movimentacao\":\"2026-03-17 00:00:00\",\"data_validade\":\"2026-06-25 00:00:00\"}', '{\"rota\":\"nutricionista.movimentacoes.store\",\"portal_origem\":\"nutricionista\",\"metodo_http\":\"POST\"}', '2026-03-18 02:36:28', '2026-03-18 02:36:28'),
(62, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 5, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Na\\u00edne Ferreira dos Santos\",\"cpf\":\"33333333333\",\"email\":\"naine@gmail.com\",\"telefone\":\"(99) 99999-9999\",\"cargo\":\"Psic\\u00f3logo\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-19 20:49:58', '2026-03-19 20:49:58'),
(63, 8, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 11, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"name\":\"Na\\u00edne Ferreira dos Santos\",\"email\":\"naine@gmail.com\",\"ativo\":\"1\",\"funcionario_id\":\"5\"}', '{\"rota\":\"secretaria.usuarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-19 21:37:38', '2026-03-19 21:37:38'),
(64, 8, NULL, 'escolas', 'alteracao', 'Escola', 'App\\Models\\Escola', 5, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"nome\":\"Secretaria Municipal de Educa\\u00e7\\u00e3o\",\"email\":\"contato@sme.gov.br\",\"telefone\":\"(00) 0000-0000\",\"cidade\":\"Cidade Base\",\"uf\":\"UF\"}', '{\"nome\":\"Escola Municipal Z\\u00f3zimo Lima\",\"email\":\"zozimo@gmail.com\",\"telefone\":\"(79) 0000-0000\",\"cidade\":\"Capela\",\"uf\":\"SE\"}', '{\"rota\":\"secretaria.escolas.update\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"PUT\"}', '2026-03-19 22:07:49', '2026-03-19 22:07:49'),
(65, 8, NULL, 'escolas', 'criacao', 'Escola', 'App\\Models\\Escola', 6, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Escola Municipal Major Honorino Leal\",\"cnpj\":\"36.564.071\\/0001-64\",\"email\":\"major@gmail.com\",\"telefone\":\"(79) 55555-5555\",\"cidade\":\"Capela\",\"uf\":\"SE\"}', '{\"rota\":\"secretaria.escolas.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-19 22:09:12', '2026-03-19 22:09:12'),
(66, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 6, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Prefessor Amado Silva\",\"cpf\":\"77777777777\",\"email\":\"professor@gmail.com\",\"telefone\":\"(79) 44444-4444\",\"cargo\":\"Professor\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-19 23:15:10', '2026-03-19 23:15:10'),
(67, 8, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 12, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"name\":\"Prefessor Amado Silva\",\"email\":\"professor@gmail.com\",\"ativo\":\"1\",\"funcionario_id\":\"6\"}', '{\"rota\":\"secretaria.usuarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-19 23:15:44', '2026-03-19 23:15:44'),
(68, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 7, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"nome\":\"Coordenadora Escolar\",\"cpf\":\"99999999999\",\"email\":\"coordenadora@gmail.com\",\"telefone\":\"(79) 66666-6666\",\"cargo\":\"Coordenador\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-20 00:02:17', '2026-03-20 00:02:17'),
(69, 8, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 13, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"name\":\"Coordenadora Escolar\",\"email\":\"coordenadora@gmail.com\",\"ativo\":\"1\",\"funcionario_id\":\"7\"}', '{\"rota\":\"secretaria.usuarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-20 00:02:50', '2026-03-20 00:02:50'),
(70, 8, NULL, 'funcionarios', 'criacao', 'Funcionario', 'App\\Models\\Funcionario', 8, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '{\"nome\":\"Assistente Administrativo Escolar\",\"cpf\":\"88888888888\",\"email\":\"assistente@gmail.com\",\"telefone\":\"(79) 22222-2222\",\"cargo\":\"Secret\\u00e1rio Escolar\"}', '{\"rota\":\"secretaria.funcionarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-20 00:09:18', '2026-03-20 00:09:18'),
(71, 8, NULL, 'usuarios', 'criacao', 'Usuario', 'App\\Models\\Usuario', 14, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '{\"name\":\"Assistente Administrativo Escolar\",\"email\":\"assistente@gmail.com\",\"ativo\":\"1\",\"funcionario_id\":\"8\"}', '{\"rota\":\"secretaria.usuarios.store\",\"portal_origem\":\"secretaria\",\"metodo_http\":\"POST\"}', '2026-03-20 00:09:42', '2026-03-20 00:09:42'),
(72, 14, NULL, 'alunos', 'criacao', 'Aluno', 'App\\Models\\Aluno', 4, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '{\"nome_completo\":\"antonio carlos gonzaga\",\"data_nascimento\":\"2015-06-11 00:00:00\",\"responsavel_nome\":\"ana j\\u00falia\",\"responsavel_telefone\":\"(79) 99999-9999\",\"cidade\":\"Capela\",\"uf\":\"SE\",\"rgm\":\"20260002\"}', '{\"rota\":\"secretaria-escolar.alunos.store\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\"}', '2026-03-20 00:14:25', '2026-03-20 00:14:25'),
(73, 14, 6, 'turmas', 'criacao', 'Turma', 'App\\Models\\Turma', 3, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '{\"escola_id\":6,\"modalidade_id\":\"3\",\"nome\":\"9\\u00ba Ano - a\",\"turno\":\"Vespertino\",\"ano_letivo\":\"2026\",\"vagas\":\"35\"}', '{\"rota\":\"secretaria-escolar.turmas.store\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\"}', '2026-03-20 00:15:13', '2026-03-20 00:15:13'),
(74, 13, 6, 'horarios', 'criacao', 'Horario de Aula', 'App\\Models\\HorarioAula', 1, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"6\",\"turma_id\":\"3\",\"disciplina_id\":\"3\",\"professor_id\":\"6\",\"dia_semana\":\"2\",\"horario_inicial\":\"12:30\",\"horario_final\":\"14:10\",\"ordem_aula\":\"1\",\"ativo\":true}', '{\"rota\":\"secretaria-escolar.coordenacao.horarios.store\",\"portal_origem\":\"coordenacao\",\"metodo_http\":\"POST\"}', '2026-03-20 00:20:39', '2026-03-20 00:20:39'),
(75, 13, 6, 'horarios', 'criacao', 'Horario de Aula', 'App\\Models\\HorarioAula', 2, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"6\",\"turma_id\":\"3\",\"disciplina_id\":\"5\",\"professor_id\":\"6\",\"dia_semana\":\"2\",\"horario_inicial\":\"14:10\",\"horario_final\":\"15:00\",\"ordem_aula\":null,\"ativo\":true}', '{\"rota\":\"secretaria-escolar.coordenacao.horarios.store\",\"portal_origem\":\"coordenacao\",\"metodo_http\":\"POST\"}', '2026-03-20 00:20:39', '2026-03-20 00:20:39'),
(76, 13, 6, 'horarios', 'criacao', 'Horario de Aula', 'App\\Models\\HorarioAula', 3, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"6\",\"turma_id\":\"3\",\"disciplina_id\":\"6\",\"professor_id\":\"6\",\"dia_semana\":\"2\",\"horario_inicial\":\"15:15\",\"horario_final\":\"16:05\",\"ordem_aula\":null,\"ativo\":true}', '{\"rota\":\"secretaria-escolar.coordenacao.horarios.store\",\"portal_origem\":\"coordenacao\",\"metodo_http\":\"POST\"}', '2026-03-20 00:20:39', '2026-03-20 00:20:39'),
(77, 13, 6, 'horarios', 'criacao', 'Horario de Aula', 'App\\Models\\HorarioAula', 4, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"6\",\"turma_id\":\"3\",\"disciplina_id\":\"9\",\"professor_id\":\"6\",\"dia_semana\":\"2\",\"horario_inicial\":\"16:05\",\"horario_final\":\"16:55\",\"ordem_aula\":null,\"ativo\":true}', '{\"rota\":\"secretaria-escolar.coordenacao.horarios.store\",\"portal_origem\":\"coordenacao\",\"metodo_http\":\"POST\"}', '2026-03-20 00:20:39', '2026-03-20 00:20:39'),
(78, 12, 6, 'diarios', 'criacao', 'Diario do Professor', 'App\\Models\\DiarioProfessor', 2, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '{\"escola_id\":\"6\",\"turma_id\":\"3\",\"disciplina_id\":\"3\",\"professor_id\":6,\"ano_letivo\":\"2026\",\"periodo_tipo\":\"bimestre\",\"periodo_referencia\":\"1\"}', '{\"rota\":\"professor.diario.store\",\"portal_origem\":\"professor\",\"metodo_http\":\"POST\",\"professor_id\":6,\"turma_id\":\"3\"}', '2026-03-20 00:22:41', '2026-03-20 00:22:41'),
(79, 12, 6, 'aulas', 'criacao', 'Registro de Aula', 'App\\Models\\RegistroAula', 1, 'medio', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', NULL, '{\"data_aula\":\"2026-03-16 00:00:00\",\"titulo\":\"N\\u00fameros naturais - Opera\\u00e7\\u00f5es de soma e subtra\\u00e7\\u00e3o\",\"conteudo_ministrado\":\"Opera\\u00e7\\u00f5es com n\\u00fameros naturais, opera\\u00e7\\u00f5es de soma e subtra\\u00e7\\u00e3o, problemas envolvendo as opera\\u00e7\\u00f5es.\",\"quantidade_aulas\":\"2\",\"aula_dada\":true}', '{\"rota\":\"professor.diario.registro-aula.store\",\"portal_origem\":\"professor\",\"metodo_http\":\"POST\",\"diario_professor_id\":2,\"professor_id\":6,\"turma_id\":3,\"disciplina_id\":3,\"ano_letivo\":2026}', '2026-03-20 00:24:56', '2026-03-20 00:24:56'),
(80, 11, 6, 'psicossocial', 'criacao', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"6\",\"tipo_publico\":\"aluno\",\"tipo_atendimento\":\"psicologia\",\"natureza\":\"agendado\",\"status\":\"agendado\",\"data_agendada\":\"2026-03-19 10:00:00\",\"data_realizacao\":\"2026-03-19 10:30:00\",\"nivel_sigilo\":\"restrito\",\"requer_acompanhamento\":true}', '{\"rota\":\"psicologia.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 17:45:46', '2026-03-20 17:45:46'),
(81, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:45:47', '2026-03-20 17:45:47'),
(82, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:46:16', '2026-03-20 17:46:16'),
(83, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:46:50', '2026-03-20 17:46:50'),
(84, 11, 6, 'psicossocial', 'criacao', 'Plano de Intervencao', 'App\\Models\\PlanoIntervencaoPsicossocial', 1, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"objetivo_geral\":\"eyJpdiI6InJ1eTdLV3Y0amcvMm5MaUxGY2RzRVE9PSIsInZhbHVlIjoiejU3bmxwOWZqbVdZbSs1RjR1WTlUSDNJS000Q1RFYjFkSGwzOFhWQlROTT0iLCJtYWMiOiI4YTVmZGY2YmMxYmIzNWZmMmM3OTY2ODAyZTgxZDNiYjhiNjg1MTg3ZTU5Mzg4YTVlYTVmMzVhNWE5MTU0NzY2IiwidGFnIjoiIn0=\",\"estrategias\":\"eyJpdiI6IlQwWlRWc3BWOUNmNWF5N0tFNVlaVUE9PSIsInZhbHVlIjoiYWRxYUc5SW1BMWlZQVd1MW9ubnp0K01WRFErVmUzNk9UNDhUYXVTVGc5TGpSVHZuZnRGdExiSzZONXM5ZFkwY2orMWZNY1VKbjgxbG1UTWpzK0cwa0dNVk4rc00rNG40amsyVDhON0srVkdDRzBOWm52QWRVWml0bWhMYm9IV0ciLCJtYWMiOiJjNmExMTI3ZWY2NGQ3Y2UxNWZkYTc2ZTJjNGIzMGY5OTE0MWEzOTJkNzY2MjVlYTQxNmVlNTlkMGJhNmYzYWM5IiwidGFnIjoiIn0=\",\"data_inicio\":\"2026-03-19 00:00:00\",\"status\":\"concluido\"}', '{\"rota\":\"psicologia.planos.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 17:48:20', '2026-03-20 17:48:20'),
(85, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:48:21', '2026-03-20 17:48:21'),
(86, 11, 6, 'psicossocial', 'criacao', 'Caso Disciplinar Sigiloso', 'App\\Models\\CasoDisciplinarSigiloso', 1, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"titulo\":\"Caso disciplinar vinculado ao atendimento\",\"status\":\"aberto\",\"escola_id\":6}', '{\"rota\":\"psicologia.casos.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 17:48:39', '2026-03-20 17:48:39'),
(87, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:48:40', '2026-03-20 17:48:40'),
(88, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:49:08', '2026-03-20 17:49:08'),
(89, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 17:58:32', '2026-03-20 17:58:32'),
(90, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:02:08', '2026-03-20 18:02:08'),
(91, 11, 6, 'psicossocial', 'criacao', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"escola_id\":\"6\",\"tipo_publico\":\"aluno\",\"tipo_atendimento\":\"psicologia\",\"natureza\":\"retorno\",\"status\":\"realizado\",\"data_agendada\":\"2026-03-20 09:00:00\",\"data_realizacao\":\"2026-03-20 09:00:00\",\"nivel_sigilo\":\"restrito\",\"requer_acompanhamento\":true}', '{\"rota\":\"psicologia.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 18:07:16', '2026-03-20 18:07:16'),
(92, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:07:16', '2026-03-20 18:07:16'),
(93, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:07:47', '2026-03-20 18:07:47'),
(94, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:08:39', '2026-03-20 18:08:39'),
(95, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:08:49', '2026-03-20 18:08:49'),
(96, 11, 6, 'psicossocial', 'alteracao', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status\":\"agendado\",\"data_realizacao\":\"2026-03-19 10:30:00\"}', '{\"status\":\"realizado\",\"data_realizacao\":\"2026-03-20 15:08:53\"}', '{\"rota\":\"psicologia.atendimento.finalizar\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"PATCH\"}', '2026-03-20 18:08:53', '2026-03-20 18:08:53'),
(97, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:08:53', '2026-03-20 18:08:53'),
(98, 11, 6, 'psicossocial', 'criacao', 'Plano de Intervencao', 'App\\Models\\PlanoIntervencaoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"objetivo_geral\":\"eyJpdiI6IlBMVldVN3p2b01iRU9WejJ1c0owVXc9PSIsInZhbHVlIjoiem1obm1TYU9nR3NZSjlkUWp2a09CSVVad0ZGNjI2b3d6OEpDWkdicVdwUT0iLCJtYWMiOiIyYWY1YjBmZGJjZDJhZGFhMThlNjYwZWFlOTE4ODlkY2E5OTI1ZTVjNGJiZmE2NjBhMzE2YzczY2FkMDU2NTkxIiwidGFnIjoiIn0=\",\"estrategias\":\"eyJpdiI6ImVJWklyU0FIcVdDdmRSQ3BsUXdVSkE9PSIsInZhbHVlIjoiWVorOTRzUVNkTHJVVXpwamh5WForYVBmMk1QUmdSbzFvVjdiN0s0OFFKRDdyeUthUDZTc1dYUlZROWlYQWQ0dzI1azRSZGNUeXdUYjdPZUZBTEpMK1E9PSIsIm1hYyI6IjkxNmUxYjRjOTY1ZmFhYTc1ZDBlZDkyYzQ1ZTAwMjEzNzIxNWFlOWYwOWYzODQ0NjYyODdmNGU5NDk1ODg5MTMiLCJ0YWciOiIifQ==\",\"data_inicio\":\"2026-03-20 00:00:00\",\"status\":\"em_acompanhamento\"}', '{\"rota\":\"psicologia.planos.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 18:10:59', '2026-03-20 18:10:59'),
(99, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:11:00', '2026-03-20 18:11:00'),
(100, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:11:29', '2026-03-20 18:11:29'),
(101, 11, 6, 'psicossocial', 'criacao', 'Relatorio Tecnico Psicossocial', 'App\\Models\\RelatorioTecnicoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"tipo_relatorio\":\"parecer_inicial\",\"titulo\":\"Paciente apresenta quadro de desaten\\u00e7\\u00e3o severa\",\"data_emissao\":\"2026-03-20 00:00:00\",\"escola_id\":6}', '{\"rota\":\"psicologia.relatorios_tecnicos.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 18:12:59', '2026-03-20 18:12:59'),
(102, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:13:00', '2026-03-20 18:13:00'),
(103, 11, 6, 'psicossocial', 'criacao', 'Caso Disciplinar Sigiloso', 'App\\Models\\CasoDisciplinarSigiloso', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"titulo\":\"Caso disciplinar vinculado ao atendimento\",\"status\":\"aberto\",\"escola_id\":6}', '{\"rota\":\"psicologia.casos.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 18:13:27', '2026-03-20 18:13:27'),
(104, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:13:28', '2026-03-20 18:13:28'),
(105, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 2, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":2,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:14:26', '2026-03-20 18:14:26'),
(106, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:14:47', '2026-03-20 18:14:47'),
(107, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:23:19', '2026-03-20 18:23:19'),
(108, 11, 6, 'psicossocial', 'criacao', 'Encaminhamento Psicossocial', 'App\\Models\\EncaminhamentoPsicossocial', 1, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '{\"tipo\":\"externo\",\"destino\":\"Secretaria de Sa\\u00fade\",\"motivo\":\"eyJpdiI6Img0Z3dnOEUvWXBubUxPazdZOVYrMkE9PSIsInZhbHVlIjoiVmdCT3c4RmRpb3dOaThNT24yMGRwZ1RLdDBHbHlHK3ptbUtRMjJyUVNPTGk1SmJkb1phTnI1UzNHR0lpdVZlRlFXRGt1RDRDVWErYWFrOHZWNXVoc3c9PSIsIm1hYyI6ImM1OTNjMTczMzRlNTVlMjhkMzQyMDJhNDI3OTIxYjhhZjY0NmYwZjE3MDRmNWU0NzdiZTNkMjFkOWU4MzUxNTAiLCJ0YWciOiIifQ==\",\"status\":\"concluido\",\"data_encaminhamento\":\"2026-03-20 00:00:00\"}', '{\"rota\":\"psicologia.encaminhamentos.store\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"POST\"}', '2026-03-20 18:24:37', '2026-03-20 18:24:37'),
(109, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:24:38', '2026-03-20 18:24:38'),
(110, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:45:18', '2026-03-20 18:45:18'),
(111, 11, 6, 'psicossocial', 'visualizacao_sensivel', 'Atendimento Psicossocial', 'App\\Models\\AtendimentoPsicossocial', 3, 'sigiloso', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, NULL, '{\"rota\":\"psicologia.show\",\"portal_origem\":\"psicossocial\",\"metodo_http\":\"GET\",\"atendimento_id\":3,\"tipo_publico\":\"aluno\",\"nivel_sigilo\":\"restrito\"}', '2026-03-20 18:46:30', '2026-03-20 18:46:30'),
(112, 8, 5, 'matriculas', 'criacao', 'Matricula', 'App\\Models\\Matricula', 3, 'alto', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', NULL, '{\"aluno_id\":\"4\",\"escola_id\":5,\"turma_id\":\"2\",\"ano_letivo\":\"2026\",\"tipo\":\"regular\",\"status\":\"ativa\",\"data_matricula\":\"2026-03-20 00:00:00\"}', '{\"rota\":\"secretaria-escolar.matriculas.store\",\"portal_origem\":\"secretaria-escolar\",\"metodo_http\":\"POST\",\"aluno_id\":\"4\",\"turma_id\":\"2\",\"ano_letivo\":2026,\"tipo_matricula\":\"regular\"}', '2026-03-20 19:10:50', '2026-03-20 19:10:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `registros_aula`
--

CREATE TABLE `registros_aula` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `horario_aula_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usuario_registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_aula` date NOT NULL,
  `titulo` varchar(160) NOT NULL,
  `conteudo_previsto` text DEFAULT NULL,
  `conteudo_ministrado` text NOT NULL,
  `metodologia` text DEFAULT NULL,
  `recursos_utilizados` text DEFAULT NULL,
  `quantidade_aulas` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `aula_dada` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `registros_aula`
--

INSERT INTO `registros_aula` (`id`, `diario_professor_id`, `horario_aula_id`, `usuario_registro_id`, `data_aula`, `titulo`, `conteudo_previsto`, `conteudo_ministrado`, `metodologia`, `recursos_utilizados`, `quantidade_aulas`, `aula_dada`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 12, '2026-03-16', 'Números naturais - Operações de soma e subtração', NULL, 'Operações com números naturais, operações de soma e subtração, problemas envolvendo as operações.', NULL, NULL, 2, 1, '2026-03-20 00:24:56', '2026-03-20 00:24:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios_tecnicos_psicossociais`
--

CREATE TABLE `relatorios_tecnicos_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_psicossocial_id` bigint(20) UNSIGNED DEFAULT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_emissor_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_relatorio` varchar(30) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo_sigiloso` text NOT NULL,
  `data_emissao` date NOT NULL,
  `observacoes_restritas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `relatorios_tecnicos_psicossociais`
--

INSERT INTO `relatorios_tecnicos_psicossociais` (`id`, `atendimento_psicossocial_id`, `escola_id`, `usuario_emissor_id`, `tipo_relatorio`, `titulo`, `conteudo_sigiloso`, `data_emissao`, `observacoes_restritas`, `created_at`, `updated_at`) VALUES
(2, 2, 6, 11, 'parecer_inicial', 'Paciente apresenta quadro de desatenção severa', 'eyJpdiI6IlNRYU1KdGFoWGxiQTI4eUlHcnVWQWc9PSIsInZhbHVlIjoiNHp3bjNlSzVacFBBa1dNNEdWeFZwT3ZYcmh5VlFoQ3hpb2ZDV0dyeUdaYjhtYzRBNVZZR0V2dVE0MW15MFg3SUVDMit5QXY2QjNUN2gzMEU5REVvbytyU3MwZU9oTU9QVDZONUZSb2JNTnYwTjRaWUlGSk1hdmZqN3l6Q2MxWDdUSUJvN1gxU095MHk1cUFQTEZLbURBPT0iLCJtYWMiOiJjODJjNTVjNDY3OTgzYjdlMzNjMjE1NTU2MjBkM2ZlMTU4NGVlMDU1YjkwY2Q1YzI3OGU1OTk1YzViNjE1YmFlIiwidGFnIjoiIn0=', '2026-03-20', NULL, '2026-03-20 18:12:59', '2026-03-20 18:12:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9ThbH1UzEGHEqTPK22FUEGRdmWELXTnKE0jfYVSU', 11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNHVIbGRwQnRIcHF1V2NiZmVKWWJEUTg2UWFYRUNtd3BpanBCdWlJUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjgwOiJodHRwOi8vbG9jYWxob3N0L3Npc3RlbWEtZXNjb2xhci9wdWJsaWMvcHNpY29sb2dpYS1wc2ljb3BlZGFnb2dpYS9kZW1hbmRhcy9jcmlhciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=', 1774024292),
('fT500boW0GVPjePdb8CLBVELrylvo2xW7fnWitt2', 8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRHN4aDh5WUNObUFudUJSellnWThQVTBFS1ZXVDZzak9XcDN6amhpQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY5OiJodHRwOi8vbG9jYWxob3N0L3Npc3RlbWEtZXNjb2xhci9wdWJsaWMvc2VjcmV0YXJpYS1lc2NvbGFyL21hdHJpY3VsYXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4O30=', 1774023051);

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessoes_atendimentos`
--

CREATE TABLE `sessoes_atendimentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `atendimento_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_profissional_id` bigint(20) UNSIGNED NOT NULL,
  `funcionario_profissional_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_sessao` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `tipo_sessao` enum('avaliacao','intervencao','retorno','emergencial','acolhimento','devolutiva','reavaliacao') NOT NULL DEFAULT 'intervencao',
  `objetivo_sessao` text DEFAULT NULL,
  `relato_sessao` text DEFAULT NULL,
  `estrategias_utilizadas` text DEFAULT NULL,
  `comportamento_observado` text DEFAULT NULL,
  `evolucao_percebida` text DEFAULT NULL,
  `encaminhamentos_definidos` text DEFAULT NULL,
  `necessita_retorno` tinyint(1) NOT NULL DEFAULT 1,
  `proximo_passo` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('realizado','remarcado','faltou','cancelado') NOT NULL DEFAULT 'realizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `triagens_psicossociais`
--

CREATE TABLE `triagens_psicossociais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `demanda_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_triador_id` bigint(20) UNSIGNED NOT NULL,
  `resumo_caso` text DEFAULT NULL,
  `sinais_observados` text DEFAULT NULL,
  `historico_breve` text DEFAULT NULL,
  `urgencia` enum('baixa','media','alta','critica') NOT NULL DEFAULT 'media',
  `risco_identificado` tinyint(1) NOT NULL DEFAULT 0,
  `descricao_risco` text DEFAULT NULL,
  `nivel_sigilo` enum('normal','reforcado') NOT NULL DEFAULT 'normal',
  `decisao` enum('iniciar_atendimento','observar','encaminhar_externo','devolver_pedagogico','encerrar_sem_atendimento') NOT NULL,
  `justificativa_decisao` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_triagem` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `modalidade_id` bigint(20) UNSIGNED NOT NULL,
  `matriz_id` bigint(20) UNSIGNED DEFAULT NULL,
  `serie_etapa` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `turno` enum('Matutino','Vespertino','Noturno','Integral') NOT NULL,
  `ano_letivo` year(4) NOT NULL,
  `vagas` int(11) NOT NULL DEFAULT 0,
  `is_multisseriada` tinyint(1) NOT NULL DEFAULT 0,
  `ativa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`id`, `escola_id`, `modalidade_id`, `matriz_id`, `serie_etapa`, `nome`, `turno`, `ano_letivo`, `vagas`, `is_multisseriada`, `ativa`, `created_at`, `updated_at`) VALUES
(2, 5, 3, 3, '9º Ano', '9º Ano - a', 'Noturno', '2026', 36, 0, 1, '2026-03-18 01:58:24', '2026-03-18 01:58:24'),
(3, 6, 3, 3, '9º', '9º Ano - a', 'Vespertino', '2026', 35, 0, 1, '2026-03-20 00:15:13', '2026-03-20 00:15:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `funcionario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `theme` varchar(20) NOT NULL DEFAULT 'lilas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `name`, `email`, `email_verified_at`, `password`, `ativo`, `funcionario_id`, `remember_token`, `theme`, `created_at`, `updated_at`) VALUES
(8, 'Administrador', 'admin@sistema.com', NULL, '$2y$12$RensC3/A9hYERx9QhiZaWetcgJ3.7IpLJpnFoNS6UWerBUS2oWWZq', 1, NULL, 'F0xneK8jUjLCC3uIOWwJpoJeyFjqppl2pdrCYvp9Ic3mRSU9WTRUhALkdPoF', 'lilas', '2026-03-18 01:23:19', '2026-03-18 01:23:19'),
(9, 'José da Silva', 'jose@gmail.com', NULL, '$2y$12$xLQPyKL7Jaw1tMqGVqmiK.9fSHatOVA0NwCF5lO.2SHotIA385yee', 1, 2, NULL, 'lilas', '2026-03-18 01:37:13', '2026-03-18 01:37:13'),
(10, 'Ane Ravenala da Silva', 'nutricionista@sistema.local', NULL, '$2y$12$7UUouXCqUj..S8qs.ACYouoJZSNxywHLKe58ip4jOQW81/ia6sIDy', 1, 3, NULL, 'lilas', '2026-03-18 02:10:56', '2026-03-18 02:10:56'),
(11, 'Naíne Ferreira dos Santos', 'naine@gmail.com', NULL, '$2y$12$fw1zBmIdUCKpiUV/SojTvuK1k/y9HFJcdw9zLctMRqay99UXs/R.i', 1, 5, NULL, 'lilas', '2026-03-19 21:37:38', '2026-03-19 21:37:38'),
(12, 'Prefessor Amado Silva', 'professor@gmail.com', NULL, '$2y$12$JJ9aVWYEt4P/6jgNf3Te/OR.amIOGd1m6Lad2dU4kOU9TgHCAGOJm', 1, 6, NULL, 'verde', '2026-03-19 23:15:44', '2026-03-19 23:58:35'),
(13, 'Coordenadora Escolar', 'coordenadora@gmail.com', NULL, '$2y$12$XatoXavvAQ33JjJCZOuKJehSD1P/aSB0eDxUsgyd.R3pVEtxCR6A6', 1, 7, NULL, 'lilas', '2026-03-20 00:02:50', '2026-03-20 00:02:50'),
(14, 'Assistente Administrativo Escolar', 'assistente@gmail.com', NULL, '$2y$12$iZJTpKHJXSn/kYVKi1Mn4eLDWyNC98O1tTYTN9cKMhweszUxpjali', 1, 8, NULL, 'lilas', '2026-03-20 00:09:42', '2026-03-20 00:09:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_escolas`
--

CREATE TABLE `usuarios_escolas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `escola_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios_escolas`
--

INSERT INTO `usuarios_escolas` (`id`, `usuario_id`, `escola_id`, `created_at`, `updated_at`) VALUES
(4, 8, 5, NULL, NULL),
(5, 9, 5, NULL, NULL),
(6, 12, 5, NULL, NULL),
(7, 12, 6, NULL, NULL),
(8, 13, 6, NULL, NULL),
(9, 14, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `validacoes_direcao`
--

CREATE TABLE `validacoes_direcao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `validavel_type` varchar(255) NOT NULL,
  `validavel_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_direcao_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(30) NOT NULL,
  `parecer` text NOT NULL,
  `observacoes_internas` text DEFAULT NULL,
  `validado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `validacoes_pedagogicas`
--

CREATE TABLE `validacoes_pedagogicas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `diario_professor_id` bigint(20) UNSIGNED NOT NULL,
  `validavel_type` varchar(255) NOT NULL,
  `validavel_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_coordenador_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pendente',
  `parecer` text NOT NULL,
  `observacoes_internas` text DEFAULT NULL,
  `validado_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acompanhamentos_pedagogicos_aluno`
--
ALTER TABLE `acompanhamentos_pedagogicos_aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `acompanhamento_pedagogico_aluno_unico` (`diario_professor_id`,`matricula_id`),
  ADD KEY `acompanhamentos_pedagogicos_aluno_matricula_id_foreign` (`matricula_id`),
  ADD KEY `acompanhamentos_pedagogicos_aluno_usuario_coordenador_id_foreign` (`usuario_coordenador_id`),
  ADD KEY `acompanhamento_pedagogico_risco_idx` (`situacao_risco`,`precisa_intervencao`);

--
-- Índices de tabela `alimentos`
--
ALTER TABLE `alimentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alimentos_categoria_alimento_id_nome_unique` (`categoria_alimento_id`,`nome`),
  ADD KEY `alimentos_ativo_nome_index` (`ativo`,`nome`);

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alunos_rgm_unique` (`rgm`),
  ADD UNIQUE KEY `alunos_cpf_unique` (`cpf`);

--
-- Índices de tabela `atendidos_externos`
--
ALTER TABLE `atendidos_externos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `atendidos_externos_aluno_id_foreign` (`aluno_id`),
  ADD KEY `atendidos_externos_escola_id_tipo_vinculo_index` (`escola_id`,`tipo_vinculo`);

--
-- Índices de tabela `atendimentos_psicossociais`
--
ALTER TABLE `atendimentos_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `atendimentos_psicossociais_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `atendimentos_psicossociais_profissional_responsavel_id_foreign` (`profissional_responsavel_id`),
  ADD KEY `atendimentos_psicossociais_atendivel_type_atendivel_id_index` (`atendivel_type`,`atendivel_id`),
  ADD KEY `psicossocial_escola_status_data_idx` (`escola_id`,`status`,`data_agendada`),
  ADD KEY `psicossocial_escola_publico_idx` (`escola_id`,`tipo_publico`);

--
-- Índices de tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cardapios_diarios`
--
ALTER TABLE `cardapios_diarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cardapios_diarios_escola_id_data_cardapio_unique` (`escola_id`,`data_cardapio`),
  ADD KEY `cardapios_diarios_usuario_id_foreign` (`usuario_id`),
  ADD KEY `cardapios_diarios_escola_id_data_cardapio_index` (`escola_id`,`data_cardapio`);

--
-- Índices de tabela `cardapio_diario_itens`
--
ALTER TABLE `cardapio_diario_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cardapio_diario_itens_alimento_id_foreign` (`alimento_id`),
  ADD KEY `cardapio_diario_itens_cardapio_diario_id_refeicao_index` (`cardapio_diario_id`,`refeicao`);

--
-- Índices de tabela `casos_disciplinares_sigilosos`
--
ALTER TABLE `casos_disciplinares_sigilosos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_sig_atend_fk` (`atendimento_psicossocial_id`),
  ADD KEY `casos_disciplinares_sigilosos_aluno_id_foreign` (`aluno_id`),
  ADD KEY `casos_disciplinares_sigilosos_funcionario_id_foreign` (`funcionario_id`),
  ADD KEY `casos_disciplinares_sigilosos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `caso_sigiloso_escola_data_idx` (`escola_id`,`data_ocorrencia`);

--
-- Índices de tabela `categorias_alimentos`
--
ALTER TABLE `categorias_alimentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_alimentos_nome_unique` (`nome`);

--
-- Índices de tabela `demandas_psicossociais`
--
ALTER TABLE `demandas_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demandas_psicossociais_escola_id_foreign` (`escola_id`),
  ADD KEY `demandas_psicossociais_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `demandas_psicossociais_profissional_responsavel_id_foreign` (`profissional_responsavel_id`),
  ADD KEY `demandas_psicossociais_aluno_id_foreign` (`aluno_id`),
  ADD KEY `demandas_psicossociais_funcionario_id_foreign` (`funcionario_id`),
  ADD KEY `demandas_psicossociais_atendimento_id_foreign` (`atendimento_id`);

--
-- Índices de tabela `devolutivas_psicossociais`
--
ALTER TABLE `devolutivas_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devolutivas_psicossociais_atendimento_id_foreign` (`atendimento_id`),
  ADD KEY `devolutivas_psicossociais_usuario_responsavel_id_foreign` (`usuario_responsavel_id`);

--
-- Índices de tabela `diarios_professor`
--
ALTER TABLE `diarios_professor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `diario_professor_unico` (`turma_id`,`disciplina_id`,`professor_id`,`ano_letivo`,`periodo_tipo`,`periodo_referencia`),
  ADD KEY `diarios_professor_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `diario_professor_escola_ano_idx` (`escola_id`,`ano_letivo`),
  ADD KEY `diario_professor_prof_periodo_idx` (`professor_id`,`periodo_tipo`,`periodo_referencia`);

--
-- Índices de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `encaminhamentos_psicossociais`
--
ALTER TABLE `encaminhamentos_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `encam_psico_atend_fk` (`atendimento_psicossocial_id`),
  ADD KEY `encaminhamentos_psicossociais_usuario_id_foreign` (`usuario_id`);

--
-- Índices de tabela `escolas`
--
ALTER TABLE `escolas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices de tabela `faltas_funcionarios`
--
ALTER TABLE `faltas_funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faltas_funcionarios_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `faltas_funcionarios_escola_data_idx` (`escola_id`,`data_falta`),
  ADD KEY `faltas_funcionarios_funcionario_data_idx` (`funcionario_id`,`data_falta`);

--
-- Índices de tabela `fechamentos_letivos`
--
ALTER TABLE `fechamentos_letivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fechamentos_letivos_escola_id_ano_letivo_unique` (`escola_id`,`ano_letivo`),
  ADD KEY `fechamentos_letivos_usuario_direcao_id_foreign` (`usuario_direcao_id`),
  ADD KEY `fechamentos_letivos_ano_status_idx` (`ano_letivo`,`status`);

--
-- Índices de tabela `fornecedores_alimentos`
--
ALTER TABLE `fornecedores_alimentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fornecedores_alimentos_cnpj_unique` (`cnpj`);

--
-- Índices de tabela `frequencias_aula`
--
ALTER TABLE `frequencias_aula`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `frequencia_aula_unica` (`registro_aula_id`,`matricula_id`),
  ADD KEY `frequencia_aula_matricula_situacao_idx` (`matricula_id`,`situacao`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `funcionarios_cpf_unique` (`cpf`);

--
-- Índices de tabela `funcionario_escola`
--
ALTER TABLE `funcionario_escola`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionario_escola_funcionario_id_foreign` (`funcionario_id`),
  ADD KEY `funcionario_escola_escola_id_foreign` (`escola_id`);

--
-- Índices de tabela `horario_aulas`
--
ALTER TABLE `horario_aulas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `horario_aulas_escola_id_foreign` (`escola_id`),
  ADD KEY `horario_aulas_turma_id_foreign` (`turma_id`),
  ADD KEY `horario_aulas_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `horario_aulas_professor_id_foreign` (`professor_id`);

--
-- Índices de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices de tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `justificativas_falta_aluno`
--
ALTER TABLE `justificativas_falta_aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `justificativas_falta_aluno_frequencia_aula_id_unique` (`frequencia_aula_id`),
  ADD KEY `justificativas_falta_aluno_matricula_id_foreign` (`matricula_id`),
  ADD KEY `justificativas_falta_aluno_usuario_direcao_id_foreign` (`usuario_direcao_id`),
  ADD KEY `justificativas_falta_aluno_diario_matricula_idx` (`diario_professor_id`,`matricula_id`);

--
-- Índices de tabela `lancamentos_avaliativos`
--
ALTER TABLE `lancamentos_avaliativos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lancamentos_avaliativos_unico` (`diario_professor_id`,`matricula_id`,`avaliacao_referencia`),
  ADD KEY `lancamentos_avaliativos_matricula_id_foreign` (`matricula_id`),
  ADD KEY `lancamentos_avaliativos_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `lancamentos_avaliativos_diario_tipo_idx` (`diario_professor_id`,`tipo_avaliacao`);

--
-- Índices de tabela `liberacoes_prazo_professor`
--
ALTER TABLE `liberacoes_prazo_professor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liberacoes_prazo_professor_usuario_direcao_id_foreign` (`usuario_direcao_id`),
  ADD KEY `liberacoes_prazo_professor_diario_tipo_idx` (`diario_professor_id`,`tipo_lancamento`),
  ADD KEY `liberacoes_prazo_professor_professor_status_idx` (`professor_id`,`status`);

--
-- Índices de tabela `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matriculas_aluno_id_foreign` (`aluno_id`),
  ADD KEY `matriculas_escola_id_foreign` (`escola_id`),
  ADD KEY `matriculas_turma_id_foreign` (`turma_id`),
  ADD KEY `matriculas_matricula_regular_id_foreign` (`matricula_regular_id`);

--
-- Índices de tabela `matricula_historicos`
--
ALTER TABLE `matricula_historicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matricula_historicos_matricula_id_foreign` (`matricula_id`),
  ADD KEY `matricula_historicos_usuario_id_foreign` (`usuario_id`);

--
-- Índices de tabela `matrizes_curriculares`
--
ALTER TABLE `matrizes_curriculares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matrizes_curriculares_modalidade_id_foreign` (`modalidade_id`),
  ADD KEY `matrizes_curriculares_escola_id_foreign` (`escola_id`);

--
-- Índices de tabela `matriz_disciplina`
--
ALTER TABLE `matriz_disciplina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matriz_disciplina_matriz_id_foreign` (`matriz_id`),
  ADD KEY `matriz_disciplina_disciplina_id_foreign` (`disciplina_id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `modalidades_ensino`
--
ALTER TABLE `modalidades_ensino`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `modelo_has_perfis`
--
ALTER TABLE `modelo_has_perfis`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Índices de tabela `modelo_has_permissoes`
--
ALTER TABLE `modelo_has_permissoes`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Índices de tabela `movimentacoes_alimentos`
--
ALTER TABLE `movimentacoes_alimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movimentacoes_alimentos_alimento_id_foreign` (`alimento_id`),
  ADD KEY `movimentacoes_alimentos_fornecedor_alimento_id_foreign` (`fornecedor_alimento_id`),
  ADD KEY `movimentacoes_alimentos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `mov_alimento_escola_data_idx` (`escola_id`,`alimento_id`,`data_movimentacao`),
  ADD KEY `mov_alimento_escola_tipo_idx` (`escola_id`,`tipo`),
  ADD KEY `mov_alimento_validade_idx` (`escola_id`,`data_validade`);

--
-- Índices de tabela `observacoes_aluno`
--
ALTER TABLE `observacoes_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `observacoes_aluno_diario_professor_id_foreign` (`diario_professor_id`),
  ADD KEY `observacoes_aluno_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `observacoes_aluno_matricula_data_idx` (`matricula_id`,`data_observacao`);

--
-- Índices de tabela `ocorrencias_diario`
--
ALTER TABLE `ocorrencias_diario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ocorrencias_diario_matricula_id_foreign` (`matricula_id`),
  ADD KEY `ocorrencias_diario_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `ocorrencias_diario_data_idx` (`diario_professor_id`,`data_ocorrencia`);

--
-- Índices de tabela `parametros_rede`
--
ALTER TABLE `parametros_rede`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices de tabela `pendencias_professor`
--
ALTER TABLE `pendencias_professor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pendencias_professor_diario_professor_id_foreign` (`diario_professor_id`),
  ADD KEY `pendencias_professor_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `pendencias_professor_status_idx` (`professor_id`,`status`);

--
-- Índices de tabela `perfil_has_permissoes`
--
ALTER TABLE `perfil_has_permissoes`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `perfil_has_permissoes_role_id_foreign` (`role_id`);

--
-- Índices de tabela `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perfis_name_guard_name_unique` (`name`,`guard_name`);

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissoes_name_guard_name_unique` (`name`,`guard_name`);

--
-- Índices de tabela `planejamentos_anuais`
--
ALTER TABLE `planejamentos_anuais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `planejamentos_anuais_diario_professor_id_unique` (`diario_professor_id`);

--
-- Índices de tabela `planejamentos_periodo`
--
ALTER TABLE `planejamentos_periodo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `planejamentos_periodo_unico` (`diario_professor_id`,`tipo_planejamento`,`data_inicio`),
  ADD KEY `planejamentos_periodo_diario_tipo_idx` (`diario_professor_id`,`tipo_planejamento`);

--
-- Índices de tabela `planejamentos_semanais`
--
ALTER TABLE `planejamentos_semanais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `planejamento_semanal_unico` (`diario_professor_id`,`data_inicio_semana`);

--
-- Índices de tabela `planos_intervencao_psicossociais`
--
ALTER TABLE `planos_intervencao_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plano_interv_psico_atend_fk` (`atendimento_psicossocial_id`),
  ADD KEY `planos_intervencao_psicossociais_usuario_id_foreign` (`usuario_id`);

--
-- Índices de tabela `reavaliacoes_psicossociais`
--
ALTER TABLE `reavaliacoes_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reavaliacoes_psicossociais_atendimento_id_foreign` (`atendimento_id`),
  ADD KEY `reavaliacoes_psicossociais_usuario_responsavel_id_foreign` (`usuario_responsavel_id`);

--
-- Índices de tabela `registros_auditoria`
--
ALTER TABLE `registros_auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auditoria_modulo_acao_idx` (`modulo`,`acao`,`created_at`),
  ADD KEY `auditoria_escola_data_idx` (`escola_id`,`created_at`),
  ADD KEY `auditoria_usuario_data_idx` (`usuario_id`,`created_at`),
  ADD KEY `auditoria_sensibilidade_data_idx` (`nivel_sensibilidade`,`created_at`),
  ADD KEY `auditoria_registro_idx` (`registro_type`,`registro_id`);

--
-- Índices de tabela `registros_aula`
--
ALTER TABLE `registros_aula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_aula_horario_aula_id_foreign` (`horario_aula_id`),
  ADD KEY `registros_aula_usuario_registro_id_foreign` (`usuario_registro_id`),
  ADD KEY `registro_aulas_diario_data_idx` (`diario_professor_id`,`data_aula`);

--
-- Índices de tabela `relatorios_tecnicos_psicossociais`
--
ALTER TABLE `relatorios_tecnicos_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rel_tec_psico_atend_fk` (`atendimento_psicossocial_id`),
  ADD KEY `relatorios_tecnicos_psicossociais_usuario_emissor_id_foreign` (`usuario_emissor_id`),
  ADD KEY `relatorio_psicossocial_idx` (`escola_id`,`tipo_relatorio`,`data_emissao`);

--
-- Índices de tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices de tabela `sessoes_atendimentos`
--
ALTER TABLE `sessoes_atendimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessoes_atendimentos_atendimento_id_foreign` (`atendimento_id`),
  ADD KEY `sessoes_atendimentos_usuario_profissional_id_foreign` (`usuario_profissional_id`),
  ADD KEY `sessoes_atendimentos_funcionario_profissional_id_foreign` (`funcionario_profissional_id`);

--
-- Índices de tabela `triagens_psicossociais`
--
ALTER TABLE `triagens_psicossociais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `triagens_psicossociais_demanda_id_foreign` (`demanda_id`),
  ADD KEY `triagens_psicossociais_usuario_triador_id_foreign` (`usuario_triador_id`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turmas_escola_id_foreign` (`escola_id`),
  ADD KEY `turmas_modalidade_id_foreign` (`modalidade_id`),
  ADD KEY `turmas_matriz_id_foreign` (`matriz_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD KEY `usuarios_funcionario_id_foreign` (`funcionario_id`);

--
-- Índices de tabela `usuarios_escolas`
--
ALTER TABLE `usuarios_escolas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuarios_escolas_usuario_id_foreign` (`usuario_id`),
  ADD KEY `usuarios_escolas_escola_id_foreign` (`escola_id`);

--
-- Índices de tabela `validacoes_direcao`
--
ALTER TABLE `validacoes_direcao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `validacoes_direcao_validavel_unique` (`validavel_type`,`validavel_id`),
  ADD KEY `validacoes_direcao_validavel_type_validavel_id_index` (`validavel_type`,`validavel_id`),
  ADD KEY `validacoes_direcao_usuario_direcao_id_foreign` (`usuario_direcao_id`),
  ADD KEY `validacoes_direcao_diario_status_idx` (`diario_professor_id`,`status`);

--
-- Índices de tabela `validacoes_pedagogicas`
--
ALTER TABLE `validacoes_pedagogicas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `validacao_pedagogica_validavel_unica` (`validavel_type`,`validavel_id`),
  ADD KEY `validacoes_pedagogicas_validavel_type_validavel_id_index` (`validavel_type`,`validavel_id`),
  ADD KEY `validacoes_pedagogicas_usuario_coordenador_id_foreign` (`usuario_coordenador_id`),
  ADD KEY `validacao_pedagogica_diario_status_idx` (`diario_professor_id`,`status`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acompanhamentos_pedagogicos_aluno`
--
ALTER TABLE `acompanhamentos_pedagogicos_aluno`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `atendidos_externos`
--
ALTER TABLE `atendidos_externos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_psicossociais`
--
ALTER TABLE `atendimentos_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `cardapios_diarios`
--
ALTER TABLE `cardapios_diarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cardapio_diario_itens`
--
ALTER TABLE `cardapio_diario_itens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `casos_disciplinares_sigilosos`
--
ALTER TABLE `casos_disciplinares_sigilosos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `categorias_alimentos`
--
ALTER TABLE `categorias_alimentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `demandas_psicossociais`
--
ALTER TABLE `demandas_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `devolutivas_psicossociais`
--
ALTER TABLE `devolutivas_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `diarios_professor`
--
ALTER TABLE `diarios_professor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `encaminhamentos_psicossociais`
--
ALTER TABLE `encaminhamentos_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `escolas`
--
ALTER TABLE `escolas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `faltas_funcionarios`
--
ALTER TABLE `faltas_funcionarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fechamentos_letivos`
--
ALTER TABLE `fechamentos_letivos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores_alimentos`
--
ALTER TABLE `fornecedores_alimentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `frequencias_aula`
--
ALTER TABLE `frequencias_aula`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `funcionario_escola`
--
ALTER TABLE `funcionario_escola`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `horario_aulas`
--
ALTER TABLE `horario_aulas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `justificativas_falta_aluno`
--
ALTER TABLE `justificativas_falta_aluno`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `lancamentos_avaliativos`
--
ALTER TABLE `lancamentos_avaliativos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `liberacoes_prazo_professor`
--
ALTER TABLE `liberacoes_prazo_professor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `matricula_historicos`
--
ALTER TABLE `matricula_historicos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `matrizes_curriculares`
--
ALTER TABLE `matrizes_curriculares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `matriz_disciplina`
--
ALTER TABLE `matriz_disciplina`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `modalidades_ensino`
--
ALTER TABLE `modalidades_ensino`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `movimentacoes_alimentos`
--
ALTER TABLE `movimentacoes_alimentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `observacoes_aluno`
--
ALTER TABLE `observacoes_aluno`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ocorrencias_diario`
--
ALTER TABLE `ocorrencias_diario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `parametros_rede`
--
ALTER TABLE `parametros_rede`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pendencias_professor`
--
ALTER TABLE `pendencias_professor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT de tabela `planejamentos_anuais`
--
ALTER TABLE `planejamentos_anuais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planejamentos_periodo`
--
ALTER TABLE `planejamentos_periodo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planejamentos_semanais`
--
ALTER TABLE `planejamentos_semanais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `planos_intervencao_psicossociais`
--
ALTER TABLE `planos_intervencao_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `reavaliacoes_psicossociais`
--
ALTER TABLE `reavaliacoes_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `registros_auditoria`
--
ALTER TABLE `registros_auditoria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de tabela `registros_aula`
--
ALTER TABLE `registros_aula`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `relatorios_tecnicos_psicossociais`
--
ALTER TABLE `relatorios_tecnicos_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `sessoes_atendimentos`
--
ALTER TABLE `sessoes_atendimentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `triagens_psicossociais`
--
ALTER TABLE `triagens_psicossociais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `usuarios_escolas`
--
ALTER TABLE `usuarios_escolas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `validacoes_direcao`
--
ALTER TABLE `validacoes_direcao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `validacoes_pedagogicas`
--
ALTER TABLE `validacoes_pedagogicas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `acompanhamentos_pedagogicos_aluno`
--
ALTER TABLE `acompanhamentos_pedagogicos_aluno`
  ADD CONSTRAINT `acompanhamentos_pedagogicos_aluno_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acompanhamentos_pedagogicos_aluno_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acompanhamentos_pedagogicos_aluno_usuario_coordenador_id_foreign` FOREIGN KEY (`usuario_coordenador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `alimentos`
--
ALTER TABLE `alimentos`
  ADD CONSTRAINT `alimentos_categoria_alimento_id_foreign` FOREIGN KEY (`categoria_alimento_id`) REFERENCES `categorias_alimentos` (`id`);

--
-- Restrições para tabelas `atendidos_externos`
--
ALTER TABLE `atendidos_externos`
  ADD CONSTRAINT `atendidos_externos_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `atendidos_externos_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`);

--
-- Restrições para tabelas `atendimentos_psicossociais`
--
ALTER TABLE `atendimentos_psicossociais`
  ADD CONSTRAINT `atendimentos_psicossociais_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `atendimentos_psicossociais_profissional_responsavel_id_foreign` FOREIGN KEY (`profissional_responsavel_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `atendimentos_psicossociais_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `cardapios_diarios`
--
ALTER TABLE `cardapios_diarios`
  ADD CONSTRAINT `cardapios_diarios_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `cardapios_diarios_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `cardapio_diario_itens`
--
ALTER TABLE `cardapio_diario_itens`
  ADD CONSTRAINT `cardapio_diario_itens_alimento_id_foreign` FOREIGN KEY (`alimento_id`) REFERENCES `alimentos` (`id`),
  ADD CONSTRAINT `cardapio_diario_itens_cardapio_diario_id_foreign` FOREIGN KEY (`cardapio_diario_id`) REFERENCES `cardapios_diarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `casos_disciplinares_sigilosos`
--
ALTER TABLE `casos_disciplinares_sigilosos`
  ADD CONSTRAINT `caso_sig_atend_fk` FOREIGN KEY (`atendimento_psicossocial_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `casos_disciplinares_sigilosos_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `casos_disciplinares_sigilosos_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `casos_disciplinares_sigilosos_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `casos_disciplinares_sigilosos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `demandas_psicossociais`
--
ALTER TABLE `demandas_psicossociais`
  ADD CONSTRAINT `demandas_psicossociais_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`),
  ADD CONSTRAINT `demandas_psicossociais_atendimento_id_foreign` FOREIGN KEY (`atendimento_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `demandas_psicossociais_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `demandas_psicossociais_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`),
  ADD CONSTRAINT `demandas_psicossociais_profissional_responsavel_id_foreign` FOREIGN KEY (`profissional_responsavel_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `demandas_psicossociais_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `devolutivas_psicossociais`
--
ALTER TABLE `devolutivas_psicossociais`
  ADD CONSTRAINT `devolutivas_psicossociais_atendimento_id_foreign` FOREIGN KEY (`atendimento_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devolutivas_psicossociais_usuario_responsavel_id_foreign` FOREIGN KEY (`usuario_responsavel_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `diarios_professor`
--
ALTER TABLE `diarios_professor`
  ADD CONSTRAINT `diarios_professor_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diarios_professor_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diarios_professor_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `funcionarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diarios_professor_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `encaminhamentos_psicossociais`
--
ALTER TABLE `encaminhamentos_psicossociais`
  ADD CONSTRAINT `encam_psico_atend_fk` FOREIGN KEY (`atendimento_psicossocial_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `encaminhamentos_psicossociais_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `faltas_funcionarios`
--
ALTER TABLE `faltas_funcionarios`
  ADD CONSTRAINT `faltas_funcionarios_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faltas_funcionarios_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faltas_funcionarios_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `fechamentos_letivos`
--
ALTER TABLE `fechamentos_letivos`
  ADD CONSTRAINT `fechamentos_letivos_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fechamentos_letivos_usuario_direcao_id_foreign` FOREIGN KEY (`usuario_direcao_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `frequencias_aula`
--
ALTER TABLE `frequencias_aula`
  ADD CONSTRAINT `frequencias_aula_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frequencias_aula_registro_aula_id_foreign` FOREIGN KEY (`registro_aula_id`) REFERENCES `registros_aula` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `funcionario_escola`
--
ALTER TABLE `funcionario_escola`
  ADD CONSTRAINT `funcionario_escola_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `funcionario_escola_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `horario_aulas`
--
ALTER TABLE `horario_aulas`
  ADD CONSTRAINT `horario_aulas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `horario_aulas_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `horario_aulas_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `horario_aulas_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `justificativas_falta_aluno`
--
ALTER TABLE `justificativas_falta_aluno`
  ADD CONSTRAINT `justificativas_falta_aluno_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `justificativas_falta_aluno_frequencia_aula_id_foreign` FOREIGN KEY (`frequencia_aula_id`) REFERENCES `frequencias_aula` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `justificativas_falta_aluno_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `justificativas_falta_aluno_usuario_direcao_id_foreign` FOREIGN KEY (`usuario_direcao_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `lancamentos_avaliativos`
--
ALTER TABLE `lancamentos_avaliativos`
  ADD CONSTRAINT `lancamentos_avaliativos_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lancamentos_avaliativos_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lancamentos_avaliativos_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `liberacoes_prazo_professor`
--
ALTER TABLE `liberacoes_prazo_professor`
  ADD CONSTRAINT `liberacoes_prazo_professor_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liberacoes_prazo_professor_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `funcionarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liberacoes_prazo_professor_usuario_direcao_id_foreign` FOREIGN KEY (`usuario_direcao_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`),
  ADD CONSTRAINT `matriculas_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `matriculas_matricula_regular_id_foreign` FOREIGN KEY (`matricula_regular_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriculas_turma_id_foreign` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`);

--
-- Restrições para tabelas `matricula_historicos`
--
ALTER TABLE `matricula_historicos`
  ADD CONSTRAINT `matricula_historicos_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matricula_historicos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `matrizes_curriculares`
--
ALTER TABLE `matrizes_curriculares`
  ADD CONSTRAINT `matrizes_curriculares_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matrizes_curriculares_modalidade_id_foreign` FOREIGN KEY (`modalidade_id`) REFERENCES `modalidades_ensino` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `matriz_disciplina`
--
ALTER TABLE `matriz_disciplina`
  ADD CONSTRAINT `matriz_disciplina_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriz_disciplina_matriz_id_foreign` FOREIGN KEY (`matriz_id`) REFERENCES `matrizes_curriculares` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `modelo_has_perfis`
--
ALTER TABLE `modelo_has_perfis`
  ADD CONSTRAINT `modelo_has_perfis_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `perfis` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `modelo_has_permissoes`
--
ALTER TABLE `modelo_has_permissoes`
  ADD CONSTRAINT `modelo_has_permissoes_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `movimentacoes_alimentos`
--
ALTER TABLE `movimentacoes_alimentos`
  ADD CONSTRAINT `movimentacoes_alimentos_alimento_id_foreign` FOREIGN KEY (`alimento_id`) REFERENCES `alimentos` (`id`),
  ADD CONSTRAINT `movimentacoes_alimentos_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `movimentacoes_alimentos_fornecedor_alimento_id_foreign` FOREIGN KEY (`fornecedor_alimento_id`) REFERENCES `fornecedores_alimentos` (`id`),
  ADD CONSTRAINT `movimentacoes_alimentos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `observacoes_aluno`
--
ALTER TABLE `observacoes_aluno`
  ADD CONSTRAINT `observacoes_aluno_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `observacoes_aluno_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `observacoes_aluno_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `ocorrencias_diario`
--
ALTER TABLE `ocorrencias_diario`
  ADD CONSTRAINT `ocorrencias_diario_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ocorrencias_diario_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ocorrencias_diario_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `pendencias_professor`
--
ALTER TABLE `pendencias_professor`
  ADD CONSTRAINT `pendencias_professor_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pendencias_professor_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `funcionarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendencias_professor_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `perfil_has_permissoes`
--
ALTER TABLE `perfil_has_permissoes`
  ADD CONSTRAINT `perfil_has_permissoes_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfil_has_permissoes_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `perfis` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `planejamentos_anuais`
--
ALTER TABLE `planejamentos_anuais`
  ADD CONSTRAINT `planejamentos_anuais_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `planejamentos_periodo`
--
ALTER TABLE `planejamentos_periodo`
  ADD CONSTRAINT `planejamentos_periodo_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `planejamentos_semanais`
--
ALTER TABLE `planejamentos_semanais`
  ADD CONSTRAINT `planejamentos_semanais_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `planos_intervencao_psicossociais`
--
ALTER TABLE `planos_intervencao_psicossociais`
  ADD CONSTRAINT `plano_interv_psico_atend_fk` FOREIGN KEY (`atendimento_psicossocial_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `planos_intervencao_psicossociais_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `reavaliacoes_psicossociais`
--
ALTER TABLE `reavaliacoes_psicossociais`
  ADD CONSTRAINT `reavaliacoes_psicossociais_atendimento_id_foreign` FOREIGN KEY (`atendimento_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reavaliacoes_psicossociais_usuario_responsavel_id_foreign` FOREIGN KEY (`usuario_responsavel_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `registros_auditoria`
--
ALTER TABLE `registros_auditoria`
  ADD CONSTRAINT `registros_auditoria_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registros_auditoria_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `registros_aula`
--
ALTER TABLE `registros_aula`
  ADD CONSTRAINT `registros_aula_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registros_aula_horario_aula_id_foreign` FOREIGN KEY (`horario_aula_id`) REFERENCES `horario_aulas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registros_aula_usuario_registro_id_foreign` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `relatorios_tecnicos_psicossociais`
--
ALTER TABLE `relatorios_tecnicos_psicossociais`
  ADD CONSTRAINT `rel_tec_psico_atend_fk` FOREIGN KEY (`atendimento_psicossocial_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `relatorios_tecnicos_psicossociais_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `relatorios_tecnicos_psicossociais_usuario_emissor_id_foreign` FOREIGN KEY (`usuario_emissor_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `sessoes_atendimentos`
--
ALTER TABLE `sessoes_atendimentos`
  ADD CONSTRAINT `sessoes_atendimentos_atendimento_id_foreign` FOREIGN KEY (`atendimento_id`) REFERENCES `atendimentos_psicossociais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessoes_atendimentos_funcionario_profissional_id_foreign` FOREIGN KEY (`funcionario_profissional_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sessoes_atendimentos_usuario_profissional_id_foreign` FOREIGN KEY (`usuario_profissional_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `triagens_psicossociais`
--
ALTER TABLE `triagens_psicossociais`
  ADD CONSTRAINT `triagens_psicossociais_demanda_id_foreign` FOREIGN KEY (`demanda_id`) REFERENCES `demandas_psicossociais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `triagens_psicossociais_usuario_triador_id_foreign` FOREIGN KEY (`usuario_triador_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`),
  ADD CONSTRAINT `turmas_matriz_id_foreign` FOREIGN KEY (`matriz_id`) REFERENCES `matrizes_curriculares` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `turmas_modalidade_id_foreign` FOREIGN KEY (`modalidade_id`) REFERENCES `modalidades_ensino` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `usuarios_escolas`
--
ALTER TABLE `usuarios_escolas`
  ADD CONSTRAINT `usuarios_escolas_escola_id_foreign` FOREIGN KEY (`escola_id`) REFERENCES `escolas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_escolas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `validacoes_direcao`
--
ALTER TABLE `validacoes_direcao`
  ADD CONSTRAINT `validacoes_direcao_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `validacoes_direcao_usuario_direcao_id_foreign` FOREIGN KEY (`usuario_direcao_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `validacoes_pedagogicas`
--
ALTER TABLE `validacoes_pedagogicas`
  ADD CONSTRAINT `validacoes_pedagogicas_diario_professor_id_foreign` FOREIGN KEY (`diario_professor_id`) REFERENCES `diarios_professor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `validacoes_pedagogicas_usuario_coordenador_id_foreign` FOREIGN KEY (`usuario_coordenador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
