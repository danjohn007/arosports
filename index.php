<?php
// Session configuration - must be set before session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

session_start();
require_once 'config/config.php';
require_once 'config/database.php';

// Simple router
class Router {
    private $routes = [];
    
    public function addRoute($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }
    
    public function route($url) {
        // Clean URL
        $url = trim($url, '/');
        $url = strtok($url, '?'); // Remove query parameters
        
        // Default route
        if (empty($url)) {
            $url = 'home';
        }
        
        // Check for exact match
        if (isset($this->routes[$url])) {
            return $this->routes[$url];
        }
        
        // Check for parameterized routes
        foreach ($this->routes as $route => $action) {
            if (strpos($route, '{') !== false) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    return array_merge($action, ['params' => array_slice($matches, 1)]);
                }
            }
        }
        
        // 404 Not Found
        return ['controller' => 'ErrorController', 'action' => 'notFound'];
    }
}

// Initialize router
$router = new Router();

// Define routes
$router->addRoute('home', 'HomeController', 'index');
$router->addRoute('login', 'AuthController', 'login');
$router->addRoute('logout', 'AuthController', 'logout');
$router->addRoute('dashboard', 'DashboardController', 'index');
$router->addRoute('users', 'UserController', 'index');
$router->addRoute('users/create', 'UserController', 'create');
$router->addRoute('users/edit/{id}', 'UserController', 'edit');
$router->addRoute('users/delete/{id}', 'UserController', 'delete');
$router->addRoute('clubs', 'ClubController', 'index');
$router->addRoute('clubs/create', 'ClubController', 'create');
$router->addRoute('clubs/edit/{id}', 'ClubController', 'edit');
$router->addRoute('fraccionamientos', 'FraccionamientoController', 'index');
$router->addRoute('fraccionamientos/create', 'FraccionamientoController', 'create');
$router->addRoute('fraccionamientos/edit/{id}', 'FraccionamientoController', 'edit');
$router->addRoute('empresas', 'EmpresaController', 'index');
$router->addRoute('empresas/create', 'EmpresaController', 'create');
$router->addRoute('empresas/edit/{id}', 'EmpresaController', 'edit');
$router->addRoute('reservas', 'ReservaController', 'index');
$router->addRoute('reservas/create', 'ReservaController', 'create');
$router->addRoute('reports', 'ReportController', 'index');
$router->addRoute('reports/financial', 'ReportController', 'financial');
$router->addRoute('test-connection', 'TestController', 'connection');

// Get current URL
$currentUrl = $_SERVER['REQUEST_URI'];
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$currentUrl = str_replace($basePath, '', $currentUrl);

// Handle direct access to index.php
if ($currentUrl === '/index.php' || $currentUrl === 'index.php') {
    $currentUrl = '';
}

// Route the request
$route = $router->route($currentUrl);

// Load controller
$controllerName = $route['controller'];
$actionName = $route['action'];
$params = isset($route['params']) ? $route['params'] : [];

$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        if (method_exists($controller, $actionName)) {
            call_user_func_array([$controller, $actionName], $params);
        } else {
            // Method not found
            require_once 'app/controllers/ErrorController.php';
            $errorController = new ErrorController();
            $errorController->notFound();
        }
    } else {
        // Controller class not found
        require_once 'app/controllers/ErrorController.php';
        $errorController = new ErrorController();
        $errorController->notFound();
    }
} else {
    // Controller file not found
    require_once 'app/controllers/ErrorController.php';
    $errorController = new ErrorController();
    $errorController->notFound();
}
?>