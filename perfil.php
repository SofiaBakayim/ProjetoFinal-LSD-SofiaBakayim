<?php
require_once 'config.php';

// Verificar se o usuário está logado
if (!is_logged_in()) {
    redirect('login.php');
}

$user_email = get_current_user_email();
$user = get_user_by_email($user_email);
$profile = get_profile($user_email);
$favorites = get_favorites($user_email);
$identifications = get_identifications($user_email);

// Processar atualização do perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $profile_data = [
            'name' => sanitize_input($_POST['name'] ?? ''),
            'bio' => sanitize_input($_POST['bio'] ?? ''),
            'location' => sanitize_input($_POST['location'] ?? ''),
            'specialty' => sanitize_input($_POST['specialty'] ?? ''),
            'experience' => sanitize_input($_POST['experience'] ?? ''),
            'notifications' => $_POST['notifications'] ?? []
        ];
        
        // Atualizar o arquivo de perfis
        $profiles = [];
        if (file_exists(PROFILES_FILE)) {
            $profiles = file(PROFILES_FILE, FILE_IGNORE_NEW_LINES);
        }
        
        // Remover perfil existente se houver
        $profiles = array_filter($profiles, function($line) use ($user_email) {
            return !str_starts_with($line, $user_email . '|');
        });
        
        // Adicionar novo perfil
        $profiles[] = $user_email . '|' . json_encode($profile_data);
        file_put_contents(PROFILES_FILE, implode("\n", $profiles) . "\n");
        
        $profile = $profile_data;
        $success_message = 'Perfil atualizado com sucesso!';
    }
}

// Dados padrão se não houver perfil
if (!$profile) {
    $profile = [
        'name' => $user['name'] ?? 'Usuário',
        'bio' => 'Apaixonado por cerâmica ibérica e peças de mercado vintage.',
        'location' => 'Lisboa, Portugal',
        'specialty' => 'ceramica',
        'experience' => 'intermedio',
        'notifications' => ['novos_objetos', 'atualizacoes_valor']
    ];
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Chronos - Perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="icon" type="image/png" href="assets/images/logooos.png">
    <link rel="shortcut icon" type="image/png" href="assets/images/logooos.png">
    <link rel="apple-touch-icon" href="assets/images/logooos.png">
    <link href="https://fonts.googleapis.com/css2?family=La+Belle+Aurore&family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #FAF3E3;
            color: #630102;
            font-family: 'Lato', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        header {
            background: #FFD97D;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 32px;
            position: relative;
            z-index: 10;
            min-height: 80px;
        }
        
        .logo-img {
            height: 48px;
            max-width: 180px;
            min-width: 120px;
            margin: 0 auto;
            display: block;
            position: relative;
            z-index: 2;
        }
        
        .nav-btns {
            display: flex;
            gap: 18px;
        }
        
        .nav-btn {
            background: rgba(99, 1, 2, 0.7);
            color: #FFD97D;
            border: none;
            border-radius: 18px;
            padding: 7px 18px;
            font-family: 'Lato', Arial, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 4px 16px rgba(255, 217, 125, 0.13);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            margin: 0;
            outline: none;
        }
        
        .nav-btn:hover {
            background: #630102;
            color: #FFD97D;
            transform: scale(1.04);
        }
        
        .menu-btn {
            display: none;
            width: 32px;
            height: 32px;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .nav-btns {
                display: none;
            }
            .menu-btn {
                display: block;
            }
        }
        
        /* Sidebar styles */
        .sidebar {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 260px;
            height: 100vh;
            background: rgba(255,217,125,0.18);
            box-shadow: -4px 0 32px rgba(255,217,125,0.18);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            z-index: 10000;
            flex-direction: column;
            align-items: flex-end;
            padding: 32px 18px 18px 18px;
            animation: sidebarIn 0.25s;
            overflow: hidden;
        }
        .sidebar.open {
            display: flex;
        }
        .close-btn {
            background: none;
            border: none;
            color: #FFD97D;
            font-size: 2.2rem;
            position: absolute;
            left: 18px;
            top: 18px;
            cursor: pointer;
            margin-bottom: 18px;
            z-index: 1;
        }
        .sidebar-btns {
            display: flex;
            flex-direction: column;
            gap: 18px;
            width: 100%;
            margin-bottom: 32px;
        }
        .sidebar-link {
            display: block;
            text-align: center;
            background: #630102;
            color: #FFD97D;
            border-radius: 18px;
            padding: 10px 0;
            font-family: 'Lato', Arial, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(255,217,125,0.13);
            margin-top: 18px;
            transition: background 0.2s;
        }
        .sidebar-link:hover {
            background: #2d0101;
        }
        @keyframes sidebarIn {
            from { right: -260px; opacity: 0; }
            to { right: 0; opacity: 1; }
        }
        .sidebar-auth {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            margin-top: 18px;
        }
        .sidebar-auth-btn {
            display: block;
            text-align: center;
            background: rgba(255,217,125,0.7);
            color: #630102;
            border-radius: 18px;
            padding: 12px 0;
            font-family: 'Lato', Arial, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(255,217,125,0.13);
            transition: background 0.2s;
        }
        .sidebar-auth-btn:hover {
            background: #E2BF5F;
        }
        .sidebar-btns, .sidebar-auth {
            margin-top: 48px;
        }
        
        /* Main content styles */
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 32px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            box-sizing: border-box;
        }
        
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #E2BF5F;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto 24px auto;
            font-size: 3rem;
            color: #630102;
            border: 4px solid #630102;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        
        .profile-picture i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 3rem;
            z-index: 1;
            pointer-events: none;
        }
        
        .profile-name {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #630102;
            margin-bottom: 16px;
            text-align: center;
            width: 100%;
        }
        
        .profile-bio {
            font-size: 1.1rem;
            color: #630102;
            margin-bottom: 16px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        
        .profile-location {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 1rem;
            color: #630102;
            margin-bottom: 32px;
        }
        
        .location-icon {
            font-size: 1.2rem;
        }
        
        .edit-profile-btn {
            background: transparent;
            color: #630102;
            border: 2px solid #630102;
            border-radius: 24px;
            padding: 12px 32px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Lato', Arial, sans-serif;
            margin-bottom: 48px;
        }
        
        .edit-profile-btn:hover {
            background: #630102;
            color: #FAF3E3;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #630102;
            margin-bottom: 24px;
        }
        
        .activity-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 48px;
        }
        
        .activity-btn {
            background: transparent;
            color: #630102;
            border: 2px solid #630102;
            border-radius: 16px;
            padding: 16px 24px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Lato', Arial, sans-serif;
            text-align: center;
        }
        
        .activity-btn:hover {
            background: #630102;
            color: #FAF3E3;
        }
        
        .success-message {
            background: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        /* Edit Profile Modal Styles */
        .edit-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            backdrop-filter: blur(5px);
        }
        
        .edit-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #FAF3E3;
            border-radius: 20px;
            padding: 0;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .edit-modal-header {
            background: #630102;
            color: #FFD97D;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .edit-modal-header h3 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
        }
        
        .close-modal-btn {
            background: none;
            border: none;
            color: #FFD97D;
            font-size: 2rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .edit-modal-body {
            padding: 30px;
        }
        
        .edit-section {
            margin-bottom: 25px;
        }
        
        .edit-section label {
            display: block;
            font-weight: 700;
            color: #630102;
            margin-bottom: 8px;
            font-size: 1rem;
        }
        
        .edit-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #630102;
            border-radius: 12px;
            background: white;
            color: #630102;
            font-family: 'Lato', Arial, sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .edit-input:focus {
            outline: none;
            border-color: #E2BF5F;
            box-shadow: 0 0 0 3px rgba(226, 191, 95, 0.2);
        }
        
        .experience-buttons {
            display: flex;
            gap: 10px;
        }
        
        .exp-btn {
            flex: 1;
            padding: 10px 16px;
            border: 2px solid #630102;
            background: transparent;
            color: #630102;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Lato', Arial, sans-serif;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        
        .exp-btn.active {
            background: #630102;
            color: #FFD97D;
        }
        
        .exp-btn:hover {
            background: #630102;
            color: #FFD97D;
        }
        
        .notification-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: #630102;
            font-weight: 600;
        }
        
        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #630102;
        }
        
        .edit-modal-footer {
            padding: 20px 30px 30px;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }
        
        .save-btn, .cancel-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-family: 'Lato', Arial, sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
        .save-btn {
            background: #630102;
            color: #FFD97D;
        }
        
        .save-btn:hover {
            background: #2d0101;
            transform: translateY(-2px);
        }
        
        .cancel-btn {
            background: transparent;
            color: #630102;
            border: 2px solid #630102;
        }
        
        .cancel-btn:hover {
            background: #630102;
            color: #FFD97D;
        }
        
        /* Responsive Design */
        @media (max-width: 900px) {
            .profile-container {
                padding: 30px 20px;
                text-align: center;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                margin: 0 auto;
            }
            
            .profile-picture {
                width: 120px;
                height: 120px;
                margin: 20px auto 24px auto;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                left: auto;
                transform: none;
                overflow: hidden;
                box-sizing: border-box;
            }
            
            .profile-picture i {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                font-size: 2.5rem;
            }
            
            .profile-name,
            .profile-bio,
            .profile-location,
            .edit-profile-btn,
            .section-title,
            .activity-buttons {
                text-align: center;
                width: 100%;
                max-width: 100%;
            }
            
            .edit-profile-btn {
                margin-left: auto;
                margin-right: auto;
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .profile-picture {
                width: 80px;
                height: 80px;
                margin: 20px auto 24px auto;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                left: auto;
                transform: none;
                overflow: hidden;
                box-sizing: border-box;
            }
            
            .profile-picture i {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .profile-picture {
                width: 70px;
                height: 70px;
                margin: 20px auto 24px auto;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                left: auto;
                transform: none;
                overflow: hidden;
                box-sizing: border-box;
            }
            
            .profile-picture i {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                font-size: 1.8rem;
            }
        }
        
        /* Footer styles */
        .novo-footer {
            background: #FFD97D;
            color: #630102;
            padding: 0 0 24px 0;
            text-align: center;
            font-family: 'Lato', Arial, sans-serif;
            margin-top: 64px;
        }
        
        .footer-logo-area {
            padding-top: 12px;
        }
        
        .footer-logo {
            max-width: 110px;
            margin: 0 auto 12px auto;
            display: block;
        }
        
        .footer-nav {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        
        .footer-nav a {
            color: #630102;
            text-decoration: none;
            font-size: 1rem;
            font-family: 'Lato', Arial, sans-serif;
            transition: color 0.2s;
        }
        
        .footer-nav a:hover {
            color: #2d0101;
        }
        
        .footer-divider {
            border: none;
            border-top: 1px solid #630102;
            margin: 18px 2% 18px 2%;
        }
        
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            max-width: 96vw;
            margin: 0 auto;
            padding: 0 2%;
        }
        
        .footer-copy {
            color: #630102;
            font-size: 1rem;
            text-align: left;
        }
        
        .footer-links {
            display: flex;
            gap: 24px;
        }
        
        .footer-links a {
            color: #630102;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.2s;
        }
        
        .footer-links a:hover {
            color: #2d0101;
        }
        
        @media (max-width: 768px) {
            .profile-container {
                padding: 20px 16px;
            }
            
            .footer-nav {
                gap: 14px;
                font-size: 0.95rem;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-btns left-nav">
            <button class="nav-btn" onclick="window.location.href='home.html'">Sobre</button>
            <button class="nav-btn" id="idBtn">ID</button>
        </div>
        <img src="assets/images/Vector.png" alt="Logo Chronos" class="logo-img">
        <div class="nav-btns right-nav">
            <button class="nav-btn">Perfil</button>
            <button class="nav-btn" onclick="window.location.href='help.html'">Help</button>
        </div>
        <div class="menu-btn-circle">
            <img src="assets/images/logsoso.png" alt="Abrir menu" class="menu-btn" onclick="openSidebar()">
        </div>
    </header>

    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="closeSidebar()">&times;</button>
        <div class="sidebar-btns">
            <button class="nav-btn" onclick="window.location.href='home.html'">Sobre</button>
            <button class="nav-btn" id="sidebarIdBtn">ID</button>
            <button class="nav-btn">Perfil</button>
            <button class="nav-btn" onclick="window.location.href='help.html'">Help</button>
        </div>
        <div class="sidebar-auth">
            <a href="logout.php" class="sidebar-auth-btn">Logout</a>
        </div>
    </div>

    <main class="profile-container">
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <!-- Profile Picture -->
        <div class="profile-picture">
            <i class="bi bi-person"></i>
        </div>
        
        <!-- Profile Name -->
        <h1 class="profile-name"><?php echo htmlspecialchars($profile['name']); ?></h1>
        
        <!-- Profile Bio -->
        <p class="profile-bio"><?php echo htmlspecialchars($profile['bio']); ?></p>
        
        <!-- Profile Location -->
        <div class="profile-location">
            <i class="bi bi-geo-alt location-icon"></i>
            <span><?php echo htmlspecialchars($profile['location']); ?></span>
        </div>
        
        <!-- Edit Profile Button -->
        <button class="edit-profile-btn" onclick="openEditModal()">Editar Perfil</button>
        
        <!-- Edit Profile Modal -->
        <div id="editModal" class="edit-modal">
            <div class="edit-modal-content">
                <div class="edit-modal-header">
                    <h3>Editar Perfil</h3>
                    <button class="close-modal-btn" onclick="closeEditModal()">&times;</button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="edit-modal-body">
                        <div class="edit-section">
                            <label>Nome:</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" class="edit-input" required>
                        </div>
                        <div class="edit-section">
                            <label>Bio:</label>
                            <textarea name="bio" class="edit-input" rows="3" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                        </div>
                        <div class="edit-section">
                            <label>Localização:</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($profile['location']); ?>" class="edit-input" required>
                        </div>
                        <div class="edit-section">
                            <label>Especialidade:</label>
                            <select name="specialty" class="edit-input">
                                <option value="ceramica" <?php echo $profile['specialty'] === 'ceramica' ? 'selected' : ''; ?>>Cerâmica Ibérica</option>
                                <option value="vintage" <?php echo $profile['specialty'] === 'vintage' ? 'selected' : ''; ?>>Mercado Vintage</option>
                                <option value="antiguidades" <?php echo $profile['specialty'] === 'antiguidades' ? 'selected' : ''; ?>>Antiguidades</option>
                                <option value="arte" <?php echo $profile['specialty'] === 'arte' ? 'selected' : ''; ?>>Arte Tradicional</option>
                                <option value="moedas" <?php echo $profile['specialty'] === 'moedas' ? 'selected' : ''; ?>>Numismática</option>
                                <option value="livros" <?php echo $profile['specialty'] === 'livros' ? 'selected' : ''; ?>>Livros Antigos</option>
                            </select>
                        </div>
                        <div class="edit-section">
                            <label>Nível de Experiência:</label>
                            <div class="experience-buttons">
                                <button type="button" class="exp-btn <?php echo $profile['experience'] === 'iniciante' ? 'active' : ''; ?>" onclick="setExperience('iniciante')">Iniciante</button>
                                <button type="button" class="exp-btn <?php echo $profile['experience'] === 'intermedio' ? 'active' : ''; ?>" onclick="setExperience('intermedio')">Intermediário</button>
                                <button type="button" class="exp-btn <?php echo $profile['experience'] === 'avancado' ? 'active' : ''; ?>" onclick="setExperience('avancado')">Avançado</button>
                            </div>
                            <input type="hidden" name="experience" id="experienceInput" value="<?php echo htmlspecialchars($profile['experience']); ?>">
                        </div>
                        <div class="edit-section">
                            <label>Notificações:</label>
                            <div class="notification-options">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="notifications[]" value="novos_objetos" <?php echo in_array('novos_objetos', $profile['notifications']) ? 'checked' : ''; ?>> Novos objetos similares
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="notifications[]" value="atualizacoes_valor" <?php echo in_array('atualizacoes_valor', $profile['notifications']) ? 'checked' : ''; ?>> Atualizações de valor
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="notifications[]" value="eventos_colecionadores" <?php echo in_array('eventos_colecionadores', $profile['notifications']) ? 'checked' : ''; ?>> Eventos de colecionadores
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="edit-modal-footer">
                        <button type="submit" class="save-btn">Guardar Alterações</button>
                        <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Activity and Contributions Section -->
        <h2 class="section-title">Atividade e Contribuições</h2>
        <div class="activity-buttons">
            <button class="activity-btn" onclick="showIdentifications()">N.º de identificações realizadas: <?php echo count($identifications); ?></button>
            <button class="activity-btn" onclick="showFavorites()">Favoritos guardados: <?php echo count($favorites); ?></button>
        </div>
        
        <!-- Activity Details -->
        <div id="activityDetails" style="display: none;">
            <div class="activity-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </main>

    <footer class="novo-footer">
        <div class="footer-logo-area">
            <img src="assets/images/Vector1.png" alt="Logo Chronos" class="footer-logo">
        </div>
        <nav class="footer-nav">
            <a href="home.html">Explorar</a>
            <a href="home.html#upload-section">Identificar Relíquias</a>
            <a href="sobre.html">Sobre</a>
            <a href="perfil.php">Perfil</a>
            <a href="help.html">Help</a>
        </nav>
        <hr class="footer-divider">
        <div class="footer-bottom">
            <span class="footer-copy">© 2024 Relume. All rights reserved.</span>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookies Settings</a>
            </div>
        </div>
    </footer>

    <script>
        // Edit Profile Modal Functions
        function openEditModal() {
            document.getElementById('editModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function setExperience(level) {
            // Remove active class from all buttons
            document.querySelectorAll('.exp-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Update hidden input
            document.getElementById('experienceInput').value = level;
        }
        
        function showIdentifications() {
            const details = document.getElementById('activityDetails');
            details.style.display = 'block';
            details.innerHTML = `
                <div class="activity-content">
                    <h3>Identificações Realizadas</h3>
                    <p>Total de identificações: <?php echo count($identifications); ?></p>
                    <?php if (!empty($identifications)): ?>
                        <ul>
                            <?php foreach ($identifications as $identification): ?>
                                <li><?php echo htmlspecialchars($identification['title'] ?? 'Identificação'); ?> - <?php echo htmlspecialchars($identification['date'] ?? ''); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Ainda não realizou nenhuma identificação.</p>
                    <?php endif; ?>
                </div>
            `;
        }
        
        function showFavorites() {
            const details = document.getElementById('activityDetails');
            details.style.display = 'block';
            details.innerHTML = `
                <div class="activity-content">
                    <h3>Favoritos Guardados</h3>
                    <p>Total de favoritos: <?php echo count($favorites); ?></p>
                    <?php if (!empty($favorites)): ?>
                        <ul>
                            <?php foreach ($favorites as $favorite): ?>
                                <li><?php echo htmlspecialchars($favorite['title'] ?? 'Favorito'); ?> - <?php echo htmlspecialchars($favorite['date'] ?? ''); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Ainda não guardou nenhum favorito.</p>
                    <?php endif; ?>
                </div>
            `;
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const editModal = document.getElementById('editModal');
            if (event.target === editModal) {
                closeEditModal();
            }
        });
        
        // Sidebar functionality
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
        }
        
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
        }
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.menu-btn');
            
            if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html> 