<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Check if we're editing an existing facture
$is_edit = isset($_GET['id']) && !empty($_GET['id']);
$facture_id = $is_edit ? intval($_GET['id']) : null;
$facture = null;
$facture_details = [];

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

// Load bons de commande for dropdown
try {
    $bons_commande_list = $db->query("SELECT bc.*, c.nom as client_nom, c.prenom as client_prenom 
                                     FROM bons_commande bc 
                                     LEFT JOIN clients c ON bc.client_id = c.id 
                                     WHERE bc.statut IN ('confirme', 'livree') 
                                     ORDER BY bc.cree_le DESC")->fetchAll();
} catch(PDOException $e) {
    $bons_commande_list = [];
}

// Load bon de commande details for JavaScript (to auto-load products when bon de commande is selected)
$bon_commande_details = [];
try {
    $stmt = $db->query("SELECT dbc.*, bc.client_id 
                       FROM details_bon_commande dbc 
                       JOIN bons_commande bc ON dbc.bon_commande_id = bc.id 
                       WHERE bc.statut IN ('confirme', 'livree')");
    while ($row = $stmt->fetch()) {
        if (!isset($bon_commande_details[$row['bon_commande_id']])) {
            $bon_commande_details[$row['bon_commande_id']] = [];
        }
        $bon_commande_details[$row['bon_commande_id']][] = $row;
    }
} catch(PDOException $e) {
    $bon_commande_details = [];
}

// Load facture data if editing
if ($is_edit) {
    try {
        $stmt = $db->prepare("SELECT * FROM factures WHERE id = ?");
        $stmt->execute([$facture_id]);
        $facture = $stmt->fetch();
        
        if ($facture) {
            // Load facture details
            $stmt = $db->prepare("SELECT * FROM details_facture WHERE facture_id = ?");
            $stmt->execute([$facture_id]);
            $facture_details = $stmt->fetchAll();
        } else {
            header('Location: factures.php?error=Facture non trouvée');
            exit;
        }
    } catch(PDOException $e) {
        header('Location: factures.php?error=Erreur lors du chargement de la facture');
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
        <h1 class="page-title"><?php echo $is_edit ? 'Modifier Facture' : 'Nouvelle Facture'; ?></h1>
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
                                        <i class="fas fa-file-invoice text-primary me-2"></i>
                                        <?php echo $is_edit ? 'Modifier la facture' : 'Créer une nouvelle facture'; ?>
                                    </h5>
                                    <p class="text-muted mb-0 small"><?php echo $is_edit ? 'Modifiez les informations ci-dessous' : 'Remplissez les informations ci-dessous pour créer une nouvelle facture'; ?></p>
                                </div>
                                <a href="factures.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form id="addFactureForm" method="POST" action="process_facture.php">
                        <?php if ($is_edit): ?>
                            <input type="hidden" name="facture_id" value="<?php echo $facture_id; ?>">
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
                                            <div class="col-md-6">
                                                <label for="client_id" class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                                                <select class="form-select" id="client_id" name="client_id" required>
                                                    <option value="">Sélectionner un client</option>
                                                    <?php foreach ($clients as $client): ?>
                                                        <option value="<?php echo $client['id']; ?>" 
                                                            <?php echo ($is_edit && $facture['client_id'] == $client['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                                            <?php if ($client['entreprise']): ?>
                                                                - <?php echo htmlspecialchars($client['entreprise']); ?>
                                                            <?php endif; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="bon_commande_id" class="form-label fw-semibold">Bon de commande associé</label>
                                                <select class="form-select" id="bon_commande_id" name="bon_commande_id">
                                                    <option value="">Aucun bon de commande</option>
                                                    <?php foreach ($bons_commande_list as $bc): ?>
                                                        <option value="<?php echo $bc['id']; ?>" 
                                                            <?php echo ($is_edit && $facture['bon_commande_id'] == $bc['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($bc['numero']); ?> - 
                                                            <?php echo htmlspecialchars($bc['client_prenom'] . ' ' . $bc['client_nom']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
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
                                                Articles de la Facture
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
                                        <textarea class="form-control" id="remarques" name="remarques" rows="4" placeholder="Remarques ou instructions particulières..."><?php echo $is_edit ? htmlspecialchars($facture['remarques']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Facture Details -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Détails de la Facture
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="facture_number" class="form-label fw-semibold">Numéro de Facture</label>
                                            <input type="text" class="form-control" id="facture_number" name="facture_number" 
                                                value="<?php echo $is_edit ? htmlspecialchars($facture['numero']) : 'AUTO-GÉNÉRÉ'; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_facture" class="form-label fw-semibold">Date de facture <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date_facture" name="date_facture" 
                                                value="<?php echo $is_edit ? $facture['date_facture'] : date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="conditions_paiement" class="form-label fw-semibold">Conditions de paiement</label>
                                            <select class="form-select" id="conditions_paiement" name="conditions_paiement">
                                                <option value="comptant" <?php echo ($is_edit && $facture['conditions_paiement'] == 'comptant') ? 'selected' : ''; ?>>Comptant</option>
                                                <option value="15_jours" <?php echo ($is_edit && $facture['conditions_paiement'] == '15_jours') ? 'selected' : ''; ?>>15 jours</option>
                                                <option value="30_jours" <?php echo ($is_edit && $facture['conditions_paiement'] == '30_jours') || !$is_edit ? 'selected' : ''; ?>>30 jours</option>
                                                <option value="60_jours" <?php echo ($is_edit && $facture['conditions_paiement'] == '60_jours') ? 'selected' : ''; ?>>60 jours</option>
                                                <option value="fin_mois" <?php echo ($is_edit && $facture['conditions_paiement'] == 'fin_mois') ? 'selected' : ''; ?>>Fin de mois</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_echeance" class="form-label fw-semibold">Date d'échéance</label>
                                            <input type="date" class="form-control" id="date_echeance" name="date_echeance" 
                                                value="<?php echo $is_edit ? $facture['date_echeance'] : date('Y-m-d', strtotime('+30 days')); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_paiement" class="form-label fw-semibold">Date de paiement</label>
                                            <input type="date" class="form-control" id="date_paiement" name="date_paiement" 
                                                value="<?php echo $is_edit ? $facture['date_paiement'] : ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="statut" class="form-label fw-semibold">Statut</label>
                                            <select class="form-select" id="statut" name="statut">
                                                <option value="brouillon" <?php echo ($is_edit && $facture['statut'] == 'brouillon') || !$is_edit ? 'selected' : ''; ?>>Brouillon</option>
                                                <option value="envoyee" <?php echo ($is_edit && $facture['statut'] == 'envoyee') ? 'selected' : ''; ?>>Envoyée</option>
                                                <option value="payee" <?php echo ($is_edit && $facture['statut'] == 'payee') ? 'selected' : ''; ?>>Payée</option>
                                                <option value="en_retard" <?php echo ($is_edit && $facture['statut'] == 'en_retard') ? 'selected' : ''; ?>>En retard</option>
                                                <option value="annulee" <?php echo ($is_edit && $facture['statut'] == 'annulee') ? 'selected' : ''; ?>>Annulée</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mode_paiement" class="form-label fw-semibold">Mode de paiement</label>
                                            <select class="form-select" id="mode_paiement" name="mode_paiement">
                                                <option value="especes" <?php echo ($is_edit && $facture['mode_paiement'] == 'especes') ? 'selected' : ''; ?>>Espèces</option>
                                                <option value="cheque" <?php echo ($is_edit && $facture['mode_paiement'] == 'cheque') ? 'selected' : ''; ?>>Chèque</option>
                                                <option value="virement" <?php echo ($is_edit && $facture['mode_paiement'] == 'virement') || !$is_edit ? 'selected' : ''; ?>>Virement</option>
                                                <option value="carte" <?php echo ($is_edit && $facture['mode_paiement'] == 'carte') ? 'selected' : ''; ?>>Carte</option>
                                                <option value="prelevement" <?php echo ($is_edit && $facture['mode_paiement'] == 'prelevement') ? 'selected' : ''; ?>>Prélèvement</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-2"></i><?php echo $is_edit ? 'Modifier la Facture' : 'Enregistrer la Facture'; ?>
                                            </button>
                                            <a href="factures.php" class="btn btn-outline-secondary">
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

// Bon de commande data from database
const bonCommandeData = {
    <?php foreach ($bons_commande_list as $bc): ?>
    <?php echo $bc['id']; ?>: {
        id: <?php echo $bc['id']; ?>,
        numero: '<?php echo addslashes($bc['numero']); ?>',
        client_id: <?php echo $bc['client_id']; ?>,
        client_name: '<?php echo addslashes($bc['client_prenom'] . ' ' . $bc['client_nom']); ?>'
    },
    <?php endforeach; ?>
};

// Bon de commande details data from database
const bonCommandeDetailsData = {
    <?php foreach ($bon_commande_details as $bc_id => $details): ?>
    <?php echo $bc_id; ?>: [
        <?php foreach ($details as $detail): ?>
        {
            produit_id: <?php echo $detail['produit_id']; ?>,
            nom_produit: '<?php echo addslashes($detail['nom_produit']); ?>',
            description_produit: '<?php echo addslashes($detail['description_produit']); ?>',
            quantite: <?php echo $detail['quantite']; ?>,
            prix_unitaire: <?php echo $detail['prix_unitaire']; ?>,
            total_ligne: <?php echo $detail['total_ligne']; ?>
        },
        <?php endforeach; ?>
    ],
    <?php endforeach; ?>
};

// Update client info when client is selected
document.getElementById('client_id').addEventListener('change', function() {
    const clientId = this.value;
    const clientInfo = document.getElementById('clientInfo');
    
    // Update client info display
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
    
    // Filter bon de commande dropdown based on selected client
    filterBonCommandeByClient(clientId);
});

// Update products when bon de commande is selected
document.getElementById('bon_commande_id').addEventListener('change', function() {
    const bcId = this.value;
    
    if (bcId && bonCommandeDetailsData[bcId]) {
        // Ask user if they want to load products from bon de commande
        if (confirm('Voulez-vous charger les produits de ce bon de commande ? Cela remplacera les produits actuels.')) {
            loadProductsFromBonCommande(bcId);
        }
    }
});

// Function to filter bon de commande dropdown based on selected client
function filterBonCommandeByClient(selectedClientId) {
    const bcSelect = document.getElementById('bon_commande_id');
    
    // Clear current selection
    bcSelect.value = '';
    
    // Remove all existing options except the first one (placeholder)
    while (bcSelect.children.length > 1) {
        bcSelect.removeChild(bcSelect.lastChild);
    }
    
    // Add filtered options
    if (selectedClientId) {
        // Filter bon de commande for the selected client
        let hasBonCommande = false;
        Object.values(bonCommandeData).forEach(bc => {
            if (bc.client_id == selectedClientId) {
                const option = document.createElement('option');
                option.value = bc.id;
                option.textContent = bc.numero + ' - ' + bc.client_name;
                bcSelect.appendChild(option);
                hasBonCommande = true;
            }
        });
        
        // If no bon de commande found for the client, add a message
        if (!hasBonCommande) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Aucun bon de commande pour ce client';
            option.disabled = true;
            bcSelect.appendChild(option);
        }
    } else {
        // If no client selected, show all bon de commande
        Object.values(bonCommandeData).forEach(bc => {
            const option = document.createElement('option');
            option.value = bc.id;
            option.textContent = bc.numero + ' - ' + bc.client_name;
            bcSelect.appendChild(option);
        });
    }
}

// Function to load products from selected bon de commande
function loadProductsFromBonCommande(bcId) {
    // Clear existing products
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';
    
    // Reset product line counter
    productLineCounter = 0;
    
    // Load products from bon de commande
    if (bonCommandeDetailsData[bcId]) {
        bonCommandeDetailsData[bcId].forEach(detail => {
            selectProduct(
                detail.produit_id,
                detail.nom_produit,
                detail.prix_unitaire,
                detail.quantite
            );
        });
    }
    
    // If no products were loaded, show empty message
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2"></i><br>
                    Aucun article trouvé dans ce bon de commande.
                </td>
            </tr>
        `;
    }
}

// Update due date when payment terms change
document.getElementById('conditions_paiement').addEventListener('change', function() {
    const dateFacture = document.getElementById('date_facture').value;
    const dateEcheance = document.getElementById('date_echeance');
    
    if (dateFacture) {
        const factureDate = new Date(dateFacture);
        let dueDate = new Date(factureDate);
        
        switch(this.value) {
            case 'comptant':
                dueDate = new Date(factureDate);
                break;
            case '15_jours':
                dueDate.setDate(factureDate.getDate() + 15);
                break;
            case '30_jours':
                dueDate.setDate(factureDate.getDate() + 30);
                break;
            case '60_jours':
                dueDate.setDate(factureDate.getDate() + 60);
                break;
            case 'fin_mois':
                dueDate = new Date(factureDate.getFullYear(), factureDate.getMonth() + 1, 0);
                break;
        }
        
        dateEcheance.value = dueDate.toISOString().split('T')[0];
    }
});

// Update due date when invoice date changes
document.getElementById('date_facture').addEventListener('change', function() {
    const conditionsPaiement = document.getElementById('conditions_paiement').value;
    const dateEcheance = document.getElementById('date_echeance');
    
    const factureDate = new Date(this.value);
    let dueDate = new Date(factureDate);
    
    switch(conditionsPaiement) {
        case 'comptant':
            dueDate = new Date(factureDate);
            break;
        case '15_jours':
            dueDate.setDate(factureDate.getDate() + 15);
            break;
        case '30_jours':
            dueDate.setDate(factureDate.getDate() + 30);
            break;
        case '60_jours':
            dueDate.setDate(factureDate.getDate() + 60);
            break;
        case 'fin_mois':
            dueDate = new Date(factureDate.getFullYear(), factureDate.getMonth() + 1, 0);
            break;
    }
    
    dateEcheance.value = dueDate.toISOString().split('T')[0];
});

<?php if ($is_edit && isset($facture_details)): ?>
// Load existing facture details for editing
<?php foreach ($facture_details as $detail): ?>
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
document.getElementById('addFactureForm').addEventListener('submit', function(e) {
    const clientId = document.getElementById('client_id').value;
    const hasProducts = document.querySelectorAll('#productsTableBody input[name*="[product_id]"]').length > 0;
    
    if (!clientId) {
        alert('Veuillez sélectionner un client');
        e.preventDefault();
        return;
    }
    
    if (!hasProducts) {
        alert('Veuillez ajouter au moins un article à la facture');
        e.preventDefault();
        return;
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Trigger due date calculation on load
    const conditionsSelect = document.getElementById('conditions_paiement');
    if (conditionsSelect.value) {
        conditionsSelect.dispatchEvent(new Event('change'));
    }
    
    <?php if ($is_edit && $facture): ?>
    // If editing, filter bon de commande based on selected client
    const selectedClientId = '<?php echo $facture['client_id']; ?>';
    if (selectedClientId) {
        filterBonCommandeByClient(selectedClientId);
        // Trigger client info display
        document.getElementById('client_id').dispatchEvent(new Event('change'));
    }
    <?php endif; ?>
});
</script>

<?php include_once '../includes/scripts.php'; ?>