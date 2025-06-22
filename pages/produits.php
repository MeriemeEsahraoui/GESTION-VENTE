<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Handle delete request
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    try {
        $stmt = $db->prepare("UPDATE produits SET status = 0 WHERE id = ?");
        $stmt->execute([$product_id]);
        $success_message = "Produit supprimé avec succès";
    } catch(PDOException $e) {
        $error_message = "Erreur lors de la suppression du produit";
    }
}

// Fetch products data
try {
    $produits_query = "SELECT * FROM produits WHERE status = 1 ORDER BY cree_le DESC";
    $produits = $db->query($produits_query)->fetchAll();
    
    $stats_produits_actifs = $db->query("SELECT COUNT(*) as total FROM produits WHERE status = 1")->fetch();
    $stats_rupture_stock = $db->query("SELECT COUNT(*) as total FROM produits WHERE status = 1 AND quantite_en_stock <= seuil_stock")->fetch();
    $stats_nouveaux_mois = $db->query("SELECT COUNT(*) as total FROM produits WHERE status = 1 AND MONTH(cree_le) = MONTH(CURDATE()) AND YEAR(cree_le) = YEAR(CURDATE())")->fetch();
    $stats_valeur_stock = $db->query("SELECT COALESCE(SUM(prix_unitaire * quantite_en_stock), 0) as total FROM produits WHERE status = 1")->fetch();
    
} catch(PDOException $e) {
    $produits = [];
    $stats_produits_actifs = ['total' => 0];
    $stats_rupture_stock = ['total' => 0];
    $stats_nouveaux_mois = ['total' => 0];
    $stats_valeur_stock = ['total' => 0];
    $error_message = "Erreur lors du chargement des produits";
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
        <h1 class="page-title">Liste des Produits</h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="container-fluid">
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
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
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control" placeholder="Rechercher un produit..." id="searchInput">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="export.php?type=produits&format=csv" class="btn btn-outline-secondary">
                                            <i class="fas fa-download me-1"></i>Exporter CSV
                                        </a>
                                        <a href="produit.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Nouveau Produit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-box fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_produits_actifs['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Produits Total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_rupture_stock['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Stock Faible</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-plus-circle fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_nouveaux_mois['total']); ?></h4>
                                    <p class="text-muted mb-0 small">Nouveaux ce mois</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-gradient rounded-3 p-3 text-white">
                                        <i class="fas fa-money-bill-wave fa-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0 fw-bold"><?php echo number_format($stats_valeur_stock['total'], 2); ?></h4>
                                    <p class="text-muted mb-0 small">Valeur Stock (DH)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-boxes text-primary me-2"></i>
                                    Liste des Produits
                                </h5>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Table View -->
                            <div class="table-responsive" id="tableView">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th>Prix Unitaire</th>
                                            <th>Stock</th>
                                            <th>Statut</th>
                                            <th>Date Creation</th>
                                            <th style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($produits)): ?>
                                            <?php foreach ($produits as $produit): ?>
                                                <?php
                                                $stock_status = '';
                                                $stock_class = '';
                                                $stock_text = '';
                                                
                                                if ($produit['quantite_en_stock'] <= $produit['seuil_stock']) {
                                                    $stock_status = 'Stock Faible';
                                                    $stock_class = 'bg-warning';
                                                    $stock_text = 'text-warning';
                                                } elseif ($produit['quantite_en_stock'] == 0) {
                                                    $stock_status = 'Rupture';
                                                    $stock_class = 'bg-danger';
                                                    $stock_text = 'text-danger';
                                                } else {
                                                    $stock_status = 'Disponible';
                                                    $stock_class = 'bg-success';
                                                    $stock_text = 'text-success';
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-gradient rounded-3 d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 50px; height: 50px;">
                                                                <i class="fas fa-box"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold"><?php echo htmlspecialchars($produit['nom']); ?></div>
                                                                <?php if ($produit['description']): ?>
                                                                    <small class="text-muted"><?php echo htmlspecialchars(substr($produit['description'], 0, 50)) . (strlen($produit['description']) > 50 ? '...' : ''); ?></small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success"><?php echo number_format($produit['prix_unitaire'], 2); ?> DH</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge <?php echo $stock_class; ?> me-2"><?php echo $produit['quantite_en_stock']; ?></span>
                                                            <small class="<?php echo $stock_text; ?>">
                                                                <?php echo $produit['quantite_en_stock'] > $produit['seuil_stock'] ? 'En stock' : 'Stock faible'; ?>
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?php echo $stock_class; ?>"><?php echo $stock_status; ?></span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($produit['cree_le'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="produit.php?id=<?php echo $produit['id']; ?>" class="btn btn-outline-warning" title="Modifier">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteProduct(<?php echo $produit['id']; ?>)" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="fas fa-box fa-2x mb-3"></i><br>
                                                    Aucun produit trouvé
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php if (!empty($produits)): ?>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Affichage de <?php echo count($produits); ?> sur <?php echo $stats_produits_actifs['total']; ?> produits
                                </div>
                                <?php if ($stats_produits_actifs['total'] > 10): ?>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="page-link" href="#">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce produit ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="product_id" id="deleteProductId">
                    <button type="submit" name="delete_product" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProduct(productId) {
    document.getElementById('deleteProductId').value = productId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php include_once '../includes/scripts.php'; ?>