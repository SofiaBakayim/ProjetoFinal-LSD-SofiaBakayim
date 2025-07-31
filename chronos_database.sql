-- =====================================================
-- Sistema Chronos - Base de Dados
-- Exportado em: 2025-07-31 23:04:26
-- =====================================================

-- Criar base de dados
CREATE DATABASE IF NOT EXISTS `chronos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `chronos`;

-- Estrutura da tabela `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dados da tabela `users`
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES ('1', 'Utilizador Teste', 'teste@chronos.com', '$2y$12$6rOE3iUrwSDV4hUiHdLbJeeld5FyCw1PqipH.1YwuGq8q9sb6NJr6', '2025-08-01 00:01:37');

-- Estrutura da tabela `profiles`
CREATE TABLE `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `notifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notifications`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dados da tabela `profiles`
INSERT INTO `profiles` (`id`, `user_id`, `name`, `bio`, `location`, `specialty`, `experience`, `notifications`, `updated_at`) VALUES ('1', '1', 'Sara Correia', 'Apaixonado por cerâmica ibérica e peças de mercado vintage.', 'Lisboa, Portugal', 'ceramica', 'intermedio', '[\"novos_objetos\",\"atualizacoes_valor\"]', '2025-08-01 00:01:37');

-- Estrutura da tabela `favorites`
CREATE TABLE `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estrutura da tabela `identifications`
CREATE TABLE `identifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `identifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

