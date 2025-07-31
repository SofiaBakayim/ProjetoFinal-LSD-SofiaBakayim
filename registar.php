<?php
require_once 'config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    if (!$name || !$email || !$password || !$confirmPassword) {
        $error = 'Um dos dados abaixo não está correto, tente novamente.';
    } elseif ($password !== $confirmPassword) {
        $error = 'As passwords não coincidem!';
    } elseif (strlen($password) < 6) {
        $error = 'A password deve ter pelo menos 6 caracteres!';
    } elseif (get_user_by_email($email)) {
        $error = 'Este email já está registado!';
    } else {
        if (save_user($name, $email, $password)) {
            $success = true;
        } else {
            $error = 'Erro ao registar utilizador. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Chronos - Registar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="icon" type="image/png" href="assets/images/logooos.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logooos.png">
    <link rel="apple-touch-icon" href="assets/images/logooos.png">
    <link href="https://fonts.googleapis.com/css2?family=La+Belle+Aurore&family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
.chro-btns-vertical-fixed {
    position: fixed;
    left: 24px;
    top: 400px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 18px;
    z-index: 9999;
    margin-bottom: 50px;
}
.chro-btn {
    width: 160px;
    height: 60px;
    border-radius: 40px;
    background: rgba(99,1,2,0.7);
    color: #FAF3E3;
    font-family: 'Lato', Arial, sans-serif;
    font-size: 16px;
    letter-spacing: 0.12em;
    border: none;
    box-shadow: 0 4px 16px rgba(99,1,2,0.18);
    transition: background 0.2s, transform 0.2s;
    margin: 0;
    padding: 0;
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}
.chro-btn:hover {
    background: #2d0101;
    color: #FFD97D;
    transform: scale(1.04);
}
.login-container {
    background: rgba(255,255,255,0.25);
    border-radius: 48px;
    box-shadow: 0 8px 48px 0 rgba(99,1,2,0.10);
    width: 90%;
    max-width: 500px;
    padding: 50px 40px 40px 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-sizing: border-box;
    backdrop-filter: blur(32px);
    -webkit-backdrop-filter: blur(32px);
    position: relative;
    z-index: 1;
    border: none;
}
@media (max-width: 900px) {
    .chro-btns-vertical-fixed {
        position: static;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        margin: 32px auto 50px auto;
        left: unset;
        bottom: unset;
        z-index: 1;
        width: 100%;
        max-width: 400px;
    }
    
    .chro-btn {
        width: 120px;
        height: 48px;
        font-size: 0.95rem;
    }
    
    .main-content {
        margin-top: 320px;
    }
}
.main-content {
    flex: 1 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 64px;
    position: relative;
    padding-bottom: 60px;
    min-height: calc(100vh - 160px);
}
.usp-footnote {
    position: relative;
    left: 0;
    bottom: 0;
    width: 100vw;
    color: #630102;
    font-family: 'Lato', Arial, sans-serif;
    font-size: 0.95rem;
    text-align: center;
    padding: 12px 0 10px 0;
    border-top: 1px solid #855A25;
    z-index: 100;
}
.login-btn {
    background: #131313;
    color: #FAF3E3;
    font-family: 'Lato', Arial, sans-serif;
    margin-bottom: 8px;
}
.google-btn {
    background: #fff;
    color: #0F0F0F;
    border: 1.5px solid #4D4D4D;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 0;
}
</style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="assets/images/logo-chronos.png" alt="Logo Chronos" class="logo-img">
        </div>
    </header>
    <main class="main-content">
        <div class="login-container">
            <h2 class="login-title">RegisteCHRO</h2>
            <div class="login-subtitle">Vem te tornar num CHRONOS</div>
            <?php if ($error): ?>
                <div class="login-subtitle" style="color:#630102; margin-bottom:18px;"> <?= htmlspecialchars($error) ?> </div>
            <?php elseif ($success): ?>
                <div class="login-subtitle" style="color:#2e7d32; margin-bottom:18px;">Registo realizado com sucesso! <a href='login.html'>Ir para login</a></div>
            <?php endif; ?>
            <form class="login-form" id="registerForm" autocomplete="off" action="registar.php" method="POST">
                <div class="form-group">
                    <label for="name">Name*</label>
                    <input type="text" id="name" name="name" placeholder="Name*" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" placeholder="Email*" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password*</label>
                    <input type="password" id="password" name="password" placeholder="Password*" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Password*</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Password*" required>
                </div>
                <button type="submit" class="login-btn">Entrar</button>
                <button type="button" class="google-btn"><i class="bi bi-google"></i> Entrar com Google</button>
            </form>
            <div class="login-footer">
                Already have an account? <a href="login.html">Log In</a>
            </div>
        </div>
    </main>
    
    <div class="chro-btns-vertical-fixed">
        <button class="chro-btn" onclick="window.location.href='help.html'">Help</button>
        <button class="chro-btn" onclick="window.location.href='index.html'">Back</button>
        <button class="chro-btn" onclick="window.location.href='sobre.html'">Sobre</button>
    </div>
    
    <p class="usp-footnote">© 2025 CHRONOS</p>
    <script>
    function helpChro() {
        window.location.href = 'help.html';
    }
    function backChro() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = 'index.html';
        }
    }
    function exploreChro() {
        window.location.href = 'intro.html';
    }
    </script>
    <script>
function renderMobileChroBtns() {
    if(window.innerWidth <= 900) {
        document.getElementById('chro-btns-mobile').innerHTML = `
            <button class='btn chro-btn' onclick="window.location.href='help.html'">Help Chro</button>
            <button class='btn chro-btn' onclick='backChro()'>Back Chro</button>
            <button class='btn chro-btn' onclick='exploreChro()'>Explore Chro</button>
        `;
    } else {
        document.getElementById('chro-btns-mobile').innerHTML = '';
    }
}
window.addEventListener('resize', renderMobileChroBtns);
document.addEventListener('DOMContentLoaded', renderMobileChroBtns);
</script>
</body>
</html> 