<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Nouveau Client</h1>
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
                                        <i class="fas fa-user-plus text-success me-2"></i>
                                        Ajouter un nouveau client
                                    </h5>
                                    <p class="text-muted mb-0 small">Remplissez les informations ci-dessous pour créer un nouveau client</p>
                                </div>
                                <a href="clients.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form id="addClientForm" method="POST" action="process_client.php">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            Informations Générales
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="nom" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nom" name="nom" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="entreprise" class="form-label fw-semibold">Entreprise</label>
                                                <input type="text" class="form-control" id="entreprise" name="entreprise">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-envelope text-muted"></i>
                                                    </span>
                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telephone" class="form-label fw-semibold">Téléphone <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-phone text-muted"></i>
                                                    </span>
                                                    <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="+212 6 XX XX XX XX" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                                                <select class="form-select" id="ville" name="ville" required>
                                                    <option value="">Sélectionner une ville</option>
                                                    <option value="Casablanca">Casablanca</option>
                                                    <option value="Rabat">Rabat</option>
                                                    <option value="Marrakech">Marrakech</option>
                                                    <option value="Fès">Fès</option>
                                                    <option value="Tanger">Tanger</option>
                                                    <option value="Agadir">Agadir</option>
                                                    <option value="Meknès">Meknès</option>
                                                    <option value="Oujda">Oujda</option>
                                                    <option value="Autre">Autre</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="statut" class="form-label fw-semibold">Statut</label>
                                                <select class="form-select" id="statut" name="statut">
                                                    <option value="Actif" selected>Actif</option>
                                                    <option value="Inactif">Inactif</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="adresse" class="form-label fw-semibold">Adresse complète</label>
                                                <textarea class="form-control" id="adresse" name="adresse" rows="3" placeholder="Adresse complète du client..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>                            
                        </div>
                        <div class="row">
                        <div class="col-lg-12">

                                <!-- Actions Card -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Enregistrer le client
                                        </button>

                                        <a href="clients.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Annuler
                                        </a>
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

<?php include_once '../includes/scripts.php'; ?>