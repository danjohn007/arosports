<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Crear Usuario</h1>
                <a href="<?php echo BASE_URL; ?>users" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
    
    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <h6><i class="bi bi-exclamation-triangle"></i> Errores encontrados:</h6>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Create User Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Usuario</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>users/create">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required
                                       value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo de Usuario *</label>
                                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="usuario" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'usuario') ? 'selected' : ''; ?>>Usuario Regular</option>
                                    <option value="club" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'club') ? 'selected' : ''; ?>>Club</option>
                                    <option value="fraccionamiento" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'fraccionamiento') ? 'selected' : ''; ?>>Fraccionamiento</option>
                                    <option value="empresa" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'empresa') ? 'selected' : ''; ?>>Empresa</option>
                                    <option value="superadmin" <?php echo (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] === 'superadmin') ? 'selected' : ''; ?>>SuperAdmin</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Crear Usuario
                            </button>
                            <a href="<?php echo BASE_URL; ?>users" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">Tipos de Usuario</h6>
                    <ul class="list-unstyled mb-0">
                        <li><strong>SuperAdmin:</strong> Acceso completo al sistema</li>
                        <li><strong>Club:</strong> Gestión de club deportivo</li>
                        <li><strong>Fraccionamiento:</strong> Administrador de fraccionamiento</li>
                        <li><strong>Empresa:</strong> Representante corporativo</li>
                        <li><strong>Usuario Regular:</strong> Cliente particular</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        password.type = 'password';
        icon.className = 'bi bi-eye';
    }
});
</script>