# Configuração Gmail SMTP - Chronos

## 🎯 Para receber emails reais, segue estes passos:

### 1. Ativar App Passwords no Gmail
1. Vai para https://myaccount.google.com/
2. Clica em "Segurança"
3. Ativa "Verificação em 2 passos" se não estiver ativa
4. Vai para "Palavras-passe de apps"
5. Clica "Criar nova palavra-passe de app"
6. Seleciona "Outro (nome personalizado)"
7. Escreve "Chronos Website"
8. Clica "Gerar"
9. **Guarda a palavra-passe de 16 caracteres**

### 2. Configurar o arquivo PHP
1. Abre o arquivo `contact_gmail.php`
2. Substitui na linha 25:
   ```php
   $smtp_username = 'seu-email@gmail.com'; // SUBSTITUIR PELO TEU EMAIL GMAIL
   ```
   Por:
   ```php
   $smtp_username = 'Sofia.bakayim@gmail.com';
   ```

3. Substitui na linha 26:
   ```php
   $smtp_password = 'sua-app-password'; // SUBSTITUIR PELA TUA APP PASSWORD
   ```
   Por:
   ```php
   $smtp_password = 'xxxx xxxx xxxx xxxx'; // A palavra-passe de 16 caracteres
   ```

### 3. Atualizar o formulário
1. Abre o arquivo `help.html`
2. Substitui a função JavaScript para usar o PHP:
   ```javascript
   // Enviar dados via AJAX
   fetch('contact_gmail.php', {
       method: 'POST',
       body: formData
   })
   ```

### 4. Testar
1. Vai à página Help
2. Preenche o formulário
3. Clica "Contacta-nos"
4. Verifica o email Sofia.bakayim@gmail.com

## 🔧 Alternativa mais simples:

Se não quiseres configurar o Gmail, podes usar um serviço como:
- **Formspree** (gratuito)
- **Netlify Forms** (gratuito)
- **EmailJS** (JavaScript)

## 📧 Para verificar mensagens atuais:
- Vai para `view_messages.html` para ver as mensagens guardadas no browser
- Ou verifica o arquivo `contact_messages.txt` na pasta do projeto

## ⚠️ Nota importante:
O XAMPP local não tem servidor SMTP configurado, por isso precisas de usar um serviço externo como o Gmail para enviar emails reais.

Queres que eu ajude a configurar o Gmail ou preferes usar um serviço mais simples? 🤔 