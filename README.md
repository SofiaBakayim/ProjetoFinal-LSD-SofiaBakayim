# Chronos - Sistema de Identificação de Relíquias

## Descrição do Projeto

O Chronos é uma plataforma web desenvolvida em PHP que permite aos utilizadores identificar e catalogar relíquias históricas e objetos antigos. O sistema oferece funcionalidades de autenticação, gestão de perfis, favoritos e identificações.

## Funcionalidades Principais

### 🔐 Sistema de Autenticação
- **Registo de utilizadores**: Criação de contas com validação de dados
- **Login seguro**: Autenticação com hash de passwords
- **Gestão de sessões**: Sistema de sessões PHP
- **Logout**: Encerramento seguro de sessões

### 👤 Gestão de Perfis
- **Perfil personalizável**: Nome, bio, localização
- **Especialidades**: Categorias de interesse (cerâmica, vintage, etc.)
- **Níveis de experiência**: Iniciante, Intermediário, Avançado
- **Notificações**: Configuração de alertas personalizados

### ❤️ Sistema de Favoritos
- **Guardar itens**: Adicionar objetos aos favoritos
- **Gestão de favoritos**: Visualizar e remover favoritos
- **Categorização**: Organização por categorias

### 🔍 Identificações
- **Registo de identificações**: Documentar objetos identificados
- **Histórico**: Visualizar todas as identificações realizadas
- **Estatísticas**: Contadores de atividade

### 📱 Design Responsivo
- **Mobile-first**: Otimizado para dispositivos móveis
- **Interface moderna**: Design clean e intuitivo
- **Acessibilidade**: Navegação por teclado e leitores de ecrã

## Estrutura de Ficheiros

```
Projeto Final/
├── config.php              # Configurações e funções principais
├── index.php               # Página inicial (redirecionamento)
├── login.php               # Sistema de login
├── registar.php            # Sistema de registo
├── perfil.php              # Gestão de perfil (PHP)
├── logout.php              # Encerramento de sessão
├── favorites.php           # API para favoritos
├── identifications.php     # API para identificações
├── contact.php             # Sistema de contacto
├── contact_handler.php     # Processamento de contactos
├── contact_smtp.php        # Configuração SMTP
├── contact_gmail.php       # Configuração Gmail
├── contact_alternative.php # Método alternativo de contacto
├── test_contact.php        # Teste de contacto
├── view_messages.html      # Visualização de mensagens
├── routers.php             # Sistema de rotas
├── SiteController.php      # Controlador principal
├── users.txt               # Base de dados de utilizadores
├── profiles.txt            # Base de dados de perfis
├── favorites.txt           # Base de dados de favoritos
├── identifications.txt     # Base de dados de identificações
├── .htaccess              # Configurações Apache
├── start.sh               # Script de inicialização
├── README.md              # Documentação do projeto
├── EMAIL_SETUP.md         # Configuração de email
├── GMAIL_SETUP.md         # Configuração Gmail
├── assets/                # Recursos estáticos
│   ├── images/            # Imagens
│   ├── fonts/             # Fontes
│   └── videos/            # Vídeos
├── js/                    # JavaScript
└── *.html                 # Páginas HTML estáticas
```

## Tecnologias Utilizadas

### Backend
- **PHP 7.4+**: Linguagem principal
- **Sessions**: Gestão de sessões
- **File I/O**: Sistema de ficheiros para base de dados
- **JSON**: Formato de dados

### Frontend
- **HTML5**: Estrutura semântica
- **CSS3**: Estilos e animações
- **JavaScript**: Interatividade
- **Bootstrap Icons**: Ícones
- **Google Fonts**: Tipografia

### Design
- **Responsive Design**: Mobile-first
- **CSS Grid/Flexbox**: Layout moderno
- **CSS Variables**: Sistema de cores
- **Animations**: Transições suaves

## Instalação e Configuração

### Pré-requisitos
- PHP 7.4 ou superior
- Servidor web (Apache/Nginx)
- Extensões PHP: session, json, fileinfo

### Instalação
1. **Clonar o projeto**:
   ```bash
   git clone [url-do-repositorio]
   cd "Projeto Final"
   ```

2. **Configurar servidor web**:
   - Colocar ficheiros no diretório do servidor web
   - Configurar virtual host se necessário

3. **Configurar permissões**:
   ```bash
   chmod 755 .
   chmod 644 *.php *.html *.css *.js
   chmod 666 *.txt
   ```

4. **Configurar email** (opcional):
   - Editar `config.php` com credenciais SMTP
   - Configurar `contact_smtp.php` ou `contact_gmail.php`

### Configuração de Email
1. **SMTP**: Editar `contact_smtp.php`
2. **Gmail**: Seguir instruções em `GMAIL_SETUP.md`
3. **Teste**: Usar `test_contact.php`

## Utilização

### 1. Registo de Utilizador
- Aceder a `registar.html`
- Preencher dados pessoais
- Criar conta com password segura

### 2. Login
- Aceder a `login.php`
- Inserir email e password
- Ser redirecionado para `home.html`

### 3. Gestão de Perfil
- Aceder a `perfil.php`
- Editar informações pessoais
- Configurar notificações
- Visualizar estatísticas

### 4. Sistema de Favoritos
- Adicionar objetos aos favoritos
- Gestão via `favorites.php`
- Visualização no perfil

### 5. Identificações
- Registar identificações via `identifications.php`
- Visualizar histórico no perfil
- Estatísticas de atividade

## Segurança

### Medidas Implementadas
- **Sanitização de inputs**: Função `sanitize_input()`
- **Hash de passwords**: `password_hash()` e `password_verify()`
- **Validação de sessões**: Verificação de autenticação
- **Proteção CSRF**: Tokens em formulários
- **Escape de output**: `htmlspecialchars()`

### Boas Práticas
- Passwords com mínimo 6 caracteres
- Validação de email
- Verificação de duplicados
- Logs de atividade

## API Endpoints

### Favoritos (`favorites.php`)
- `GET`: Listar favoritos do utilizador
- `POST action=add`: Adicionar favorito
- `POST action=remove`: Remover favorito

### Identificações (`identifications.php`)
- `GET`: Listar identificações do utilizador
- `POST action=add`: Adicionar identificação

### Autenticação
- `login.php`: Processar login
- `registar.php`: Processar registo
- `logout.php`: Encerrar sessão

## Base de Dados

### Estrutura de Ficheiros
- **users.txt**: `nome|email|password_hash`
- **profiles.txt**: `email|json_profile_data`
- **favorites.txt**: `email|json_favorite_data`
- **identifications.txt**: `email|json_identification_data`

### Formato JSON
```json
{
  "name": "Nome do Utilizador",
  "bio": "Descrição pessoal",
  "location": "Localização",
  "specialty": "especialidade",
  "experience": "nivel",
  "notifications": ["tipo1", "tipo2"]
}
```

## Personalização

### Cores e Temas
- Variáveis CSS em `style.css`
- Cores principais: `#630102`, `#FFD97D`, `#FAF3E3`
- Fonte principal: Lato

### Funcionalidades
- Adicionar novas categorias em `config.php`
- Modificar validações em `registar.php`
- Expandir perfil em `perfil.php`

## Troubleshooting

### Problemas Comuns
1. **Erro de sessão**: Verificar `session_start()`
2. **Permissões**: Configurar chmod corretamente
3. **Email não funciona**: Verificar configuração SMTP
4. **Ficheiros não salvam**: Verificar permissões de escrita

### Logs
- Debug de login: `debug_login.txt`
- Logs de erro: Verificar error_log do servidor

## Contribuição

### Desenvolvimento
1. Fork do projeto
2. Criar branch para feature
3. Implementar funcionalidade
4. Testar localmente
5. Submeter pull request

### Padrões de Código
- PSR-4 para autoloading
- PSR-12 para estilo de código
- Comentários em português
- Nomes de variáveis descritivos

## Licença

Este projeto foi desenvolvido para fins educacionais. Todos os direitos reservados.

## Contacto

Para questões ou suporte:
- Email: [email]
- Documentação: Ver ficheiros README
- Issues: [url-do-repositorio]/issues

---

**Desenvolvido com ❤️ para o projeto final de programação web** 