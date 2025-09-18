<?php
require_once 'BaseController.php';

class ReservaController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        if (!$this->db) {
            $this->loadView('reservas/index', [
                'title' => 'Gestión de Reservas',
                'reservas' => [],
                'demo_mode' => true
            ]);
            return;
        }
        
        // Get all reservas
        $stmt = $this->db->prepare("
            SELECT r.id, r.fecha, r.hora_inicio, r.hora_fin, r.cancha, r.deporte, r.precio, r.status, r.created_at,
                   u.nombre as cliente_nombre, u.email as cliente_email
            FROM reservas r
            LEFT JOIN usuarios u ON r.cliente_id = u.id
            ORDER BY r.fecha DESC, r.hora_inicio DESC
        ");
        $stmt->execute();
        $reservas = $stmt->fetchAll();
        
        $this->loadView('reservas/index', [
            'title' => 'Gestión de Reservas',
            'reservas' => $reservas
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $fecha = $_POST['fecha'];
            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin = $_POST['hora_fin'];
            $cancha = $this->sanitizeInput($_POST['cancha']);
            $deporte = $this->sanitizeInput($_POST['deporte']);
            $precio = floatval($_POST['precio']);
            $cliente_id = intval($_POST['cliente_id']);
            
            if (empty($fecha) || empty($hora_inicio) || empty($hora_fin) || empty($cancha) || empty($deporte)) {
                $error = 'Todos los campos obligatorios deben completarse';
            } else {
                if ($this->db) {
                    $stmt = $this->db->prepare("
                        INSERT INTO reservas (fecha, hora_inicio, hora_fin, cancha, deporte, precio, cliente_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    if ($stmt->execute([$fecha, $hora_inicio, $hora_fin, $cancha, $deporte, $precio, $cliente_id])) {
                        $this->redirect('reservas');
                    } else {
                        $error = 'Error al crear la reserva';
                    }
                } else {
                    $error = 'Base de datos no disponible';
                }
            }
        }
        
        // Get available clients
        $clientes = [];
        if ($this->db) {
            $stmt = $this->db->prepare("SELECT id, nombre, email FROM usuarios WHERE status = 'activo' ORDER BY nombre");
            $stmt->execute();
            $clientes = $stmt->fetchAll();
        }
        
        $this->loadView('reservas/create', [
            'title' => 'Nueva Reserva',
            'clientes' => $clientes,
            'error' => isset($error) ? $error : null
        ]);
    }
}
?>