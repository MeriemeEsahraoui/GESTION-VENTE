<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Check if we're editing an existing devis
$is_edit = isset($_GET['id']) && !empty($_GET['id']);
$devis_id = $is_edit ? intval($_GET['id']) : null;
$devis = null;
$devis_details = [];

// Load clients
try {
    $clients = $db->query("SELECT * FROM clients WHERE status = 1 ORDER BY nom, prenom")->fetchAll();
} catch(PDOException $e) {
    $clients = [];
}

// Load products
try {
    $products = $db->query("SELECT * FROM produits WHERE status = 1 ORDER BY nom")->fetchAll();
} catch(PDOException $e) {
    $products = [];
}

// Load devis data if editing
if ($is_edit) {
    try {
        $stmt = $db->prepare("SELECT * FROM devis WHERE id = ?");
        $stmt->execute([$devis_id]);
        $devis = $stmt->fetch();
        
        if ($devis) {
            // Load devis details
            $stmt = $db->prepare("SELECT * FROM details_devis WHERE devis_id = ?");
            $stmt->execute([$devis_id]);
            $devis_details = $stmt->fetchAll();
        } else {
            header('Location: devis.php?error=Devis non trouvé');
            exit;
        }
    } catch(PDOException $e) {
        header('Location: devis.php?error=Erreur lors du chargement du devis');
        exit;
    }
}

// Handle messages
$success_message = $_GET['success'] ?? '';
$error_message = $_GET['error'] ?? '';
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
        <h1 class="page-title"><?php echo $is_edit ? 'Modifier Devis' : 'Nouveau Devis'; ?></h1>
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
                                        <i class="fas fa-file-invoice text-primary me-2"></i>
                                        <?php echo $is_edit ? 'Modifier le devis' : 'Créer un nouveau devis'; ?>
                                    </h5>
                                    <p class="text-muted mb-0 small"><?php echo $is_edit ? 'Modifiez les informations ci-dessous' : 'Remplissez les informations ci-dessous pour créer un nouveau devis'; ?></p>
                                </div>
                                <a href="devis.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
            
            <div class="row">
                <div class="col-12">
                    <form id="addDevisForm" method="POST" action="process_devis.php">
                        <?php if ($is_edit): ?>
                            <input type="hidden" name="devis_id" value="<?php echo $devis_id; ?>">
                        <?php endif; ?>
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-8">
                                <!-- Client Information -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            Information Client
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="client_id" class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                                                <select class="form-select" id="client_id" name="client_id" required>
                                                    <option value="">Sélectionner un client</option>
                                                    <?php foreach ($clients as $client): ?>
                                                        <option value="<?php echo $client['id']; ?>" 
                                                            <?php echo ($is_edit && $devis['client_id'] == $client['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                                            <?php if ($client['entreprise']): ?>
                                                                - <?php echo htmlspecialchars($client['entreprise']); ?>
                                                            <?php endif; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">&nbsp;</label>
                                                <a href="client.php" class="btn btn-outline-primary d-block">
                                                    <i class="fas fa-plus me-1"></i>Nouveau Client
                                                </a>
                                            </div>
                                        </div>
                                        <div id="clientInfo" class="mt-3" style="display: none;">
                                            <div class="alert alert-light border">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong id="clientName"></strong><br>
                                                        <span id="clientCompany" class="text-muted"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope me-1"></i><span id="clientEmail"></span><br>
                                                            <i class="fas fa-phone me-1"></i><span id="clientPhone"></span><br>
                                                            <i class="fas fa-map-marker-alt me-1"></i><span id="clientAddress"></span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Products Section -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-boxes text-primary me-2"></i>
                                                Articles du Devis
                                            </h6>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="addProductLine()">
                                                <i class="fas fa-plus me-1"></i>Ajouter Article
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="productsTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="40%">Produit</th>
                                                        <th width="15%">Prix Unit.</th>
                                                        <th width="15%">Quantité</th>
                                                        <th width="20%">Total</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="productsTableBody">
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-4">
                                                            <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                                            Aucun article ajouté. Cliquez sur "Ajouter Article" pour commencer.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Total Section -->
                                        <div class="row mt-3">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-4">
                                                <div class="card bg-light">
                                                    <div class="card-body py-2">
                                                        <div class="d-flex justify-content-between">
                                                            <strong>Total HT:</strong>
                                                            <strong id="totalHT">0.00 DH</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>TVA (20%):</span>
                                                            <span id="totalTVA">0.00 DH</span>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="d-flex justify-content-between">
                                                            <strong class="text-primary">Total TTC:</strong>
                                                            <strong class="text-primary" id="totalTTC">0.00 DH</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-comment text-primary me-2"></i>
                                            Remarques
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <textarea class="form-control" id="remarque" name="remarque" rows="4" placeholder="Remarques ou conditions particulières..."><?php echo $is_edit ? htmlspecialchars($devis['remarques']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Devis Details -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Détails du Devis
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="devis_number" class="form-label fw-semibold">Numéro de Devis</label>
                                            <input type="text" class="form-control" id="devis_number" name="devis_number" 
                                                value="<?php echo $is_edit ? htmlspecialchars($devis['numero']) : 'AUTO-GÉNÉRÉ'; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_devis" class="form-label fw-semibold">Date du Devis</label>
                                            <input type="date" class="form-control" id="date_devis" name="date_devis" 
                                                value="<?php echo $is_edit ? $devis['date_devis'] : date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="validite_jours" class="form-label fw-semibold">Validité (jours) <span class="text-danger">*</span></label>
                                            <select class="form-select" id="validite_jours" name="validite_jours" required>
                                                <option value="7" <?php echo ($is_edit && $devis['validite_jours'] == 7) ? 'selected' : ''; ?>>7 jours</option>
                                                <option value="15" <?php echo ($is_edit && $devis['validite_jours'] == 15) ? 'selected' : ''; ?>>15 jours</option>
                                                <option value="30" <?php echo ($is_edit && $devis['validite_jours'] == 30) || !$is_edit ? 'selected' : ''; ?>>30 jours</option>
                                                <option value="60" <?php echo ($is_edit && $devis['validite_jours'] == 60) ? 'selected' : ''; ?>>60 jours</option>
                                                <option value="90" <?php echo ($is_edit && $devis['validite_jours'] == 90) ? 'selected' : ''; ?>>90 jours</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="statut" class="form-label fw-semibold">Statut</label>
                                            <select class="form-select" id="statut" name="statut">
                                                <option value="en_attente" <?php echo ($is_edit && $devis['statut'] == 'en_attente') || !$is_edit ? 'selected' : ''; ?>>En attente</option>
                                                <option value="accepte" <?php echo ($is_edit && $devis['statut'] == 'accepte') ? 'selected' : ''; ?>>Accepté</option>
                                                <option value="refuse" <?php echo ($is_edit && $devis['statut'] == 'refuse') ? 'selected' : ''; ?>>Refusé</option>
                                                <option value="expire" <?php echo ($is_edit && $devis['statut'] == 'expire') ? 'selected' : ''; ?>>Expiré</option>
                                            </select>
                                        </div>
                                        <div class="alert alert-info small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Date d'expiration: <strong id="expirationDate"></strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-2"></i><?php echo $is_edit ? 'Modifier le Devis' : 'Enregistrer le Devis'; ?>
                                            </button>
                                            <a href="devis.php" class="btn btn-outline-secondary">
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

<!-- Product Selection Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sélectionner un Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="productSearch" placeholder="Rechercher un produit...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productModalBody">
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($product['nom']); ?></div>
                                            <?php if ($product['description']): ?>
                                                <small class="text-muted"><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . (strlen($product['description']) > 50 ? '...' : ''); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($product['prix_unitaire'], 2); ?> DH</td>
                                    <td>
                                        <span class="badge <?php echo $product['quantite_en_stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $product['quantite_en_stock']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="selectProduct(<?php echo $product['id']; ?>, '<?php echo addslashes($product['nom']); ?>', <?php echo $product['prix_unitaire']; ?>)" <?php echo $product['quantite_en_stock'] == 0 ? 'disabled' : ''; ?>>
                                            Sélectionner
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let productLineCounter = 0;

// Client data from database
const clientsData = {
    <?php foreach ($clients as $client): ?>
    <?php echo $client['id']; ?>: {
        name: '<?php echo addslashes($client['prenom'] . ' ' . $client['nom']); ?>',
        company: '<?php echo addslashes($client['entreprise']); ?>',
        email: '<?php echo addslashes($client['email']); ?>',
        phone: '<?php echo addslashes($client['telephone']); ?>',
        address: '<?php echo addslashes($client['adresse']); ?>'
    },
    <?php endforeach; ?>
};

// Update client info when client is selected
document.getElementById('client_id').addEventListener('change', function() {
    const clientId = this.value;
    const clientInfo = document.getElementById('clientInfo');
    
    if (clientId && clientsData[clientId]) {
        const client = clientsData[clientId];
        document.getElementById('clientName').textContent = client.name;
        document.getElementById('clientCompany').textContent = client.company;
        document.getElementById('clientEmail').textContent = client.email;
        document.getElementById('clientPhone').textContent = client.phone;
        document.getElementById('clientAddress').textContent = client.address;
        clientInfo.style.display = 'block';
    } else {
        clientInfo.style.display = 'none';
    }
});

// Update expiration date
function updateExpirationDate() {
    const dateDevis = document.getElementById('date_devis').value;
    const validiteJours = parseInt(document.getElementById('validite_jours').value);
    
    if (dateDevis && validiteJours) {
        const date = new Date(dateDevis);
        date.setDate(date.getDate() + validiteJours);
        document.getElementById('expirationDate').textContent = date.toLocaleDateString('fr-FR');
    }
}

document.getElementById('date_devis').addEventListener('change', updateExpirationDate);
document.getElementById('validite_jours').addEventListener('change', updateExpirationDate);

// Initialize expiration date
updateExpirationDate();

<?php if ($is_edit && isset($devis_details)): ?>
// Load existing devis details for editing
<?php foreach ($devis_details as $detail): ?>
selectProduct(
    <?php echo $detail['produit_id']; ?>,
    '<?php echo addslashes($detail['nom_produit']); ?>',
    <?php echo $detail['prix_unitaire']; ?>,
    <?php echo $detail['quantite']; ?>
);
<?php endforeach; ?>
<?php endif; ?>

// Add product line
function addProductLine() {
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
}

// Select product from modal
function selectProduct(productId, productName, price, quantity = 1) {
    productLineCounter++;
    
    const tbody = document.getElementById('productsTableBody');
    
    // Remove empty message if it exists
    if (tbody.querySelector('tr td[colspan="5"]')) {
        tbody.innerHTML = '';
    }
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="hidden" name="products[${productLineCounter}][product_id]" value="${productId}">
            <div class="fw-semibold">${productName}</div>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="products[${productLineCounter}][prix_unitaire]" 
                   value="${price}" 
                   step="0.01" 
                   onchange="calculateLineTotal(${productLineCounter})">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="products[${productLineCounter}][quantite]" 
                   value="${quantity}" 
                   min="1" 
                   onchange="calculateLineTotal(${productLineCounter})">
        </td>
        <td>
            <span class="fw-bold" id="lineTotal${productLineCounter}">${(price * quantity).toFixed(2)} DH</span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeProductLine(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Close modal only if called from modal (not when loading existing data)
    const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
    if (modal) {
        modal.hide();
    }
    
    calculateTotals();
}

// Remove product line
function removeProductLine(button) {
    button.closest('tr').remove();
    
    // Add empty message if no products
    const tbody = document.getElementById('productsTableBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2"></i><br>
                    Aucun article ajouté. Cliquez sur "Ajouter Article" pour commencer.
                </td>
            </tr>
        `;
    }
    
    calculateTotals();
}

// Calculate line total
function calculateLineTotal(lineId) {
    const priceInput = document.querySelector(`input[name="products[${lineId}][prix_unitaire]"]`);
    const quantityInput = document.querySelector(`input[name="products[${lineId}][quantite]"]`);
    const totalSpan = document.getElementById(`lineTotal${lineId}`);
    
    const price = parseFloat(priceInput.value) || 0;
    const quantity = parseInt(quantityInput.value) || 0;
    const total = price * quantity;
    
    totalSpan.textContent = total.toFixed(2) + ' DH';
    calculateTotals();
}

// Calculate all totals
function calculateTotals() {
    let totalHT = 0;
    
    document.querySelectorAll('#productsTableBody tr').forEach(row => {
        const priceInput = row.querySelector('input[name*="[prix_unitaire]"]');
        const quantityInput = row.querySelector('input[name*="[quantite]"]');
        
        if (priceInput && quantityInput) {
            const price = parseFloat(priceInput.value) || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            totalHT += price * quantity;
        }
    });
    
    const totalTVA = totalHT * 0.20;
    const totalTTC = totalHT + totalTVA;
    
    document.getElementById('totalHT').textContent = totalHT.toFixed(2) + ' DH';
    document.getElementById('totalTVA').textContent = totalTVA.toFixed(2) + ' DH';
    document.getElementById('totalTTC').textContent = totalTTC.toFixed(2) + ' DH';
}

// Form validation
document.getElementById('addDevisForm').addEventListener('submit', function(e) {
    const clientId = document.getElementById('client_id').value;
    const hasProducts = document.querySelectorAll('#productsTableBody input[name*="[product_id]"]').length > 0;
    
    if (!clientId) {
        alert('Veuillez sélectionner un client');
        e.preventDefault();
        return;
    }
    
    if (!hasProducts) {
        alert('Veuillez ajouter au moins un article au devis');
        e.preventDefault();
        return;
    }
});
</script>

<?php include_once '../includes/scripts.php'; ?>