<?php
require_once 'BaseController.php';

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent reservations
        $recentReservations = $this->getRecentReservations();
        
        // Get monthly revenue data for charts
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        // Get revenue by type
        $revenueByType = $this->getRevenueByType();
        
        $this->loadView('dashboard/index', [
            'stats' => $stats,
            'recentReservations' => $recentReservations,
            'monthlyRevenue' => $monthlyRevenue,
            'revenueByType' => $revenueByType
        ]);
    }
    
    private function getDashboardStats() {
        $stats = [];
        
        // Total reservations today
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM reservas WHERE DATE(fecha_reserva) = CURDATE()");
        $stmt->execute();
        $stats['reservations_today'] = $stmt->fetch()['total'];
        
        // Total revenue this month
        $stmt = $this->db->prepare("SELECT SUM(precio_final) as total FROM reservas WHERE MONTH(fecha_reserva) = MONTH(CURDATE()) AND YEAR(fecha_reserva) = YEAR(CURDATE()) AND status_pago = 'pagado'");
        $stmt->execute();
        $stats['revenue_month'] = $stmt->fetch()['total'] ?? 0;
        
        // Total commissions this month
        $stmt = $this->db->prepare("SELECT SUM(comision) as total FROM reservas WHERE MONTH(fecha_reserva) = MONTH(CURDATE()) AND YEAR(fecha_reserva) = YEAR(CURDATE()) AND status_pago = 'pagado'");
        $stmt->execute();
        $stats['commissions_month'] = $stmt->fetch()['total'] ?? 0;
        
        // Active users
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE status = 'activo'");
        $stmt->execute();
        $stats['active_users'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    private function getRecentReservations() {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nombre as usuario_nombre, u.tipo_usuario,
                   CASE 
                       WHEN r.tipo_reserva = 'club' THEN c.nombre
                       WHEN r.tipo_reserva = 'fraccionamiento' THEN f.nombre
                       WHEN r.tipo_reserva = 'empresa' THEN e.nombre
                       ELSE 'Particular'
                   END as entidad_nombre
            FROM reservas r 
            JOIN usuarios u ON r.usuario_id = u.id
            LEFT JOIN clubes c ON r.tipo_reserva = 'club' AND r.entidad_id = c.id
            LEFT JOIN fraccionamientos f ON r.tipo_reserva = 'fraccionamiento' AND r.entidad_id = f.id
            LEFT JOIN empresas e ON r.tipo_reserva = 'empresa' AND r.entidad_id = e.id
            ORDER BY r.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function getMonthlyRevenue() {
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(fecha_reserva, '%Y-%m') as mes,
                SUM(precio_final) as total_ingresos,
                SUM(comision) as total_comisiones
            FROM reservas 
            WHERE status_pago = 'pagado' 
                AND fecha_reserva >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(fecha_reserva, '%Y-%m')
            ORDER BY mes ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function getRevenueByType() {
        $stmt = $this->db->prepare("
            SELECT 
                tipo_reserva,
                COUNT(*) as cantidad,
                SUM(precio_final) as total_ingresos,
                SUM(comision) as total_comisiones
            FROM reservas 
            WHERE status_pago = 'pagado' 
                AND MONTH(fecha_reserva) = MONTH(CURDATE()) 
                AND YEAR(fecha_reserva) = YEAR(CURDATE())
            GROUP BY tipo_reserva
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>