# 🕰️ Sistema Chronos - Identificação de Objetos Antigos

## 📋 Descrição do Projeto

O **Chronos** é um sistema web desenvolvido em PHP para identificação e gestão de objetos antigos. Permite aos utilizadores registar-se, criar perfis personalizados, guardar favoritos e fazer identificações de objetos históricos.

## 🚀 Funcionalidades Principais

### ✅ Sistema de Autenticação
- Registo de utilizadores
- Login/Logout seguro
- Gestão de sessões
- Encriptação de passwords

### ✅ Gestão de Perfis
- Edição de perfil pessoal
- Upload de imagem de perfil
- Configuração de notificações
- Especialidade e experiência

### ✅ Sistema de Favoritos
- Adicionar/remover objetos favoritos
- Listagem de favoritos
- API REST para gestão
- Persistência em base de dados

### ✅ Identificações
- Registo de identificações
- Categorização de objetos
- Histórico de identificações
- API para gestão

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP 8.1+
- **Base de Dados:** MySQL 8.0+
- **Frontend:** HTML5, CSS3, JavaScript
- **Servidor:** XAMPP (Apache + MySQL)
- **Design:** Responsivo com Bootstrap Icons
- **Fonte:** Lato (Google Fonts)

## 📁 Estrutura do Projeto

```
Projeto Final/
├── assets/
│   ├── fonts/
│   ├── images/
│   └── videos/
├── js/
├── config.php (Configuração da base de dados)
├── login.php (Sistema de autenticação)
├── registar.php (Registo de utilizadores)
├── perfil.php (Gestão de perfis)
├── favorites.php (API de favoritos)
├── identifications.php (API de identificações)
├── setup_with_password.php (Configuração da base de dados)
├── export_database.php (Exportar para SQL)
├── chronos_database.sql (Base de dados exportada)
├── info_professor.html (Informações para o professor)
├── README.md (Documentação)
└── [outros ficheiros HTML/CSS]
```

## 🗄️ Estrutura da Base de Dados

### Tabela: `users`
- `id` - INT AUTO_INCREMENT PRIMARY KEY
- `name` - VARCHAR(255) NOT NULL
- `email` - VARCHAR(255) UNIQUE NOT NULL
- `password` - VARCHAR(255) NOT NULL
- `created_at` - TIMESTAMP DEFAULT CURRENT_TIMESTAMP

### Tabela: `profiles`
- `id` - INT AUTO_INCREMENT PRIMARY KEY
- `user_id` - INT NOT NULL (FOREIGN KEY)
- `name` - VARCHAR(255) NOT NULL
- `bio` - TEXT
- `location` - VARCHAR(255)
- `specialty` - VARCHAR(100)
- `experience` - VARCHAR(50)
- `notifications` - JSON
- `updated_at` - TIMESTAMP

### Tabela: `favorites`
- `id` - INT AUTO_INCREMENT PRIMARY KEY
- `user_id` - INT NOT NULL (FOREIGN KEY)
- `title` - VARCHAR(255) NOT NULL
- `image` - VARCHAR(500)
- `category` - VARCHAR(100)
- `created_at` - TIMESTAMP DEFAULT CURRENT_TIMESTAMP

### Tabela: `identifications`
- `id` - INT AUTO_INCREMENT PRIMARY KEY
- `user_id` - INT NOT NULL (FOREIGN KEY)
- `title` - VARCHAR(255) NOT NULL
- `category` - VARCHAR(100)
- `material` - VARCHAR(255)
- `origin` - VARCHAR(255)
- `created_at` - TIMESTAMP DEFAULT CURRENT_TIMESTAMP

## 🔧 Instalação

### Pré-requisitos
- XAMPP (Apache + MySQL)
- PHP 8.1+
- MySQL 8.0+

### Passos de Instalação

1. **Clone o repositório:**
   ```bash
   git clone [URL_DO_REPOSITORIO]
   cd Projeto-Final
   ```

2. **Configure o XAMPP:**
   - Inicie o Apache e MySQL
   - Certifique-se de que o MySQL está a funcionar

3. **Configure a base de dados:**
   - Aceda a: `http://localhost/Projeto%20Final/setup_with_password.php`
   - Ou execute o arquivo SQL: `chronos_database.sql`

4. **Configure as credenciais:**
   - Edite `config.php` com as suas credenciais MySQL
   - Password padrão: `123456789Abc`

5. **Teste o sistema:**
   - Aceda a: `http://localhost/Projeto%20Final/login.php`
   - Credenciais de teste: `teste@chronos.com` / `123456`

## 🔐 Credenciais de Teste

- **Email:** teste@chronos.com
- **Password:** 123456
- **Perfil:** Sara Correia - Apaixonado por cerâmica ibérica

## 📊 Estatísticas do Sistema

- **4 tabelas** criadas na base de dados
- **1 utilizador** de teste criado
- **2 APIs REST** implementadas
- **Design responsivo** para todos os dispositivos
- **Sistema de sessões** seguro

## 🔗 Links Importantes

- **phpMyAdmin:** http://localhost/phpmyadmin
- **Login:** http://localhost/Projeto%20Final/login.php
- **Perfil:** http://localhost/Projeto%20Final/perfil.php
- **Informações para Professor:** http://localhost/Projeto%20Final/info_professor.html

## 📝 Notas para o Professor

### ✅ Objetivos Alcançados
- Sistema de autenticação completo
- Gestão de perfis de utilizador
- Sistema de favoritos funcional
- API REST para gestão de dados
- Base de dados MySQL estruturada
- Interface responsiva e moderna
- Documentação completa
- Código limpo e bem estruturado

### 🔧 Configuração Técnica
- **Servidor:** XAMPP (Apache + MySQL)
- **Base de Dados:** MySQL (chronos)
- **PHP:** PDO para conexões seguras
- **Frontend:** HTML5, CSS3, JavaScript
- **Design:** Responsivo com Bootstrap Icons

### 📋 Para Testar
1. Execute o arquivo `setup_with_password.php`
2. Aceda ao phpMyAdmin para verificar as tabelas
3. Teste o login com as credenciais fornecidas
4. Explore todas as funcionalidades do sistema

## 👨‍💻 Desenvolvido por

**Aluno:** [Seu Nome]  
**Data:** Julho 2024  
**Disciplina:** Programação Web  
**Professor:** [Nome do Professor]

## 📄 Licença

Este projeto foi desenvolvido para fins educacionais.

---

**🎯 Sistema totalmente funcional e pronto para demonstração!** 