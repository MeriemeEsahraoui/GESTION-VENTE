<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
$is_edit = isset($_GET['id']) && !empty($_GET['id']);
$client = null;
$page_title = $is_edit ? "Modifier Client" : "Nouveau Client";
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $entreprise = trim($_POST['entreprise'] ?? '');
    
    // Validation
    if (empty($nom) || empty($prenom) || empty($email)) {
        $error_message = "Les champs nom, prénom et email sont requis";
    } else {
        try {
            if ($is_edit) {
                // Update existing client
                $stmt = $db->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, ville = ?, entreprise = ? WHERE id = ?");
                $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $ville, $entreprise, $_GET['id']]);
                $success_message = "Client modifié avec succès";
            } else {
                // Insert new client
                $stmt = $db->prepare("INSERT INTO clients (nom, prenom, email, telephone, adresse, ville, entreprise, status) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $ville, $entreprise]);
                $success_message = "Client ajouté avec succès";
                // Redirect to clients list after successful creation
                if (!$is_edit) {
                    header('Location: clients.php?success=1');
                    exit;
                }
            }
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $error_message = "Cette adresse email est déjà utilisée";
            } else {
                $error_message = "Erreur lors de l'enregistrement du client";
            }
        }
    }
}

// Fetch client data for editing
if ($is_edit) {
    try {
        $stmt = $db->prepare("SELECT * FROM clients WHERE id = ? AND status = 1");
        $stmt->execute([$_GET['id']]);
        $client = $stmt->fetch();
        
        if (!$client) {
            header('Location: clients.php');
            exit;
        }
    } catch(PDOException $e) {
        header('Location: clients.php');
        exit;
    }
}
?>

<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title"><?php echo $page_title; ?></h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="container-fluid">
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Page Header with Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold">
                                        <i class="fas fa-<?php echo $is_edit ? 'user-edit' : 'user-plus'; ?> text-<?php echo $is_edit ? 'warning' : 'success'; ?> me-2"></i>
                                        <?php echo $is_edit ? 'Modifier le client' : 'Ajouter un nouveau client'; ?>
                                    </h5>
                                    <p class="text-muted mb-0 small">
                                        <?php echo $is_edit ? 'Modifiez les informations du client ci-dessous' : 'Remplissez les informations ci-dessous pour créer un nouveau client'; ?>
                                    </p>
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
                    <form method="POST" action="">
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
                                                <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($client['nom'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($client['prenom'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="entreprise" class="form-label fw-semibold">Entreprise</label>
                                                <input type="text" class="form-control" id="entreprise" name="entreprise" value="<?php echo htmlspecialchars($client['entreprise'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-envelope text-muted"></i>
                                                    </span>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-phone text-muted"></i>
                                                    </span>
                                                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($client['telephone'] ?? ''); ?>" placeholder="+212 6 XX XX XX XX">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="ville" class="form-label fw-semibold">Ville</label>
                                                <select class="form-select" id="ville" name="ville">
                                                    <option value="">Sélectionner une ville</option>
                                                    <?php 
                                                    $villes = ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès', 'Oujda', 'Autre'];
                                                    foreach ($villes as $ville) {
                                                        $selected = (isset($client['ville']) && $client['ville'] === $ville) ? 'selected' : '';
                                                        echo "<option value=\"$ville\" $selected>$ville</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="adresse" class="form-label fw-semibold">Adresse complète</label>
                                                <textarea class="form-control" id="adresse" name="adresse" rows="3" placeholder="Adresse complète du client..."><?php echo htmlspecialchars($client['adresse'] ?? ''); ?></textarea>
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
                                        <button type="submit" class="btn btn-<?php echo $is_edit ? 'warning' : 'success'; ?>">
                                            <i class="fas fa-save me-2"></i><?php echo $is_edit ? 'Modifier le client' : 'Enregistrer le client'; ?>
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