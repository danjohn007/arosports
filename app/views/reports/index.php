<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Reportes Financieros</h1>
                <a href="<?php echo BASE_URL; ?>reports/financial<?php echo !empty($_GET) ? '?' . http_build_query($_GET) : ''; ?>" class="btn btn-primary">
                    <i class="bi bi-graph-up"></i> Reporte Detallado
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>reports" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo htmlspecialchars($startDate); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo htmlspecialchars($endDate); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="user_type" class="form-label">Tipo de Usuario</label>
                            <select class="form-select" id="user_type" name="user_type">
                                <option value="">Todos los tipos</option>
                                <option value="club" <?php echo $userType === 'club' ? 'selected' : ''; ?>>Club</option>
                                <option value="fraccionamiento" <?php echo $userType === 'fraccionamiento' ? 'selected' : ''; ?>>Fraccionamiento</option>
                                <option value="empresa" <?php echo $userType === 'empresa' ? 'selected' : ''; ?>>Empresa</option>
                                <option value="particular" <?php echo $userType === 'particular' ? 'selected' : ''; ?>>Particular</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                            <a href="<?php echo BASE_URL; ?>reports" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reservas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($financialData['total_reservas']); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ingresos Netos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($financialData['ingresos_netos'], 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Comisiones
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($financialData['total_comisiones'], 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-percent text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ticket Promedio
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($financialData['ticket_promedio'], 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Daily Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos Diarios</h6>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Revenue by Type Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos por Tipo</h6>
                </div>
                <div class="card-body">
                    <canvas id="typeRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Tables -->
    <div class="row">
        <!-- Reservations by Type -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reservas por Tipo</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Ingresos</th>
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reservationsData)): ?>
                                    <?php foreach ($reservationsData as $data): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $data['tipo_reserva'] === 'club' ? 'primary' : 
                                                        ($data['tipo_reserva'] === 'empresa' ? 'success' : 
                                                        ($data['tipo_reserva'] === 'fraccionamiento' ? 'info' : 'secondary'));
                                                ?>">
                                                    <?php echo ucfirst($data['tipo_reserva']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo number_format($data['cantidad']); ?></td>
                                            <td>$<?php echo number_format($data['ingresos'], 2); ?></td>
                                            <td>$<?php echo number_format($data['promedio'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No hay datos disponibles</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Commission Analysis -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Análisis de Comisiones</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Entidad</th>
                                    <th>Reservas</th>
                                    <th>Comisiones</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($commissionAnalysis)): ?>
                                    <?php foreach ($commissionAnalysis as $commission): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <span class="badge bg-<?php 
                                                        echo $commission['tipo_reserva'] === 'club' ? 'primary' : 
                                                            ($commission['tipo_reserva'] === 'empresa' ? 'success' : 
                                                            ($commission['tipo_reserva'] === 'fraccionamiento' ? 'info' : 'secondary'));
                                                    ?>">
                                                        <?php echo ucfirst($commission['tipo_reserva']); ?>
                                                    </span>
                                                    <br>
                                                    <small><?php echo htmlspecialchars($commission['entidad_nombre']); ?></small>
                                                </div>
                                            </td>
                                            <td><?php echo number_format($commission['total_reservas']); ?></td>
                                            <td>$<?php echo number_format($commission['comisiones_totales'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?php echo number_format($commission['porcentaje_comision_promedio'], 1); ?>%
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No hay datos disponibles</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Daily Revenue Chart
const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
const dailyData = <?php echo json_encode($dailyRevenue); ?>;

new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: dailyData.map(item => item.fecha),
        datasets: [{
            label: 'Ingresos',
            data: dailyData.map(item => parseFloat(item.ingresos || 0)),
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Comisiones',
            data: dailyData.map(item => parseFloat(item.comisiones || 0)),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Revenue by Type Chart
const typeCtx = document.getElementById('typeRevenueChart').getContext('2d');
const typeData = <?php echo json_encode($reservationsData); ?>;

new Chart(typeCtx, {
    type: 'doughnut',
    data: {
        labels: typeData.map(item => item.tipo_reserva.charAt(0).toUpperCase() + item.tipo_reserva.slice(1)),
        datasets: [{
            data: typeData.map(item => parseFloat(item.ingresos || 0)),
            backgroundColor: [
                '#FF6384',
                '#36A2EB', 
                '#FFCE56',
                '#4BC0C0',
                '#9966FF'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>