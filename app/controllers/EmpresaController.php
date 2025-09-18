<?php
require_once 'BaseController.php';

class EmpresaController extends BaseController {
    
    public function index() {
        $this->requireSuperAdmin();
        
        if (!$this->db) {
            $this->loadView('empresas/index', [
                'title' => 'Gestión de Empresas',
                'empresas' => [],
                'demo_mode' => true
            ]);
            return;
        }
        
        // Get all empresas
        $stmt = $this->db->prepare("
            SELECT id, nombre, direccion, telefono, email, responsable, comision_porcentaje, status, created_at 
            FROM empresas 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $empresas = $stmt->fetchAll();
        
        $this->loadView('empresas/index', [
            'title' => 'Gestión de Empresas',
            'empresas' => $empresas
        ]);
    }
    
    public function create() {
        $this->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $email = $this->sanitizeInput($_POST['email']);
            $responsable = $this->sanitizeInput($_POST['responsable']);
            $comision = floatval($_POST['comision_porcentaje']);
            
            if (empty($nombre) || empty($direccion) || empty($responsable)) {
                $error = 'Todos los campos obligatorios deben completarse';
            } else {
                if ($this->db) {
                    $stmt = $this->db->prepare("
                        INSERT INTO empresas (nombre, direccion, telefono, email, responsable, comision_porcentaje) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    if ($stmt->execute([$nombre, $direccion, $telefono, $email, $responsable, $comision])) {
                        $this->redirect('empresas');
                    } else {
                        $error = 'Error al crear la empresa';
                    }
                } else {
                    $error = 'Base de datos no disponible';
                }
            }
        }
        
        $this->loadView('empresas/create', [
            'title' => 'Nueva Empresa',
            'error' => isset($error) ? $error : null
        ]);
    }
    
    public function edit($id) {
        $this->requireSuperAdmin();
        
        if (!$this->db) {
            $this->redirect('empresas');
            return;
        }
        
        // Get empresa data
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE id = ?");
        $stmt->execute([$id]);
        $empresa = $stmt->fetch();
        
        if (!$empresa) {
            $this->redirect('empresas');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $email = $this->sanitizeInput($_POST['email']);
            $responsable = $this->sanitizeInput($_POST['responsable']);
            $comision = floatval($_POST['comision_porcentaje']);
            $status = $_POST['status'];
            
            if (empty($nombre) || empty($direccion) || empty($responsable)) {
                $error = 'Todos los campos obligatorios deben completarse';
            } else {
                $stmt = $this->db->prepare("
                    UPDATE empresas 
                    SET nombre = ?, direccion = ?, telefono = ?, email = ?, responsable = ?, comision_porcentaje = ?, status = ?
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$nombre, $direccion, $telefono, $email, $responsable, $comision, $status, $id])) {
                    $this->redirect('empresas');
                } else {
                    $error = 'Error al actualizar la empresa';
                }
            }
        }
        
        $this->loadView('empresas/edit', [
            'title' => 'Editar Empresa',
            'empresa' => $empresa,
            'error' => isset($error) ? $error : null
        ]);
    }
}
?>