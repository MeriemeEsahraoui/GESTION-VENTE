<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion de Vente</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="login-left">
                        <div class="brand-logo">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h1 class="brand-title">VENTE</h1>
                        <p class="brand-subtitle">Votre solution complète de gestion des ventes</p>
                        
                        <div class="mt-4">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Suivi des ventes en temps réel</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Gestion des clients</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Rapports et analyses détaillés</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="login-right">
                        <div class="text-center d-lg-none mb-4">
                            <div class="brand-logo mx-auto" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <h2 class="mt-3" style="color: #667eea;">SalesManager</h2>
                        </div>
                        
                        <h2 class="login-title">Bon retour !</h2>
                        <p class="login-subtitle">Connectez-vous pour accéder à votre tableau de bord</p>
                        
                        <form id="loginForm">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" placeholder="nom@exemple.com" required>
                                <label for="email">
                                    <i class="fa fa-envelope me-2"></i>Adresse email
                                </label>
                            </div>
                            
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" placeholder="Mot de passe" required>
                                <label for="password">
                                    <i class="fa fa-lock me-2"></i>Mot de passe
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>
                                Se connecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const submitBtn = document.querySelector('.btn-login');
            
            // Basic validation
            if (!email || !password) {
                alert('Veuillez remplir tous les champs');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion...';
            submitBtn.disabled = true;
            
            // Simulate authentication delay
            setTimeout(() => {
                // For demo purposes, accept any email/password combination
                // In real application, this would be server-side authentication
                if (email && password) {
                    // Store fake session data
                    localStorage.setItem('isLoggedIn', 'true');
                    localStorage.setItem('userEmail', email);
                    localStorage.setItem('userName', 'Meriem Essahraoui');
                    localStorage.setItem('userRole', 'Administrateur');
                    
                    // Show success message briefly
                    submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Connexion réussie !';
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-success');
                    
                    // Redirect to dashboard after a short delay
                    setTimeout(() => {
                        window.location.href = 'pages/acceuil.php';
                    }, 1000);
                } else {
                    // Handle error (though this won't happen in our fake auth)
                    submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Erreur de connexion';
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-danger');
                    
                    setTimeout(() => {
                        submitBtn.innerHTML = '<i class="fa-solid fa-right-to-bracket me-2"></i>Se connecter';
                        submitBtn.classList.remove('btn-danger');
                        submitBtn.classList.add('btn-primary');
                        submitBtn.disabled = false;
                    }, 2000);
                }
            }, 1500); // 1.5 second delay to simulate server response
        });
        
        // Auto-fill demo credentials on page load for easier testing
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is already logged in
            if (localStorage.getItem('isLoggedIn') === 'true') {
                // Show a message and redirect
                document.querySelector('.login-right').innerHTML = `
                    <div class="text-center">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle fa-2x mb-3"></i>
                            <h4>Déjà connecté !</h4>
                            <p>Redirection vers le tableau de bord...</p>
                        </div>
                    </div>
                `;
                setTimeout(() => {
                    window.location.href = 'pages/acceuil.php';
                }, 2000);
                return;
            }
        });
    </script>
</body>
</html>