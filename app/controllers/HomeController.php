<?php
require_once 'BaseController.php';

class HomeController extends BaseController {
    
    public function index() {
        // If user is logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        } else {
            $this->redirect('login');
        }
    }
}
?>