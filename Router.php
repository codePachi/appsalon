<?php

	namespace MVC;

	class Router{
		public array $getRoutes = [];
		public array $postRoutes = [];

		public function get($url, $fn){
			$this->getRoutes[$url] = $fn;
		}

		public function post($url, $fn){
			$this->postRoutes[$url] = $fn;
		}

		public function comprobarRutas(){
			isSession();
      
			$currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
			$method = $_SERVER['REQUEST_METHOD'];

			if($method === 'GET'){
				$fn = $this->getRoutes[$currentUrl] ?? null;
			}else{
				$fn = $this->postRoutes[$currentUrl] ?? null;
			}

			if($fn){
				// Call user fn va a llamar una función cuando no sabemos cual sera
				call_user_func($fn, $this);
			} else {
				echo "Página No Encontrada o Ruta no válida";
			}
		}

		public function render($view, $datos = []){
			foreach($datos as $key => $value){
				$$key = $value;  
			}

			ob_start(); // Almacenamiento en memoria durante un momento...

			include_once __DIR__ . "/views/$view.php";
			$contenido = ob_get_clean(); // Limpia el Buffer
			include_once __DIR__ . '/views/layout.php';
		}
	}
?>