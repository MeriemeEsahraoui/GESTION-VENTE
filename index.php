<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion de Vente</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
</body>
</html>