const funcionInit = () => {
	if (!('geolocation' in navigator)) {
		// If geolocation is not available, disable action buttons and inform the user
		const $btnIniciarFail = document.querySelector('#btnIniciar');
		const $btnDetenerFail = document.querySelector('#btnDetener');
		const $messageFail = document.querySelector('#message');
		if ($btnIniciarFail) $btnIniciarFail.disabled = true;
		if ($btnDetenerFail) $btnDetenerFail.disabled = true;
		if ($messageFail) $messageFail.innerText = 'Tu navegador no soporta el acceso a la ubicación. Intenta con otro o usa la app móvil.';
		return;
	}

	let idWatcher = null;

	const $btnIniciar = document.querySelector("#btnIniciar"),
		$btnDetener = document.querySelector("#btnDetener"),
        $latitud = document.querySelector("#latitud"),
		$longitud = document.querySelector("#longitud"),
		$message = document.querySelector("#message"),
	// Prefer data-temp (set server-side), fallback to data-user
	$user = (document.body.getAttribute('data-temp') || document.body.getAttribute('data-user') || '').toLowerCase();	

	const onUbicacionConcedida = ubicacion => {
		const coordenadas = ubicacion.coords;

		// Default campus bounding box (tune values). Note longitude min should be the more negative value.
		if (coordenadas.latitude > 10.2244 && coordenadas.latitude < 10.2248 && coordenadas.longitude > -68.0066 && coordenadas.longitude < -68.0056) {
			$btnIniciar.disabled = false;
			$btnDetener.disabled = false;
			$latitud.innerText = coordenadas.latitude;
			$longitud.innerText = coordenadas.longitude;
			$message.innerText = '';
		} else {

			switch ($user) {

				// No unconditional bypasses: users must be inside defined areas to enable buttons.

				case 'jesus ramirez':
					if (coordenadas.latitude > 10.2214 && coordenadas.latitude < 10.2258 && coordenadas.longitude > -68.0075 && coordenadas.longitude < -68.0047) {
						$btnIniciar.disabled = false;
						$btnDetener.disabled = false;
						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;
						$message.innerText = '';
					} else {
						$btnIniciar.disabled = true; 
						$btnDetener.disabled = true; 
						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;
						$message.innerText = 'No se encuentra cerca del Colegio de Abogados del Estado Carabobo';
					}
					break;

				case 'francisco colina':
					if (coordenadas.latitude > 10.2214 && coordenadas.latitude < 10.2258 && coordenadas.longitude > -68.0075 && coordenadas.longitude < -68.0047) {
						$btnIniciar.disabled = false;
						$btnDetener.disabled = false;
						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;
						$message.innerText = '';
					} else {
						$btnIniciar.disabled = true; 
						$btnDetener.disabled = true; 
						$latitud.innerText = coordenadas.latitude;
						$longitud.innerText = coordenadas.longitude;
						$message.innerText = 'No se encuentra cerca del Colegio de Abogados del Estado Carabobo';
					}
					break;

				case 'angelo polgrossi':
					$btnIniciar.disabled = false;
					$btnDetener.disabled = false;
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
		// If permissions policy or browser blocks geolocation, show clear message and disable buttons
		if ($btnIniciar) $btnIniciar.disabled = true;
		if ($btnDetener) $btnDetener.disabled = true;
		$latitud.innerText = 'Error obteniendo ubicación: ' + err.message;
		$longitud.innerText = 'Error obteniendo ubicación: ' + err.message;
		$message.innerText = 'Activa la localización en tu navegador y permite el acceso cuando se solicite.';
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

	
	$btnIniciar.addEventListener('click', () => { window.location.href = 'assist1.php'; });
	$btnDetener.addEventListener('click', () => { window.location.href = 'assist2.php'; });

	

	$btnIniciar.disabled = true; 
	$btnDetener.disabled = true; 

	detenerWatcher();
	idWatcher = navigator.geolocation.watchPosition(onUbicacionConcedida, onErrorDeUbicacion, opcionesDeSolicitud);

};

document.addEventListener("DOMContentLoaded", funcionInit);