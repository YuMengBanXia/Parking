<?php
	//Tarjeta VISA para pruebas:4548810000000003 12/49 123
	//Tarjeta Mastercard para pruebas: 5576441563045037 12/49 123
    require_once __DIR__ . '/includes/config.php';
    require_once 'redsys/apiRedsys.php';

	// Se crea Objeto
	$miObj = new RedsysAPI;

	// Valores de entrada 
	$fuc="999008881";
	$terminal="1";
	$moneda="978";
	$trans="0";
	$urlOK="http://localhost/Parking/returnPago.php";//esto hay que cambiarlo para el VPS
    $urlKO="http://localhost/Parking/returnPago.php";
	$urlNotify = "http://localhost/Parking/redsys/notifyPago.php"; //Funcionalidad capada -> memoria

	$total = $_SESSION['pago_cantidad'];
	$codigo = $_SESSION['pago_id'];
	$ticket = \es\ucm\fdi\aw\ePark\SATicket::buscarCodigo($codigo);

	if ($total === null || empty($ticket)) {
		header('Location: index.php?error=acceso_denegado');
		exit;
	}
	$importe = floatval($total ?? 1.0); // Por defecto valor 1.0 porque el 0 da error por RedSys
    $amount = intval($importe * 100); // Redsys requiere el importe en céntimos, sin punto decimal

	//Como se pide un numero de 4 a 12
	// 1) Prefijo numérico: 8 dígitos con año(4)+mes(2)+día(2)
	$prefijo = date('YmdH'); 

	// 2) Sufijo aleatorio: 4 dígitos hex (2 bytes)
	$sufijo = strtoupper(bin2hex(random_bytes(2))); // e.g. "A3F9"

	// 3) Juntamos y limitamos a 12 caracteres
	$order  = substr($prefijo . $sufijo, 0, 12);

	
	// Se Rellenan los campos
	$miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
	$miObj->setParameter("DS_MERCHANT_ORDER",$order);
	$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
	$miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
	$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
	$miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
	$miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);
	$miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);
	$miObj->setParameter("DS_MERCHANT_MERCHANTURL",   $urlNotify);

	//Datos de configuración
	$version="HMAC_SHA256_V1";
	$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';//Clave recuperada de CANALES
	// Se generan los parámetros de la petición
	$request = "";
	$params = $miObj->createMerchantParameters();
	$signature = $miObj->createMerchantSignature($kc);
?>

    <!-- Envío automático del formulario -->
<form id="redsysForm" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">
    <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1" />
    <input type="hidden" name="Ds_MerchantParameters" value="<?= $params ?>" />
    <input type="hidden" name="Ds_Signature" value="<?= $signature ?>" />
</form>


<script>
    document.getElementById('redsysForm').submit();
</script>


