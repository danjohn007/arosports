    </main>
    
    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Footer -->
    <footer class="bg-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">Versión <?php echo APP_VERSION; ?> | Base URL: <?php echo BASE_URL; ?></small>
                </div>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>public/js/app.js"></script>
    
    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $jsFile): ?>
            <script src="<?php echo BASE_URL . $jsFile; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>