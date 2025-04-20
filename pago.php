//Tarjeta VISA para pruebas:4548810000000003 12/49 123
//Tarjeta Mastercard para pruebas: 5576441563045037 12/49 123

<?php
    require_once __DIR__ . '/includes/config.php';
    require_once 'redsys/apiRedsys.php';

	// Se crea Objeto
	$miObj = new RedsysAPI;

	// Valores de entrada 
	$fuc="999008881";
	$terminal="1";
	$moneda="978";
	$trans="0";
	$url="";
	$urlOK="http://localhost/GitHub/Parking/index.php";//esto hay que cambiarlo para el VPS
    $urlKO="http://localhost/GitHub/Parking/contacto.php";
	$id=time();
	$importe = floatval($_POST['cantidad']??12.5); // Por defecto valor 12.5 porque el 0 da error por RedSys
    $amount = intval($importe * 100); // Redsys requiere el importe en céntimos, sin punto decimal

	
	// Se Rellenan los campos
	$miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
	$miObj->setParameter("DS_MERCHANT_ORDER",$id);
	$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
	$miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
	$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
	$miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
	$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
	$miObj->setParameter("DS_MERCHANT_URLOK",$urlOK);
	$miObj->setParameter("DS_MERCHANT_URLKO",$urlKO);

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


