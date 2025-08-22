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
    <h1>Top 5 productos más baratos</h1>
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
    
    <h1>Numero de pedidos del mes actual</h1>
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
    
    <h1>2 inserciones TCL con ROLLBACK</h1>
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
            echo"<p>No productos en la tabla.</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }

    $conn->close();
    ?>
    
</body>
</html>

