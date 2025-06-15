<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Nouvel Utilisateur</h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="container-fluid">
            
            <!-- Page Header with Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">
                                        <i class="fas fa-user-plus text-primary me-2"></i>
                                        Créer un nouvel utilisateur
                                    </h5>
                                    <p class="text-muted mb-0 small">Remplissez les informations ci-dessous pour créer un nouveau compte utilisateur</p>
                                </div>
                                <a href="utilisateurs.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form id="addUserForm" method="POST" action="process_user.php">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <!-- Personal Information -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-id-card text-primary me-2"></i>
                                            Informations Personnelles
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nom" name="nom" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                                <div class="form-text">L'email servira d'identifiant de connexion</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                                                <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="+212 6 XX XX XX XX">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Security -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-shield-alt text-primary me-2"></i>
                                            Sécurité du Compte
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="mot_de_passe" class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('mot_de_passe')">
                                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                    </button>
                                                </div>
                                                <div class="form-text">Minimum 8 caractères, incluant majuscules, minuscules et chiffres</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="confirmer_mot_de_passe" class="form-label fw-semibold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmer_mot_de_passe')">
                                                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Password Strength Indicator -->
                                        <div class="mt-3">
                                            <div class="small text-muted mb-1">Force du mot de passe:</div>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <div class="small mt-1" id="passwordStrengthText">Très faible</div>
                                        </div>

                                        <!-- Generate Password Button -->
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-outline-info btn-sm" onclick="generatePassword()">
                                                <i class="fas fa-magic me-1"></i>Générer un mot de passe sécurisé
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Account Details -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-user-cog text-primary me-2"></i>
                                            Détails du Compte
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="status" class="form-label fw-semibold">Statut du compte</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="1" selected>Actif</option>
                                                <option value="0">Inactif</option>
                                            </select>
                                        </div>

                                        <div class="alert alert-info small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> Un email de bienvenue sera envoyé automatiquement à l'utilisateur avec ses identifiants de connexion.
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-user-plus me-2"></i>Créer l'Utilisateur
                                            </button>
                                            <a href="utilisateurs.php" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-2"></i>Annuler
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Role descriptions
const roleDescriptions = {
    'admin': 'Accès complet au système, gestion des utilisateurs et paramètres',
    'vendeur': 'Accès aux ventes, devis, commandes et clients'
};

// Update role permissions based on selection
function updateRolePermissions() {
    const role = document.getElementById('role').value;
    const description = document.getElementById('roleDescription');
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    // Update description
    description.textContent = roleDescriptions[role] || '';
    
    // Set default permissions based on role
    checkboxes.forEach(checkbox => checkbox.checked = false);
    
    if (role === 'admin') {
        // Admin gets all permissions
        checkboxes.forEach(checkbox => checkbox.checked = true);
    } else if (role === 'vendeur') {
        // Vendeur gets limited permissions
        document.getElementById('can_view_reports').checked = true;
        document.getElementById('can_export_data').checked = true;
    }
}

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId === 'mot_de_passe' ? 'togglePasswordIcon' : 'toggleConfirmPasswordIcon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Generate secure password
function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    
    // Ensure at least one of each type
    password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
    password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
    password += '0123456789'[Math.floor(Math.random() * 10)];
    password += '!@#$%^&*'[Math.floor(Math.random() * 8)];
    
    // Fill remaining length
    for (let i = 4; i < 12; i++) {
        password += chars[Math.floor(Math.random() * chars.length)];
    }
    
    // Shuffle password
    password = password.split('').sort(() => Math.random() - 0.5).join('');
    
    document.getElementById('mot_de_passe').value = password;
    document.getElementById('confirmer_mot_de_passe').value = password;
    
    // Update strength indicator
    updatePasswordStrength();
}

// Check password strength
function updatePasswordStrength() {
    const password = document.getElementById('mot_de_passe').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    
    let strength = 0;
    let text = 'Très faible';
    let color = 'bg-danger';
    
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
    
    if (strength >= 100) {
        text = 'Très forte';
        color = 'bg-success';
    } else if (strength >= 75) {
        text = 'Forte';
        color = 'bg-info';
    } else if (strength >= 50) {
        text = 'Moyenne';
        color = 'bg-warning';
    } else if (strength >= 25) {
        text = 'Faible';
        color = 'bg-warning';
    }
    
    strengthBar.className = `progress-bar ${color}`;
    strengthBar.style.width = Math.min(strength, 100) + '%';
    strengthText.textContent = text;
}

// Send test email
function sendTestEmail() {
    const email = document.getElementById('email').value;
    if (!email) {
        alert('Veuillez entrer une adresse email d\'abord');
        return;
    }
    
    if (confirm(`Envoyer un email de test à ${email} ?`)) {
        // AJAX call to send test email
        console.log('Sending test email to:', email);
        alert('Email de test envoyé avec succès !');
    }
}

// Form validation
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('mot_de_passe').value;
    const confirmPassword = document.getElementById('confirmer_mot_de_passe').value;
    const email = document.getElementById('email').value;
    const role = document.getElementById('role').value;
    
    // Check required fields
    if (!email || !password || !role) {
        alert('Veuillez remplir tous les champs obligatoires');
        e.preventDefault();
        return;
    }
    
    // Check password match
    if (password !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas');
        e.preventDefault();
        return;
    }
    
    // Check password strength
    if (password.length < 8) {
        alert('Le mot de passe doit contenir au moins 8 caractères');
        e.preventDefault();
        return;
    }
    
    // Check email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Veuillez entrer une adresse email valide');
        e.preventDefault();
        return;
    }
});

// Password strength check on input
document.getElementById('mot_de_passe').addEventListener('input', updatePasswordStrength);

// Auto-generate email based on name
document.getElementById('prenom').addEventListener('input', generateEmailSuggestion);
document.getElementById('nom').addEventListener('input', generateEmailSuggestion);

function generateEmailSuggestion() {
    const prenom = document.getElementById('prenom').value.toLowerCase();
    const nom = document.getElementById('nom').value.toLowerCase();
    const emailField = document.getElementById('email');
    
    if (prenom && nom && !emailField.value) {
        const suggestion = `${prenom}.${nom}@gestion-vente.ma`;
        emailField.value = suggestion;
    }
}
</script>

<?php include_once '../includes/scripts.php'; ?>