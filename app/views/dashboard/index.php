<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Dashboard Financiero</h1>
                <div class="text-muted">
                    <i class="bi bi-calendar3"></i> <?php echo date('d/m/Y H:i'); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Reservas Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['reservations_today']); ?>
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
                                Ingresos Este Mes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($stats['revenue_month'], 2); ?>
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
                                Comisiones Este Mes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $<?php echo number_format($stats['commissions_month'], 2); ?>
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
                                Usuarios Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($stats['active_users']); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos Mensuales</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Revenue by Type Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ingresos por Tipo</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueByTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Reservations -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reservas Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Entidad</th>
                                    <th>Horario</th>
                                    <th>Cancha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentReservations)): ?>
                                    <?php foreach ($recentReservations as $reservation): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($reservation['fecha_reserva'])); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['usuario_nombre']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $reservation['tipo_reserva'] === 'club' ? 'primary' : 
                                                        ($reservation['tipo_reserva'] === 'empresa' ? 'success' : 
                                                        ($reservation['tipo_reserva'] === 'fraccionamiento' ? 'info' : 'secondary'));
                                                ?>">
                                                    <?php echo ucfirst($reservation['tipo_reserva']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($reservation['entidad_nombre']); ?></td>
                                            <td>
                                                <?php echo date('H:i', strtotime($reservation['hora_inicio'])); ?> - 
                                                <?php echo date('H:i', strtotime($reservation['hora_fin'])); ?>
                                            </td>
                                            <td><?php echo $reservation['cancha_numero']; ?></td>
                                            <td>$<?php echo number_format($reservation['precio_final'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $reservation['status_reserva'] === 'completada' ? 'success' : 
                                                        ($reservation['status_reserva'] === 'activa' ? 'warning' : 'danger');
                                                ?>">
                                                    <?php echo ucfirst($reservation['status_reserva']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay reservas recientes</td>
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
// Monthly Revenue Chart
const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
const monthlyData = <?php echo json_encode($monthlyRevenue); ?>;

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.mes),
        datasets: [{
            label: 'Ingresos',
            data: monthlyData.map(item => parseFloat(item.total_ingresos || 0)),
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4
        }, {
            label: 'Comisiones',
            data: monthlyData.map(item => parseFloat(item.total_comisiones || 0)),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
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
const typeCtx = document.getElementById('revenueByTypeChart').getContext('2d');
const typeData = <?php echo json_encode($revenueByType); ?>;

new Chart(typeCtx, {
    type: 'doughnut',
    data: {
        labels: typeData.map(item => item.tipo_reserva.charAt(0).toUpperCase() + item.tipo_reserva.slice(1)),
        datasets: [{
            data: typeData.map(item => parseFloat(item.total_ingresos || 0)),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0'
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