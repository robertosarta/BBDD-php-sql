<?php 
include 'conexion.php'; //preguntar que hace esto exactamente
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Top 5 productos más baratos (DQL)</h1>
    <?php
        $sql = "SELECT * FROM producto ORDER BY precio ASC LIMIT 5"; //mirar la diferencia entre ponerlo asi (html) a ponerlo en sql nativo. Como cambia y porque.
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li>" . $row["nombre"] . " - $" . $row["precio"] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No hay productos.";
        }
    ?>
    <!-- fin ejercicio -->
    
    <h1>Numero de pedidos del mes actual (DQL)</h1>
    <?php
    $sql = "SELECT COUNT(*) AS pedidos_mes FROM pedido
            WHERE MONTH(fecha) = MONTH(CURDATE())
            AND YEAR(fecha) = YEAR(CURDATE())";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p>Número de pedidos este mes: <strong>" . $row["pedidos_mes"] . "</strong></p>";
    } else {
        echo"<p>No hay pedidos este mes.</p>";
    }
    ?>
    <!-- fin ejercicio -->
    
    <h1>inserciones TCL con ROLLBACK</h1>
    <?php
    $conn->autocommit(FALSE);
    try {
        $conn->query("INSERT INTO producto (nombre, precio, stock, id_categoria) VALUES ('Tablet', 250.00, 6, 1)");
        $conn->query("INSERT INTO producto (nombre, precio, stock, id_categoria) VALUES ('smartwatch', 150.00, 10, 1)");

        echo "<h2>Antes del rollback:</h2>";
        $result = $conn->query("SELECT * FROM producto");
        if ($result->num_rows > 0) {
            echo"<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row['id_producto'] . " - " . $row['nombre'] . " | Precio: " . $row['precio'] . " | Stock: " . $row['stock'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo"<p>No productos en la tabla.</p>";
        }

        $conn->rollback();

        echo "<h2>Despues del rollback:</h2>";
        $sql = "SELECT * FROM producto";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo"<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row['id_producto'] . " - " . $row['nombre'] . " | Precio: " . $row['precio'] . " | Stock: " . $row['stock'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo"<p>No hay productos en la tabla.</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
    <!-- fin ejercicio -->
    
    <h1>Crear un usuario nuevo y asignar permisos de lectura y escritura en la tabla "producto"</h1>
    <?php
    //Borramos el usuario si ya existia porque se genera siempre para el ejercicio y se queda guardado
    $sql1 = "DROP USER IF EXISTS 'usuario_ejercicio'@'localhost'";
    if ($conn->query($sql1) === TRUE) {
        echo "<p>Usuario 'usuario_ejercicio' eliminado si existía para poder realizar ejercicio.</p>";
    } else {
        echo "<p>Error al eliminar usuario: " . $conn->error . "</p>";
    }

    //Creamos usuario con contraseña
    $sql2 = "CREATE USER 'usuario_ejercicio'@'localhost' IDENTIFIED BY 'claveSegura123'";
    if($conn->query($sql2) === TRUE) {
        echo "<p>Usuario 'usuario_ejercicio' creado correctamente.</p>";
    } else {
        echo "<p>Error al crear usuario: " . $conn->error . "</p>";
    }

    //Asignar permisos de lectura y escritura
    $sql3 = "GRANT SELECT, INSERT, UPDATE, DELETE ON ejercicio1.producto TO 'usuario_ejercicio'@'localhost'";
    if($conn->query($sql3) === TRUE) {
        echo "<p>Permisos asignados correctamente.</p>";
    } else {
        echo "<p>Error al asignar permisos: " . $conn->error . "</p>";
    }

    //Aplicar cambios de permisos
    $sql4 = "FLUSH PRIVILEGES";
    if ($conn->query($sql4) === TRUE) {
        echo "<P>Privilegios actualizados.</P>";
    } else {
        echo"<P>Error al actualizar privilegios: " . $conn->error . "</p>";
    }
    ?>

    <!-- fin del ejercicio -->

    <h1>Crear función que con el producto seleccionado nos muestre el numero de veces que se ha vendido dicho producto.</h1>
    <?php
    $sql = "SELECT veces_vendido(1) AS vendidos";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "El producto con id = 1 se ha vendido " . $row["vendidos"] . " veces";
    ?>
    <!-- fin del ejercicio -->

    <!-- $conn->close();     (ponerla en la ultima operacion para cerrar conexion)-->
</body>
</html>

