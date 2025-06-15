<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Nouveau Devis</h1>
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
                                        Créer un nouveau devis
                                    </h5>
                                    <p class="text-muted mb-0 small">Remplissez les informations ci-dessous pour créer un nouveau devis</p>
                                </div>
                                <a href="devis.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form id="addDevisForm" method="POST" action="process_devis.php">
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
                                                    <option value="1">Ahmed Hassan - Tech Solutions SARL</option>
                                                    <option value="2">Fatima El Amrani - Boutique Moderne</option>
                                                    <option value="3">Youssef Benali - Import Export Co.</option>
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
                                        <textarea class="form-control" id="remarque" name="remarque" rows="4" placeholder="Remarques ou conditions particulières..."></textarea>
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
                                            <input type="text" class="form-control" id="devis_number" name="devis_number" value="DEV-2024-004" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_devis" class="form-label fw-semibold">Date du Devis</label>
                                            <input type="date" class="form-control" id="date_devis" name="date_devis" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="validite_jours" class="form-label fw-semibold">Validité (jours) <span class="text-danger">*</span></label>
                                            <select class="form-select" id="validite_jours" name="validite_jours" required>
                                                <option value="7">7 jours</option>
                                                <option value="15">15 jours</option>
                                                <option value="30" selected>30 jours</option>
                                                <option value="60">60 jours</option>
                                                <option value="90">90 jours</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="statut" class="form-label fw-semibold">Statut</label>
                                            <select class="form-select" id="statut" name="statut">
                                                <option value="en_attente" selected>En attente</option>
                                                <option value="accepté">Accepté</option>
                                                <option value="refusé">Refusé</option>
                                                <option value="expiré">Expiré</option>
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
                                                <i class="fas fa-save me-2"></i>Enregistrer le Devis
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" onclick="previewDevis()">
                                                <i class="fas fa-eye me-2"></i>Aperçu
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
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-semibold">Ordinateur Portable HP</div>
                                        <small class="text-muted">Laptop HP 15-dw3000 Intel Core i5</small>
                                    </div>
                                </td>
                                <td>8,500.00 DH</td>
                                <td><span class="badge bg-success">45</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="selectProduct(1, 'Ordinateur Portable HP', 8500.00)">
                                        Sélectionner
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-semibold">Smartphone Samsung</div>
                                        <small class="text-muted">Galaxy A54 5G 128GB</small>
                                    </div>
                                </td>
                                <td>3,200.00 DH</td>
                                <td><span class="badge bg-warning">8</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="selectProduct(2, 'Smartphone Samsung', 3200.00)">
                                        Sélectionner
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let productLineCounter = 0;

// Client data (would come from database)
const clientsData = {
    1: { name: 'Ahmed Hassan', company: 'Tech Solutions SARL', email: 'ahmed.hassan@techsol.ma', phone: '+212 6 12 34 56 78', address: 'Casablanca' },
    2: { name: 'Fatima El Amrani', company: 'Boutique Moderne', email: 'fatima@boutique-moderne.com', phone: '+212 6 87 65 43 21', address: 'Rabat' },
    3: { name: 'Youssef Benali', company: 'Import Export Co.', email: 'y.benali@importexport.ma', phone: '+212 6 99 88 77 66', address: 'Marrakech' }
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

// Add product line
function addProductLine() {
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
}

// Select product from modal
function selectProduct(productId, productName, price) {
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
                   value="1" 
                   min="1" 
                   onchange="calculateLineTotal(${productLineCounter})">
        </td>
        <td>
            <span class="fw-bold" id="lineTotal${productLineCounter}">${price.toFixed(2)} DH</span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeProductLine(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
    modal.hide();
    
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

// Preview devis
function previewDevis() {
    alert('Fonctionnalité d\'aperçu à implémenter');
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