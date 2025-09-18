<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Reservas</h1>
                <a href="<?php echo BASE_URL; ?>reservas/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Reserva
                </a>
            </div>
        </div>
    </div>
    
    <!-- Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($demo_mode) && $demo_mode): ?>
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> <strong>Modo Demo:</strong> La base de datos no está disponible. Para usar esta funcionalidad, configure MySQL y ejecute el archivo SQL incluido.
        </div>
    <?php endif; ?>
    
    <!-- Reservas Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Reservas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Cancha</th>
                                    <th>Deporte</th>
                                    <th>Cliente</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reservas)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <i class="bi bi-inbox text-muted"></i>
                                            <p class="text-muted mt-2">No hay reservas registradas.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reservas as $reserva): ?>
                                        <tr>
                                            <td><?php echo $reserva['id']; ?></td>
                                            <td>
                                                <?php 
                                                $fecha = new DateTime($reserva['fecha']);
                                                echo $fecha->format('d/m/Y'); 
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $reserva['hora_inicio']; ?> - <?php echo $reserva['hora_fin']; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($reserva['cancha']); ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($reserva['deporte']); ?></span>
                                            </td>
                                            <td>
                                                <?php if (isset($reserva['cliente_nombre'])): ?>
                                                    <strong><?php echo htmlspecialchars($reserva['cliente_nombre']); ?></strong><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($reserva['cliente_email']); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin asignar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>$<?php echo number_format($reserva['precio'], 2); ?></td>
                                            <td>
                                                <?php 
                                                switch($reserva['status']) {
                                                    case 'confirmada':
                                                        echo '<span class="badge bg-success">Confirmada</span>';
                                                        break;
                                                    case 'pendiente':
                                                        echo '<span class="badge bg-warning">Pendiente</span>';
                                                        break;
                                                    case 'cancelada':
                                                        echo '<span class="badge bg-danger">Cancelada</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">' . htmlspecialchars($reserva['status']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>