
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
        <div class="user-menu">
            <div class="user-profile">
                <div class="user-avatar">ME</div>
                <div class="user-info">
                    <h6>Meriem Essahraoui</h6>
                    <span>Administrateur</span>
                </div>
            </div>
        </div>
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
                <div class="stat-value">€45,678</div>
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
    </div>
</div>

<?php include_once '../includes/scripts.php'; ?>
