<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Clubes</h1>
                <a href="<?php echo BASE_URL; ?>clubs/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear Club
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
    
    <!-- Clubs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Clubes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Responsable</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Comisión %</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($clubs)): ?>
                                    <?php foreach ($clubs as $club): ?>
                                        <tr>
                                            <td><?php echo $club['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($club['nombre']); ?></strong>
                                                <?php if (!empty($club['direccion'])): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($club['direccion']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($club['responsable'] ?: '-'); ?></td>
                                            <td><?php echo htmlspecialchars($club['telefono'] ?: '-'); ?></td>
                                            <td><?php echo htmlspecialchars($club['email'] ?: '-'); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo number_format($club['comision_porcentaje'], 1); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $club['status'] === 'activo' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($club['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo BASE_URL; ?>clubs/edit/<?php echo $club['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <?php if (isset($demo_mode) && $demo_mode): ?>
                                                No hay datos disponibles (modo demo sin base de datos)
                                            <?php else: ?>
                                                No hay clubes registrados
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!isset($demo_mode) || !$demo_mode): ?>
    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Clubes Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count(array_filter($clubs, function($c) { return $c['status'] === 'activo'; })); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
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
                                Comisión Promedio
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                if (!empty($clubs)) {
                                    $avgComision = array_reduce($clubs, function($carry, $club) { 
                                        return $carry + $club['comision_porcentaje']; 
                                    }, 0) / count($clubs);
                                    echo number_format($avgComision, 1) . '%';
                                } else {
                                    echo '0%';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-percent text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>