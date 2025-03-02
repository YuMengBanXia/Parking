//Este es el antiguo con algunos cambios en la tabla Parkings. 
<!DOCTYPE>
<html>
<head>
<title>Inicializar_mysql</title>
</head>
<body>
<p>
<?php

$db= @mysqli_connect("localhost","root","",);
if($db){
    echo'Conexión realizada correctamente.<br/>';
    echo'Información sobre el servidor:',
    mysqli_get_host_info($db),'<br/>';
    echo "Versión del servidor:",
    mysqli_get_server_info($db),"<br/>";
}else{
    printf("Error%d:%s.<br/>",mysqli_connect_errno(),mysqli_connect_error());
};

//Create table
$sql="CREATE DATABASE practica";
if(mysqli_query($db,$sql)){
echo "Database created successfully<br> ";
}else{
echo "creating database:" .mysqli_error($db);
}

//Creación de las tablas
$db = @mysqli_connect("localhost","root","","practica");

//Tabla parking
$sql = "CREATE TABLE parkings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dir VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    UNIQUE (dir,ciudad), 
    CP DECIMAL(5,0) NOT NULL,//intentar restringuir a 5 dígitos
    precio DECIMAL(5,4) NOT NULL,
    n_plazas INT NOT NULL)";
if(mysqli_query($db,$sql)){
    echo "Table parking created succesfully<br>";
} else{
    echo "Error table parkings: ".mysqli_error($db);
}

//Tabla usuarios
$sql = "CREATE TABLE usuario (
    usuario VARCHAR(99) UNIQUE NOT NULL,
    contraseña VARCHAR(99) NOT NULL,
    dni VARCHAR(9) PRIMARY KEY,
    email VARCHAR(99),
    tipo ENUM('admin','cliente') DEFAULT 'cliente')";
if(mysqli_query($db,$sql)){
    echo "Table usuario created succesfully<br>";
} else{
    echo "Error table usuario: ".mysqli_error($db);
}

//Tabla ticket
$sql = "CREATE TABLE ticket (
    codigo INT UNSIGNED,
    id INT REFERENCES parking,
    matricula VARCHAR(7),
    fecha_ini DATETIME NOT NULL,
    PRIMARY KEY (codigo,id))";
if(mysqli_query($db,$sql)){
    echo "Table ticket created succesfully <br>";
} else{
    echo "Error table ticket: ".mysqli_error($db);
}

//Tabla abonado
$sql = "CREATE TABLE abonado (
    dni VARCHAR(9) REFERENCES usuario,
    id INT REFERENCES parking,
    matricula VARCHAR(7),
    banco VARCHAR(99) NOT NULL,
    num INT NOT NULL references plaza,
    PRIMARY KEY(dni,id))";
if(mysqli_query($db,$sql)){
    echo "Table abonado created succesfully<br>";
} else{
    echo "Error table abonado: ".mysqli_error($db);
}

//Tabla plaza
$sql = "CREATE TABLE plaza (
    num INT UNSIGNED,
    id INT REFERENCES parking,
    ocupado BIT DEFAULT 0,
    PRIMARY KEY(num,id))";
if(mysqli_query($db,$sql)){
    echo "Table plaza created succesfully <br>";
} else{
    echo "Error table plaza: ".mysqli_error($db);
}

//Tabla reserva
$sql = "CREATE TABLE reserva (
    dni VARCHAR(9) REFERENCES usuario,
    id INT REFERENCES parking,
    matricula VARCHAR(7),
    fecha DATE,
    num INT NOT NULL references plaza,
    PRIMARY KEY(dni,id,fecha))";
if(mysqli_query($db,$sql)){
    echo "Table reserva created succesfully<br>";
} else{
    echo "Error table reserva: ".mysqli_error($db);
}

//Creación de un admin para probar la vista admin
$sql = "INSERT INTO `usuario`(`usuario`, `contraseña`, `dni`, `tipo`) VALUES ('admin','admin','XXXXXXXXX','admin')";
if(mysqli_query($db,$sql)){
    echo "Admin creado <br>";
} else{
    echo "Error en la creacion del admin: ".mysqli_error($db);
}

@mysqli_close($db);
?>
</p>
</body>
</html>
