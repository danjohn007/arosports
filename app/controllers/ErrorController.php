<?php
require_once 'BaseController.php';

class ErrorController extends BaseController {
    
    public function notFound() {
        http_response_code(404);
        $this->loadView('errors/404');
    }
    
    public function serverError() {
        http_response_code(500);
        $this->loadView('errors/500');
    }
}
?>