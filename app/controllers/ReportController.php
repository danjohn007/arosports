<?php
require_once 'BaseController.php';

class ReportController extends BaseController {
    
    public function index() {
        $this->requireSuperAdmin();
        
        // Default date range (current month)
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $userType = isset($_GET['user_type']) ? $_GET['user_type'] : '';
        
        // Get financial data
        $financialData = $this->getFinancialData($startDate, $endDate, $userType);
        
        // Get reservations data
        $reservationsData = $this->getReservationsData($startDate, $endDate, $userType);
        
        // Get daily revenue chart data
        $dailyRevenue = $this->getDailyRevenue($startDate, $endDate, $userType);
        
        // Get commission analysis
        $commissionAnalysis = $this->getCommissionAnalysis($startDate, $endDate, $userType);
        
        $this->loadView('reports/index', [
            'title' => 'Reportes Financieros',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'userType' => $userType,
            'financialData' => $financialData,
            'reservationsData' => $reservationsData,
            'dailyRevenue' => $dailyRevenue,
            'commissionAnalysis' => $commissionAnalysis
        ]);
    }
    
    public function financial() {
        $this->requireSuperAdmin();
        
        // Enhanced financial report with more detailed analysis
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $userType = isset($_GET['user_type']) ? $_GET['user_type'] : '';
        
        // Get detailed financial metrics
        $metrics = $this->getDetailedFinancialMetrics($startDate, $endDate, $userType);
        
        // Get top performing entities
        $topEntities = $this->getTopPerformingEntities($startDate, $endDate);
        
        // Get hourly distribution
        $hourlyDistribution = $this->getHourlyDistribution($startDate, $endDate, $userType);
        
        // Get payment methods distribution
        $paymentMethods = $this->getPaymentMethodsDistribution($startDate, $endDate, $userType);
        
        $this->loadView('reports/financial', [
            'title' => 'Reporte Financiero Detallado',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'userType' => $userType,
            'metrics' => $metrics,
            'topEntities' => $topEntities,
            'hourlyDistribution' => $hourlyDistribution,
            'paymentMethods' => $paymentMethods
        ]);
    }
    
    private function getFinancialData($startDate, $endDate, $userType = '') {
        $whereClause = "WHERE r.fecha_reserva BETWEEN ? AND ? AND r.status_pago = 'pagado'";
        $params = [$startDate, $endDate];
        
        if (!empty($userType)) {
            $whereClause .= " AND r.tipo_reserva = ?";
            $params[] = $userType;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_reservas,
                SUM(r.precio_total) as ingresos_brutos,
                SUM(r.descuento) as total_descuentos,
                SUM(r.comision) as total_comisiones,
                SUM(r.precio_final) as ingresos_netos,
                AVG(r.precio_final) as ticket_promedio
            FROM reservas r
            $whereClause
        ");
        
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    private function getReservationsData($startDate, $endDate, $userType = '') {
        $whereClause = "WHERE r.fecha_reserva BETWEEN ? AND ?";
        $params = [$startDate, $endDate];
        
        if (!empty($userType)) {
            $whereClause .= " AND r.tipo_reserva = ?";
            $params[] = $userType;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                r.tipo_reserva,
                COUNT(*) as cantidad,
                SUM(r.precio_final) as ingresos,
                SUM(r.comision) as comisiones,
                AVG(r.precio_final) as promedio
            FROM reservas r
            $whereClause
            GROUP BY r.tipo_reserva
            ORDER BY ingresos DESC
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getDailyRevenue($startDate, $endDate, $userType = '') {
        $whereClause = "WHERE r.fecha_reserva BETWEEN ? AND ? AND r.status_pago = 'pagado'";
        $params = [$startDate, $endDate];
        
        if (!empty($userType)) {
            $whereClause .= " AND r.tipo_reserva = ?";
            $params[] = $userType;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                DATE(r.fecha_reserva) as fecha,
                SUM(r.precio_final) as ingresos,
                SUM(r.comision) as comisiones,
                COUNT(*) as reservas
            FROM reservas r
            $whereClause
            GROUP BY DATE(r.fecha_reserva)
            ORDER BY fecha ASC
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getCommissionAnalysis($startDate, $endDate, $userType = '') {
        $whereClause = "WHERE r.fecha_reserva BETWEEN ? AND ? AND r.status_pago = 'pagado'";
        $params = [$startDate, $endDate];
        
        if (!empty($userType)) {
            $whereClause .= " AND r.tipo_reserva = ?";
            $params[] = $userType;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                r.tipo_reserva,
                CASE 
                    WHEN r.tipo_reserva = 'club' THEN c.nombre
                    WHEN r.tipo_reserva = 'fraccionamiento' THEN f.nombre
                    WHEN r.tipo_reserva = 'empresa' THEN e.nombre
                    ELSE 'Particular'
                END as entidad_nombre,
                COUNT(*) as total_reservas,
                SUM(r.precio_final) as ingresos_totales,
                SUM(r.comision) as comisiones_totales,
                AVG(r.comision / r.precio_final * 100) as porcentaje_comision_promedio
            FROM reservas r
            LEFT JOIN clubes c ON r.tipo_reserva = 'club' AND r.entidad_id = c.id
            LEFT JOIN fraccionamientos f ON r.tipo_reserva = 'fraccionamiento' AND r.entidad_id = f.id
            LEFT JOIN empresas e ON r.tipo_reserva = 'empresa' AND r.entidad_id = e.id
            $whereClause
            GROUP BY r.tipo_reserva, r.entidad_id
            ORDER BY comisiones_totales DESC
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getDetailedFinancialMetrics($startDate, $endDate, $userType = '') {
        // Previous period comparison
        $periodDiff = (strtotime($endDate) - strtotime($startDate)) / (24 * 3600);
        $prevStartDate = date('Y-m-d', strtotime($startDate . " -$periodDiff days"));
        $prevEndDate = date('Y-m-d', strtotime($endDate . " -$periodDiff days"));
        
        $current = $this->getFinancialData($startDate, $endDate, $userType);
        $previous = $this->getFinancialData($prevStartDate, $prevEndDate, $userType);
        
        return [
            'current' => $current,
            'previous' => $previous,
            'growth' => [
                'reservas' => $this->calculateGrowth($previous['total_reservas'], $current['total_reservas']),
                'ingresos' => $this->calculateGrowth($previous['ingresos_netos'], $current['ingresos_netos']),
                'comisiones' => $this->calculateGrowth($previous['total_comisiones'], $current['total_comisiones']),
                'ticket_promedio' => $this->calculateGrowth($previous['ticket_promedio'], $current['ticket_promedio'])
            ]
        ];
    }
    
    private function getTopPerformingEntities($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                r.tipo_reserva,
                CASE 
                    WHEN r.tipo_reserva = 'club' THEN c.nombre
                    WHEN r.tipo_reserva = 'fraccionamiento' THEN f.nombre
                    WHEN r.tipo_reserva = 'empresa' THEN e.nombre
                    ELSE 'Particulares'
                END as nombre,
                COUNT(*) as total_reservas,
                SUM(r.precio_final) as ingresos_totales,
                SUM(r.comision) as comisiones_generadas
            FROM reservas r
            LEFT JOIN clubes c ON r.tipo_reserva = 'club' AND r.entidad_id = c.id
            LEFT JOIN fraccionamientos f ON r.tipo_reserva = 'fraccionamiento' AND r.entidad_id = f.id
            LEFT JOIN empresas e ON r.tipo_reserva = 'empresa' AND r.entidad_id = e.id
            WHERE r.fecha_reserva BETWEEN ? AND ? AND r.status_pago = 'pagado'
            GROUP BY r.tipo_reserva, r.entidad_id
            ORDER BY ingresos_totales DESC
            LIMIT 10
        ");
        
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    private function getHourlyDistribution($startDate, $endDate, $userType = '') {
        $whereClause = "WHERE r.fecha_reserva BETWEEN ? AND ? AND r.status_pago = 'pagado'";
        $params = [$startDate, $endDate];
        
        if (!empty($userType)) {
            $whereClause .= " AND r.tipo_reserva = ?";
            $params[] = $userType;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                HOUR(r.hora_inicio) as hora,
                COUNT(*) as reservas,
                SUM(r.precio_final) as ingresos
            FROM reservas r
            $whereClause
            GROUP BY HOUR(r.hora_inicio)
            ORDER BY hora ASC
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function getPaymentMethodsDistribution($startDate, $endDate, $userType = '') {
        $whereClause = "WHERE r.fecha_reserva BETWEEN ? AND ? AND r.status_pago = 'pagado'";
        $params = [$startDate, $endDate];
        
        if (!empty($userType)) {
            $whereClause .= " AND r.tipo_reserva = ?";
            $params[] = $userType;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                r.metodo_pago,
                COUNT(*) as cantidad,
                SUM(r.precio_final) as total
            FROM reservas r
            $whereClause
            GROUP BY r.metodo_pago
            ORDER BY total DESC
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function calculateGrowth($previous, $current) {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }
}
?>