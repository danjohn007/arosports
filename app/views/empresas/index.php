<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Empresas</h1>
                <a href="<?php echo BASE_URL; ?>empresas/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear Empresa
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
    
    <!-- Empresas Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Empresas</h6>
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
                                    <th>Comisión</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($empresas)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <i class="bi bi-inbox text-muted"></i>
                                            <p class="text-muted mt-2">No hay empresas registradas.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($empresas as $empresa): ?>
                                        <tr>
                                            <td><?php echo $empresa['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($empresa['nombre']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($empresa['direccion']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($empresa['responsable']); ?></td>
                                            <td><?php echo htmlspecialchars($empresa['telefono']); ?></td>
                                            <td><?php echo htmlspecialchars($empresa['email']); ?></td>
                                            <td><?php echo number_format($empresa['comision_porcentaje'], 2); ?>%</td>
                                            <td>
                                                <?php if ($empresa['status'] === 'activo'): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo BASE_URL; ?>empresas/edit/<?php echo $empresa['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
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