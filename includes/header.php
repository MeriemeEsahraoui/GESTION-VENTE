<?php
// Get current page name for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Function to check if menu item should be active
function isActive($page, $current) {
    if ($page === $current) return 'active';
    
    // Handle related pages
    switch($current) {
        case 'client.php':
        case 'edit_client.php':
            return ($page === 'clients.php') ? 'active' : '';
        case 'produit.php':
        case 'edit_produit.php':
            return ($page === 'produits.php') ? 'active' : '';
        case 'add_devis.php':
        case 'devis_detail.php':
        case 'edit_devis.php':
            return ($page === 'devis.php') ? 'active' : '';
        case 'add_bon_commande.php':
        case 'bon_commande_detail.php':
        case 'edit_bon_commande.php':
            return ($page === 'bons_commande.php') ? 'active' : '';
        case 'add_commande.php':
        case 'commande_detail.php':
        case 'edit_commande.php':
            return ($page === 'commandes.php') ? 'active' : '';
        case 'add_utilisateur.php':
        case 'edit_utilisateur.php':
            return ($page === 'utilisateurs.php') ? 'active' : '';
        default:
            return '';
    }
}
?>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="brand-text">VENTE</div>
    </div>

    <div class="sidebar-user">
        <div class="user-profile-sidebar">
            <div class="user-avatar-sidebar">ME</div>
            <div class="user-info-sidebar">
                <h6>Meriem Essahraoui</h6>
                <span>Administrateur</span>
            </div>
        </div>
        <div class="user-actions">
            <button class="btn btn-sm btn-outline-light" onclick="logout()" title="Déconnexion">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="acceuil.php" class="<?php echo isActive('acceuil.php', $current_page); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Acceuil</span>
            </a>
        </li>
        <li>
            <a href="clients.php" class="<?php echo isActive('clients.php', $current_page); ?>">
                <i class="fas fa-users"></i>
                <span>Clients</span>
            </a>
        </li>
        <li>
            <a href="produits.php" class="<?php echo isActive('produits.php', $current_page); ?>">
                <i class="fas fa-box"></i>
                <span>Produits</span>
            </a>
        </li>
        <li>
            <a href="devis.php" class="<?php echo isActive('devis.php', $current_page); ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Devis</span>
            </a>
        </li>
        <li>
            <a href="bons_commande.php" class="<?php echo isActive('bons_commande.php', $current_page); ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Bons de commande</span>
            </a>
        </li>
        <li>
            <a href="commandes.php" class="<?php echo isActive('commandes.php', $current_page); ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Factures</span>
            </a>
        </li>
        <li>
            <a href="utilisateurs.php" class="<?php echo isActive('utilisateurs.php', $current_page); ?>">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
        </li>
    </ul>

    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
</div>