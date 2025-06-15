<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Nouveau Produit</h1>
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
                                        <i class="fas fa-box text-primary me-2"></i>
                                        Ajouter un nouveau produit
                                    </h5>
                                    <p class="text-muted mb-0 small">Remplissez les informations ci-dessous pour creer un nouveau produit</p>
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
                    <form id="addProductForm" method="POST" action="process_produit.php">
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
                                                <input type="text" class="form-control" id="nom" name="nom" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="prix_unitaire" class="form-label fw-semibold">Prix unitaire (DH) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" step="0.01" min="0" required>
                                                    <span class="input-group-text bg-light">DH</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="quantite_en_stock" class="form-label fw-semibold">Quantite en stock <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </span>
                                                    <input type="number" class="form-control" id="quantite_en_stock" name="quantite_en_stock" min="0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="seuil_stock" class="form-label fw-semibold">Seuil d'alerte stock</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-exclamation-triangle text-muted"></i>
                                                    </span>
                                                    <input type="number" class="form-control" id="seuil_stock" name="seuil_stock" min="0" placeholder="Ex: 10">
                                                </div>
                                                <div class="form-text">Quantite minimale avant alerte de stock faible</div>
                                            </div>
                                            <div class="col-12">
                                                <label for="description" class="form-label fw-semibold">Description du produit</label>
                                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description detaillee du produit..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categories et Statut -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-tags text-primary me-2"></i>
                                            Statut et Fournisseur
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="statut" class="form-label fw-semibold">Statut</label>
                                                <select class="form-select" id="statut" name="statut">
                                                    <option value="Disponible" selected>Disponible</option>
                                                    <option value="Indisponible">Indisponible</option>
                                                    <option value="Discontinue">Discontinue</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fournisseur" class="form-label fw-semibold">Fournisseur</label>
                                                <input type="text" class="form-control" id="fournisseur" name="fournisseur" placeholder="Nom du fournisseur">
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
                                            <i class="fas fa-save me-2"></i>Enregistrer le produit
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