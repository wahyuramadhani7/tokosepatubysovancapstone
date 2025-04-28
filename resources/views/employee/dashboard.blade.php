@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <div class="nav-logo">
            <img src="path-to-your-logo.svg" alt="Logo">
        </div>
        <div class="nav-links">
            <a href="#" class="active">DASHBOARD</a>
            <a href="#">Inventory</a>
            <a href="#">Transaksi</a>
            <a href="#">Laporan</a>
        </div>
    </div>

    <!-- Hero Section with Background -->
    <div class="hero-section">
        <div class="brand-tag">
            <h2>@SEPATUBYSOVAN</h2>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dashboard-content">
        <!-- Daily Report Section -->
        <div class="report-section">
            <h2 class="section-title">LAPORAN HARIAN</h2>
            
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="path-to-shoe-icon.svg" alt="Product Icon">
                    </div>
                    <div class="stat-info">
                        <h3>Total Produk</h3>
                        <div class="stat-value">--</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="path-to-user-icon.svg" alt="User Icon">
                    </div>
                    <div class="stat-info">
                        <h3>Pengunjung Hari Ini</h3>
                        <div class="stat-value">--</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <img src="path-to-stock-icon.svg" alt="Stock Icon">
                    </div>
                    <div class="stat-info">
                        <h3>Total Stok</h3>
                        <div class="stat-value">--</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-container pie-chart">
                <h3 class="chart-title">Produk Terlaris</h3>
                <div class="chart" id="productsPieChart"></div>
                <div class="chart-legends">
                    <div class="legend-item"></div>
                    <div class="legend-item"></div>
                    <div class="legend-item"></div>
                </div>
            </div>
            
            <div class="chart-container line-chart">
                <h3 class="chart-title">Grafik Pengunjung Mingguan</h3>
                <p class="chart-subtitle">Laporan Pengunjung Selama Seminggu</p>
                <div class="chart" id="weeklyVisitorsChart"></div>
            </div>
        </div>

        <!-- Transactions Detail Section -->
        <div class="transactions-section">
            <h3 class="section-title">Detail Transaksi</h3>
            <div class="transactions-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Transaction data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f5f5f5;
    }
    
    .dashboard-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Top Navigation */
    .top-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #212121;
        padding: 15px 30px;
        color: white;
    }
    
    .nav-logo img {
        height: 30px;
    }
    
    .nav-links {
        display: flex;
        gap: 30px;
    }
    
    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }
    
    .nav-links a.active {
        font-weight: bold;
    }
    
    /* Hero Section */
    .hero-section {
        height: 180px;
        background-color: #e0e0e0;
        position: relative;
        /* Replace with your background image */
        background-image: url('path-to-your-background.jpg');
        background-size: cover;
        background-position: center;
    }
    
    .brand-tag {
        position: absolute;
        left: 40px;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.8);
        padding: 10px 20px;
        border-radius: 8px;
    }
    
    .brand-tag h2 {
        color: #ff5722;
        font-size: 18px;
    }
    
    /* Report Section */
    .report-section {
        margin-top: 20px;
    }
    
    .section-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .stats-cards {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background-color: #fff;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        flex: 1;
        border: 1px solid #ddd;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        background-color: #ff5722;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .stat-icon img {
        width: 30px;
        height: 30px;
    }
    
    .stat-info h3 {
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
    }
    
    .stat-value {
        font-size: 22px;
        font-weight: bold;
        background-color: #333;
        color: white;
        padding: 5px 15px;
        border-radius: 5px;
    }
    
    /* Charts Section */
    .charts-section {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .chart-container {
        flex: 1;
        background-color: #333;
        border-radius: 10px;
        padding: 20px;
        color: white;
    }
    
    .chart-title {
        font-size: 18px;
        margin-bottom: 10px;
    }
    
    .chart-subtitle {
        font-size: 12px;
        color: #aaa;
        margin-bottom: 15px;
    }
    
    .chart {
        height: 200px;
        width: 100%;
        background-color: #444;
        border-radius: 8px;
    }
    
    .chart-legends {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 15px;
    }
    
    .legend-item {
        height: 10px;
        background-color: #555;
        border-radius: 3px;
    }
    
    /* Transactions Section */
    .transactions-section {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .transactions-table {
        width: 100%;
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stats-cards, .charts-section {
            flex-direction: column;
        }
        
        .nav-links {
            gap: 15px;
        }
    }
</style>

<script>
    // Placeholder for chart libraries
    // You would need to include Chart.js or another charting library
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts here
        // This is just a placeholder. You'll need to implement actual charts
        console.log('Dashboard loaded. Charts should be initialized here.');
    });
</script>
@endsection