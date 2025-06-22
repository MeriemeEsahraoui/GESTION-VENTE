<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
$is_edit = isset($_GET['id']) && !empty($_GET['id']);
$produit = null;
$page_title = $is_edit ? "Modifier Produit" : "Nouveau Produit";
$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix_unitaire = floatval($_POST['prix_unitaire'] ?? 0);
    $quantite_en_stock = intval($_POST['quantite_en_stock'] ?? 0);
    $fournisseur = trim($_POST['fournisseur'] ?? '');
    $seuil_stock = intval($_POST['seuil_stock'] ?? 0);
    
    // Validation
    if (empty($nom) || $prix_unitaire <= 0) {
        $error_message = "Le nom et le prix unitaire sont requis";
    } else {
        try {
            if ($is_edit) {
                // Update existing product
                $stmt = $db->prepare("UPDATE produits SET nom = ?, description = ?, prix_unitaire = ?, quantite_en_stock = ?, fournisseur = ?, seuil_stock = ? WHERE id = ?");
                $stmt->execute([$nom, $description, $prix_unitaire, $quantite_en_stock, $fournisseur, $seuil_stock, $_GET['id']]);
                $success_message = "Produit modifié avec succès";
            } else {
                // Insert new product
                $stmt = $db->prepare("INSERT INTO produits (nom, description, prix_unitaire, quantite_en_stock, fournisseur, seuil_stock, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$nom, $description, $prix_unitaire, $quantite_en_stock, $fournisseur, $seuil_stock]);
                $success_message = "Produit ajouté avec succès";
                // Redirect to products list after successful creation
                if (!$is_edit) {
                    header('Location: produits.php?success=1');
                    exit;
                }
            }
        } catch(PDOException $e) {
            $error_message = "Erreur lors de l'enregistrement du produit";
        }
    }
}

// Fetch product data for editing
if ($is_edit) {
    try {
        $stmt = $db->prepare("SELECT * FROM produits WHERE id = ? AND status = 1");
        $stmt->execute([$_GET['id']]);
        $produit = $stmt->fetch();
        
        if (!$produit) {
            header('Location: produits.php');
            exit;
        }
    } catch(PDOException $e) {
        header('Location: produits.php');
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
                                        <i class="fas fa-<?php echo $is_edit ? 'edit' : 'box'; ?> text-<?php echo $is_edit ? 'warning' : 'primary'; ?> me-2"></i>
                                        <?php echo $is_edit ? 'Modifier le produit' : 'Ajouter un nouveau produit'; ?>
                                    </h5>
                                    <p class="text-muted mb-0 small">
                                        <?php echo $is_edit ? 'Modifiez les informations du produit ci-dessous' : 'Remplissez les informations ci-dessous pour créer un nouveau produit'; ?>
                                    </p>
                                </div>
                                <a href="produits.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour e la liste
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
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Informations Generales
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="nom" class="form-label fw-semibold">Nom du produit <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($produit['nom'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="prix_unitaire" class="form-label fw-semibold">Prix unitaire (DH) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" step="0.01" min="0" value="<?php echo htmlspecialchars($produit['prix_unitaire'] ?? ''); ?>" required>
                                                    <span class="input-group-text bg-light">DH</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="quantite_en_stock" class="form-label fw-semibold">Quantité en stock</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </span>
                                                    <input type="number" class="form-control" id="quantite_en_stock" name="quantite_en_stock" min="0" value="<?php echo htmlspecialchars($produit['quantite_en_stock'] ?? '0'); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="seuil_stock" class="form-label fw-semibold">Seuil d'alerte stock</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-exclamation-triangle text-muted"></i>
                                                    </span>
                                                    <input type="number" class="form-control" id="seuil_stock" name="seuil_stock" min="0" value="<?php echo htmlspecialchars($produit['seuil_stock'] ?? '0'); ?>" placeholder="Ex: 10">
                                                </div>
                                                <div class="form-text">Quantité minimale avant alerte de stock faible</div>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="fournisseur" class="form-label fw-semibold">Fournisseur</label>
                                                <input type="text" class="form-control" id="fournisseur" name="fournisseur" value="<?php echo htmlspecialchars($produit['fournisseur'] ?? ''); ?>" placeholder="Nom du fournisseur">
                                            </div>
                                            <div class="col-12">
                                                <label for="description" class="form-label fw-semibold">Description du produit</label>
                                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description détaillée du produit..."><?php echo htmlspecialchars($produit['description'] ?? ''); ?></textarea>
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
                                            <i class="fas fa-save me-2"></i><?php echo $is_edit ? 'Modifier le produit' : 'Enregistrer le produit'; ?>
                                        </button>

                                        <a href="produits.php" class="btn btn-outline-secondary">
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

<script>
// Auto-generate reference if empty
document.getElementById('nom').addEventListener('blur', function() {
    const nom = this.value;
    const referenceField = document.getElementById('reference');
    
    if (nom && !referenceField.value) {
        // Generate reference from product name
        const ref = 'REF-' + nom.substring(0, 3).toUpperCase() + '-' + Date.now().toString().slice(-4);
        referenceField.value = ref;
    }
});

// Stock validation
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    const stock = parseInt(document.getElementById('quantite_en_stock').value);
    const seuil = parseInt(document.getElementById('seuil_stock').value) || 0;
    
    if (seuil > 0 && stock <= seuil) {
        if (!confirm('Le stock actuel est inferieur ou egal au seuil d\'alerte. Voulez-vous continuer ?')) {
            e.preventDefault();
        }
    }
});
</script>

<?php include_once '../includes/scripts.php'; ?>