# Configuração do Sistema de Email - Chronos

## Problema Identificado
O XAMPP local não tem um servidor SMTP configurado, por isso os emails não são enviados.

## Soluções Implementadas

### 1. Backup Local (Funciona Agora!)
- As mensagens são salvas no arquivo `contact_messages.txt`
- Podes verificar as mensagens a qualquer momento
- O formulário mostra "Mensagem recebida!" mesmo se o email falhar

### 2. Para Configurar Email Real

#### Opção A: Configurar XAMPP SMTP
1. Abrir XAMPP Control Panel
2. Clicar em "Config" do Apache
3. Selecionar "php.ini"
4. Procurar por `[mail function]`
5. Configurar:
   ```
   SMTP = smtp.gmail.com
   smtp_port = 587
   sendmail_from = teu-email@gmail.com
   ```

#### Opção B: Usar Serviço Externo
1. Criar conta no SendGrid, Mailgun ou similar
2. Substituir a função `mail()` por uma biblioteca como PHPMailer
3. Configurar com as credenciais do serviço

#### Opção C: Usar Gmail SMTP (Mais Fácil)
1. Ativar "App Passwords" no Google Account
2. Instalar PHPMailer: `composer require phpmailer/phpmailer`
3. Usar as credenciais do Gmail

## Verificar Mensagens
Para ver as mensagens recebidas:
```bash
cat contact_messages.txt
```

## Teste Atual
1. Vai à página Help
2. Preenche o formulário
3. Clica "Contacta-nos"
4. Verifica o arquivo `contact_messages.txt` na pasta do projeto

A mensagem será salva localmente mesmo se o email falhar! 📧✅ 