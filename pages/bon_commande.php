<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/auth_check.php'; ?>

<?php
// Check if we're editing an existing bon de commande
$is_edit = isset($_GET['id']) && !empty($_GET['id']);
$commande_id = $is_edit ? intval($_GET['id']) : null;
$commande = null;
$commande_details = [];

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

// Load devis for dropdown - include accepted and pending devis
try {
    $devis_list = $db->query("SELECT d.*, c.nom as client_nom, c.prenom as client_prenom 
                             FROM devis d 
                             LEFT JOIN clients c ON d.client_id = c.id 
                             WHERE d.statut IN ('accepte', 'en_attente') 
                             ORDER BY d.cree_le DESC")->fetchAll();
} catch(PDOException $e) {
    $devis_list = [];
}

// Load devis details for JavaScript (to auto-load products when devis is selected)
$devis_details = [];
try {
    $stmt = $db->query("SELECT dd.*, d.client_id 
                       FROM details_devis dd 
                       JOIN devis d ON dd.devis_id = d.id 
                       WHERE d.statut IN ('accepte', 'en_attente')");
    while ($row = $stmt->fetch()) {
        if (!isset($devis_details[$row['devis_id']])) {
            $devis_details[$row['devis_id']] = [];
        }
        $devis_details[$row['devis_id']][] = $row;
    }
} catch(PDOException $e) {
    $devis_details = [];
}


// Load commande data if editing
if ($is_edit) {
    try {
        $stmt = $db->prepare("SELECT * FROM bons_commande WHERE id = ?");
        $stmt->execute([$commande_id]);
        $commande = $stmt->fetch();
        
        if ($commande) {
            // Load commande details
            $stmt = $db->prepare("SELECT * FROM details_bon_commande WHERE bon_commande_id = ?");
            $stmt->execute([$commande_id]);
            $commande_details = $stmt->fetchAll();
        } else {
            header('Location: bons_commande.php?error=Bon de commande non trouvé');
            exit;
        }
    } catch(PDOException $e) {
        header('Location: bons_commande.php?error=Erreur lors du chargement du bon de commande');
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
        <h1 class="page-title"><?php echo $is_edit ? 'Modifier Bon de Commande' : 'Nouveau Bon de Commande'; ?></h1>
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
                                        <i class="fas fa-shopping-cart text-primary me-2"></i>
                                        <?php echo $is_edit ? 'Modifier le bon de commande' : 'Créer un nouveau bon de commande'; ?>
                                    </h5>
                                    <p class="text-muted mb-0 small"><?php echo $is_edit ? 'Modifiez les informations ci-dessous' : 'Remplissez les informations ci-dessous pour créer un nouveau bon de commande'; ?></p>
                                </div>
                                <a href="bons_commande.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form id="addCommandeForm" method="POST" action="process_bon_commande.php">
                        <?php if ($is_edit): ?>
                            <input type="hidden" name="commande_id" value="<?php echo $commande_id; ?>">
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
                                                            <?php echo ($is_edit && $commande['client_id'] == $client['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                                            <?php if ($client['entreprise']): ?>
                                                                - <?php echo htmlspecialchars($client['entreprise']); ?>
                                                            <?php endif; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="devis_id" class="form-label fw-semibold">Devis associé</label>
                                                <select class="form-select" id="devis_id" name="devis_id">
                                                    <option value="">Aucun devis</option>
                                                    <?php foreach ($devis_list as $devis): ?>
                                                        <option value="<?php echo $devis['id']; ?>" 
                                                            <?php echo ($is_edit && $commande['devis_id'] == $devis['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($devis['numero']); ?> - 
                                                            <?php echo htmlspecialchars($devis['client_prenom'] . ' ' . $devis['client_nom']); ?>
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
                                                Articles de la Commande
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

                                <!-- Delivery Address -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            Adresse de livraison
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <textarea class="form-control" id="adresse_livraison" name="adresse_livraison" rows="3" placeholder="Adresse de livraison..."><?php echo $is_edit ? htmlspecialchars($commande['adresse_livraison']) : ''; ?></textarea>
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
                                        <textarea class="form-control" id="remarques" name="remarques" rows="4" placeholder="Remarques ou instructions particulières..."><?php echo $is_edit ? htmlspecialchars($commande['remarques']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-4">
                                <!-- Commande Details -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Détails de la Commande
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="commande_number" class="form-label fw-semibold">Numéro de Commande</label>
                                            <input type="text" class="form-control" id="commande_number" name="commande_number" 
                                                value="<?php echo $is_edit ? htmlspecialchars($commande['numero']) : 'AUTO-GÉNÉRÉ'; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_livraison_prevue" class="form-label fw-semibold">Date de livraison prévue <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date_livraison_prevue" name="date_livraison_prevue" 
                                                value="<?php echo $is_edit ? $commande['date_livraison_prevue'] : date('Y-m-d', strtotime('+7 days')); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_livraison_reelle" class="form-label fw-semibold">Date de livraison réelle</label>
                                            <input type="date" class="form-control" id="date_livraison_reelle" name="date_livraison_reelle" 
                                                value="<?php echo $is_edit ? $commande['date_livraison_reelle'] : ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="statut" class="form-label fw-semibold">Statut</label>
                                            <select class="form-select" id="statut" name="statut">
                                                <option value="en_attente" <?php echo ($is_edit && $commande['statut'] == 'en_attente') || !$is_edit ? 'selected' : ''; ?>>En attente</option>
                                                <option value="confirme" <?php echo ($is_edit && $commande['statut'] == 'confirme') ? 'selected' : ''; ?>>Confirmé</option>
                                                <option value="expediee" <?php echo ($is_edit && $commande['statut'] == 'expediee') ? 'selected' : ''; ?>>Expédiée</option>
                                                <option value="livree" <?php echo ($is_edit && $commande['statut'] == 'livree') ? 'selected' : ''; ?>>Livrée</option>
                                                <option value="annulee" <?php echo ($is_edit && $commande['statut'] == 'annulee') ? 'selected' : ''; ?>>Annulée</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mode_paiement" class="form-label fw-semibold">Mode de paiement</label>
                                            <select class="form-select" id="mode_paiement" name="mode_paiement">
                                                <option value="especes" <?php echo ($is_edit && $commande['mode_paiement'] == 'especes') || !$is_edit ? 'selected' : ''; ?>>Espèces</option>
                                                <option value="cheque" <?php echo ($is_edit && $commande['mode_paiement'] == 'cheque') ? 'selected' : ''; ?>>Chèque</option>
                                                <option value="virement" <?php echo ($is_edit && $commande['mode_paiement'] == 'virement') ? 'selected' : ''; ?>>Virement</option>
                                                <option value="carte" <?php echo ($is_edit && $commande['mode_paiement'] == 'carte') ? 'selected' : ''; ?>>Carte</option>
                                                <option value="credit" <?php echo ($is_edit && $commande['mode_paiement'] == 'credit') ? 'selected' : ''; ?>>Crédit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-2"></i><?php echo $is_edit ? 'Modifier la Commande' : 'Enregistrer la Commande'; ?>
                                            </button>
                                            <a href="bons_commande.php" class="btn btn-outline-secondary">
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

// Devis data from database
const devisData = {
    <?php foreach ($devis_list as $devis): ?>
    <?php echo $devis['id']; ?>: {
        id: <?php echo $devis['id']; ?>,
        numero: '<?php echo addslashes($devis['numero']); ?>',
        client_id: <?php echo $devis['client_id']; ?>,
        client_name: '<?php echo addslashes($devis['client_prenom'] . ' ' . $devis['client_nom']); ?>'
    },
    <?php endforeach; ?>
};

// Devis details data from database
const devisDetailsData = {
    <?php foreach ($devis_details as $devis_id => $details): ?>
    <?php echo $devis_id; ?>: [
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
    const devisSelect = document.getElementById('devis_id');
    
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
    
    // Filter devis dropdown based on selected client
    filterDevisByClient(clientId);
});

// Update products when devis is selected
document.getElementById('devis_id').addEventListener('change', function() {
    const devisId = this.value;
    
    if (devisId && devisDetailsData[devisId]) {
        // Ask user if they want to load products from devis
        if (confirm('Voulez-vous charger les produits de ce devis ? Cela remplacera les produits actuels.')) {
            loadProductsFromDevis(devisId);
        }
    }
});

// Function to filter devis dropdown based on selected client
function filterDevisByClient(selectedClientId) {
    const devisSelect = document.getElementById('devis_id');
    
    // Clear current devis selection
    devisSelect.value = '';
    
    // Remove all existing options except the first one (placeholder)
    while (devisSelect.children.length > 1) {
        devisSelect.removeChild(devisSelect.lastChild);
    }
    
    // Check if devisData exists and has content
    if (!devisData || Object.keys(devisData).length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Aucun devis disponible';
        option.disabled = true;
        devisSelect.appendChild(option);
        return;
    }
    
    // Add filtered devis options
    if (selectedClientId) {
        // Filter devis for the selected client
        let hasDevis = false;
        Object.values(devisData).forEach(devis => {
            if (devis.client_id == selectedClientId) {
                const option = document.createElement('option');
                option.value = devis.id;
                option.textContent = devis.numero + ' - ' + devis.client_name;
                devisSelect.appendChild(option);
                hasDevis = true;
            }
        });
        
        // If no devis found for the client, add a message
        if (!hasDevis) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Aucun devis pour ce client';
            option.disabled = true;
            devisSelect.appendChild(option);
        }
    } else {
        // If no client selected, show all devis
        Object.values(devisData).forEach(devis => {
            const option = document.createElement('option');
            option.value = devis.id;
            option.textContent = devis.numero + ' - ' + devis.client_name;
            devisSelect.appendChild(option);
        });
    }
}

// Function to load products from selected devis
function loadProductsFromDevis(devisId) {
    // Clear existing products
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';
    
    // Reset product line counter
    productLineCounter = 0;
    
    // Load products from devis
    if (devisDetailsData[devisId]) {
        devisDetailsData[devisId].forEach(detail => {
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
                    Aucun article trouvé dans ce devis.
                </td>
            </tr>
        `;
    }
}

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    // If editing, filter devis based on selected client
    <?php if ($is_edit && $commande): ?>
    const selectedClientId = '<?php echo $commande['client_id']; ?>';
    const selectedDevisId = '<?php echo $commande['devis_id']; ?>';
    
    if (selectedClientId) {
        // Filter devis for the selected client
        filterDevisByClient(selectedClientId);
        
        // Restore selected devis after filtering
        setTimeout(() => {
            if (selectedDevisId) {
                document.getElementById('devis_id').value = selectedDevisId;
            }
        }, 100);
    }
    <?php endif; ?>
});

<?php if ($is_edit && isset($commande_details)): ?>
// Load existing commande details for editing
<?php foreach ($commande_details as $detail): ?>
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
document.getElementById('addCommandeForm').addEventListener('submit', function(e) {
    const clientId = document.getElementById('client_id').value;
    const hasProducts = document.querySelectorAll('#productsTableBody input[name*="[product_id]"]').length > 0;
    
    if (!clientId) {
        alert('Veuillez sélectionner un client');
        e.preventDefault();
        return;
    }
    
    if (!hasProducts) {
        alert('Veuillez ajouter au moins un article à la commande');
        e.preventDefault();
        return;
    }
});
</script>

<?php include_once '../includes/scripts.php'; ?>