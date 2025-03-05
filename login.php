<?php
session_start();

include("includes/clases/login/loginForm.php");

$form = new loginForm(); 

$htmlFormLogin = $form->Manage();

?>