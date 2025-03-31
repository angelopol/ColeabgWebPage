const funcionInit = () => {
	if (!"geolocation" in navigator) {
		return alert("Tu navegador no soporta el acceso a la ubicación. Intenta con otro");
	}

	let idWatcher = null;

	const $btnIniciar = document.querySelector("#btnIniciar"),
		$btnDetener = document.querySelector("#btnDetener"),
        $latitud = document.querySelector("#latitud"),
		$longitud = document.querySelector("#longitud"),
		$message = document.querySelector("#message"),
		$user = document.querySelector('body').getAttribute('data-temp');

	

	const onUbicacionConcedida = ubicacion => {
		const coordenadas = ubicacion.coords;

		if (coordenadas.latitude > 10.2244 && coordenadas.latitude < 10.2248 && coordenadas.longitude < -68.0056 && coordenadas.longitude > -68.0066) {

			$btnIniciar.disabled = false;
			$btnDetener.disabled = false;

			$latitud.innerText = coordenadas.latitude;
			$longitud.innerText = coordenadas.longitude;

			$message.innerText = "";

		} else {

			switch ($user) {

				case 'gilda sosa':

					$btnIniciar.disabled = false;
					$btnDetener.disabled = false;
		
					$latitud.innerText = coordenadas.latitude;
					$longitud.innerText = coordenadas.longitude;
		
					$message.innerText = "";
					
					break;

				case 'Jesus ramirez':

					if (coordenadas.latitude > 10.2214 && coordenadas.latitude < 10.2258 && coordenadas.longitude < -68.0047 && coordenadas.longitude > -68.0075) {

						$btnIniciar.disabled = false;
						$btnDetener.disabled = false;
			
						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;
			
						$message.innerText = "";
					
					} else {

						$btnIniciar.disabled = true; 
						$btnDetener.disabled = true; 

						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;

						$message.innerText = "No se encuentra cerca del Colegio de Abogados del Estado Carabobo";

					}
					
					break;

				case 'Francisco colina':

					if (coordenadas.latitude > 10.2214 && coordenadas.latitude < 10.2258 && coordenadas.longitude < -68.0047 && coordenadas.longitude > -68.0075) {

						$btnIniciar.disabled = false;
						$btnDetener.disabled = false;
			
						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;
			
						$message.innerText = "";
					
					} else {

						$btnIniciar.disabled = true; 
						$btnDetener.disabled = true; 

						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;

						$message.innerText = "No se encuentra cerca del Colegio de Abogados del Estado Carabobo";

					}
					
					break;
			
				default:

					$btnIniciar.disabled = true; 
					$btnDetener.disabled = true; 

					$latitud.innerText = coordenadas.latitude;
					$longitud.innerText = coordenadas.longitude;

					$message.innerText = "No se encuentra cerca de las oficinas del Colegio de Abogados del Estado Carabobo";

					break;
			}		
			
		}
	}

    const onErrorDeUbicacion = err => {

		$latitud.innerText = "Error obteniendo ubicación: " + err.message;
		$longitud.innerText = "Error obteniendo ubicación: " + err.message;
	}

	const opcionesDeSolicitud = {
		enableHighAccuracy: true, // Alta precisión
		maximumAge: 0, // No queremos caché
		timeout: 5000 // Esperar solo 5 segundos
	};

	const detenerWatcher = () => {
		if (idWatcher) {
			navigator.geolocation.clearWatch(idWatcher);
			idWatcher = null;
		}
	}

	
	$btnIniciar.addEventListener("click", () => {
		window.location.href = "assist1.php";
	});

	$btnDetener.addEventListener("click", () => {
        window.location.href = "assist2.php";
    });

	

	$btnIniciar.disabled = true; 
	$btnDetener.disabled = true; 

	detenerWatcher();
	idWatcher = navigator.geolocation.watchPosition(onUbicacionConcedida, onErrorDeUbicacion, opcionesDeSolicitud);

};

document.addEventListener("DOMContentLoaded", funcionInit);