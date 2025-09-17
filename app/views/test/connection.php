<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Prueba de Conexión y Configuración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Esta página permite verificar que todos los componentes del sistema estén funcionando correctamente.
                    </div>
                    
                    <?php foreach ($tests as $testKey => $test): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1"><?php echo $test['name']; ?></h6>
                                        <p class="card-text mb-0"><?php echo htmlspecialchars($test['result']); ?></p>
                                    </div>
                                    <div>
                                        <?php if ($test['status'] === 'success'): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Exitoso
                                            </span>
                                        <?php elseif ($test['status'] === 'warning'): ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-triangle"></i> Advertencia
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Error
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="mt-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Información del Sistema</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
                                    <li><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></li>
                                    <li><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></li>
                                    <li><strong>Script Name:</strong> <?php echo $_SERVER['SCRIPT_NAME']; ?></li>
                                    <li><strong>Base URL:</strong> <?php echo BASE_URL; ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo BASE_URL; ?>login" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Ir al Login
                        </a>
                        <button class="btn btn-secondary" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar Tests
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>