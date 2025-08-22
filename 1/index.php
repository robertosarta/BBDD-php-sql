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

    <h2>Numero de pedidos del mes actual</h2>
    <?php
    $sql = "SELECT COUNT(*) AS pedidos_mes
            FROM pedido
            WHERE MONTH(fecha) = MONTH(CURDATE())
            AND YEAR(fecha) = YEAR(CURDATE())";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p>Número de pedidos este mes: <strong>" . $row["pedidos_mes"] . "</strong></p>";
    } else {
        echo"<p>No hay pedidos este mes.</p>";
    }
    $conn->close();
    ?>
</body>
</html>
