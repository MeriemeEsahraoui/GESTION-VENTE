
<?php include_once '../includes/head.php'; ?>
<?php include_once '../includes/header.php'; ?>


<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Header -->
    <div class="top-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Acceuil</h1>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card sales">
                <div class="stat-header">
                    <div class="stat-icon sales">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value">1,234</div>
                <div class="stat-label">Ventes ce mois</div>
            </div>

            <div class="stat-card revenue">
                <div class="stat-header">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">DHs45,678</div>
                <div class="stat-label">Chiffre d'affaires</div>
            </div>

            <div class="stat-card clients">
                <div class="stat-header">
                    <div class="stat-icon clients">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">856</div>
                <div class="stat-label">Clients actifs</div>
            </div>

            <div class="stat-card orders">
                <div class="stat-header">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <div class="stat-value">2,341</div>
                <div class="stat-label">Commandes totales</div>
            </div>
        </div>

        <!-- Latest Factures Section -->
        <div class="recent-activity mt-4">
            <div class="activity-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Dernières Factures
                    </h3>
                    <a href="factures.php" class="btn btn-sm btn-outline-primary">
                        Voir toutes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="latestFactures">
                            <!-- Factures will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.recent-activity {
    margin-top: 2rem;
}

.activity-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.card-title {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.table {
    margin: 0;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    color: #6c757d;
    font-weight: 600;
    padding: 1rem;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}

.btn-action {
    padding: 0.25rem 0.5rem;
    margin: 0 0.125rem;
    border-radius: 6px;
}
</style>

<script>
// Load latest factures on page load
document.addEventListener('DOMContentLoaded', function() {
    loadLatestFactures();
});

function loadLatestFactures() {
    // Mock data for demonstration - replace with actual PHP/AJAX call
    const mockFactures = [
        {
            numero: 'FAC-2024-001',
            client: 'ACME Corporation',
            date: '2024-12-15',
            montant: '15,750.00',
            statut: 'payee'
        },
        {
            numero: 'FAC-2024-002',
            client: 'Tech Solutions',
            date: '2024-12-14',
            montant: '8,900.50',
            statut: 'envoyee'
        },
        {
            numero: 'FAC-2024-003',
            client: 'Global Services',
            date: '2024-12-13',
            montant: '22,300.00',
            statut: 'en_retard'
        },
        {
            numero: 'FAC-2024-004',
            client: 'Innovation Ltd',
            date: '2024-12-12',
            montant: '12,450.75',
            statut: 'brouillon'
        },
        {
            numero: 'FAC-2024-005',
            client: 'Smart Business',
            date: '2024-12-10',
            montant: '6,200.00',
            statut: 'payee'
        }
    ];

    const tbody = document.getElementById('latestFactures');
    tbody.innerHTML = '';

    mockFactures.slice(0, 5).forEach(facture => {
        const statusBadge = getStatusBadge(facture.statut);
        
        const row = `
            <tr>
                <td><strong>${facture.numero}</strong></td>
                <td>${facture.client}</td>
                <td>${formatDate(facture.date)}</td>
                <td><strong>${facture.montant} DH</strong></td>
                <td>${statusBadge}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-action" onclick="viewFacture('${facture.numero}')" title="Voir">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary btn-action" onclick="downloadFacture('${facture.numero}')" title="Télécharger">
                        <i class="fas fa-download"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function getStatusBadge(statut) {
    const statusConfig = {
        'brouillon': { class: 'bg-secondary', text: 'Brouillon' },
        'envoyee': { class: 'bg-primary', text: 'Envoyée' },
        'payee': { class: 'bg-success', text: 'Payée' },
        'en_retard': { class: 'bg-danger', text: 'En retard' },
        'annulee': { class: 'bg-dark', text: 'Annulée' }
    };
    
    const config = statusConfig[statut] || { class: 'bg-secondary', text: 'Inconnu' };
    return `<span class="badge ${config.class}">${config.text}</span>`;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function viewFacture(numero) {
    // Redirect to facture detail page
    window.location.href = `facture_detail.php?numero=${numero}`;
}

function downloadFacture(numero) {
    // Implement facture download functionality
    alert(`Téléchargement de la facture ${numero}`);
}
</script>

<?php include_once '../includes/scripts.php'; ?>
