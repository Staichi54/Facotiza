<?php
session_start();
$serverName = "DESKTOP-56FEFQK";  
$connectionInfo = array(
    "Database" => "Inventario",
    "UID" => "DFME",       
    "PWD" => "1234",  
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    die("❌ Error de conexión:<br>" . print_r(sqlsrv_errors(), true));
}

// --------- Agregar producto a la lista (cuando se pulsa +) ---------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $producto = [
        'nombre' => $_POST['nombre'],
        'peso' => $_POST['peso'],
        'precio' => (int) $_POST['precio'],
        'mercado' => $_POST['mercado'],
        'cantidad' => 1
    ];

    if (!isset($_SESSION['lista'])) {
        $_SESSION['lista'] = [];
    }

    $existe = false;
    foreach ($_SESSION['lista'] as &$item) {
        if ($item['nombre'] === $producto['nombre'] && $item['mercado'] === $producto['mercado']) {
            $item['cantidad']++;
            $existe = true;
            break;
        }
    }
    if (!$existe) {
        $_SESSION['lista'][] = $producto;
    }

    // 🔹 Aquí cambiamos la redirección:
    header("Location: Listas.php");
    exit();
}

// --------- Filtros de búsqueda ---------
$busqueda = isset($_GET['q']) ? $_GET['q'] : "";
$mercadoFiltro = isset($_GET['mercado']) ? $_GET['mercado'] : "";
$orden = isset($_GET['orden']) ? $_GET['orden'] : "";

$sql = "SELECT NombreProducto, Peso, PrecioActual, Mercado, Imagen 
        FROM Productos 
        WHERE NombreProducto LIKE ?";
$params = array("%$busqueda%");

// Filtro por mercado
if (!empty($mercadoFiltro)) {
    $sql .= " AND Mercado = ?";
    $params[] = $mercadoFiltro;
}

// Orden por precio
if ($orden == "ASC") {
    $sql .= " ORDER BY PrecioActual ASC";
} elseif ($orden == "DESC") {
    $sql .= " ORDER BY PrecioActual DESC";
}

$stmt = sqlsrv_query($conn, $sql, $params);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Facotiza - Productos</title>
<link rel="stylesheet" href="css/productos.css">
</head>
<body>

<div class="barraArriba">
  <div class="logo">
    <a href="Principal.php">facotiza</a>
  </div>

  <div class="menu">
    <button onclick="window.location.href='Productos.php'">Productos</button>
    <button onclick="window.location.href='Listas.php'">Listas</button>
  </div>
</div>

<div class="filtros">
  <form method="GET" action="Productos.php">
    <input type="text" name="q" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>">

    <select name="mercado">
      <option value="">Todos los mercados</option>
      <?php
      $mercados = ["Ara","Cooratiendas","D1","Exito","Mercacentro","Metro","Zapatoca"];
      foreach($mercados as $m){
          $selected = ($mercadoFiltro == $m) ? "selected" : "";
          echo "<option value='$m' $selected>$m</option>";
      }
      ?>
    </select>

    <select name="orden">
      <option value="">Ordenar por precio</option>
      <option value="ASC" <?php if($orden=="ASC") echo "selected"; ?>>Menor a mayor</option>
      <option value="DESC" <?php if($orden=="DESC") echo "selected"; ?>>Mayor a menor</option>
    </select>

    <button type="submit">Filtrar</button>
  </form>
</div>

<!-- Resultados -->
<div class="productos-grid">
<?php
if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $nombre  = $row['NombreProducto'];
        $peso    = $row['Peso'];
        $precio  = $row['PrecioActual']; 
        $mercado = $row['Mercado'];

        // Imagen producto
        if ($row['Imagen'] !== null) {
            $imagenBase64 = 'data:image/jpeg;base64,' . base64_encode($row['Imagen']);
        } else {
            $imagenBase64 = 'Fotos/default.png';
        }

        // Colores y logos según mercado
        $colores = [
            "Ara" => ["#FF8C00", "ara.jpg"],
            "Cooratiendas" => ["#004d00", "cooratiendas.png"],
            "D1" => ["#cc0000", "D1.png"],
            "Exito" => ["#fcfc43ff", "exito.jpeg"],
            "Mercacentro" => ["#0066cc", "mercacentro.png"],
            "Metro" => ["#d3a900ff", "metro.jpeg"],
            "Zapatoca" => ["#66cc66", "zapatoca.jpeg"],
        ];

        $color = $colores[$mercado][0] ?? "#ccc";
        $logo = "Fotos/" . ($colores[$mercado][1] ?? "default.png");

        echo "
        <div class='card'>
            <div class='card-header' style='background-color: $color'>
                <img src='$logo' alt='$mercado' class='logo-mercado'>
            </div>
            <div class='card-body'>
                <div class='img-container'>
                    <img src='$imagenBase64' alt='$nombre' class='producto-img'>

                    <!-- Botón + dentro de un formulario -->
                    <form method='POST' action='Productos.php?".http_build_query($_GET)."' class='form-plus'>
                        <input type='hidden' name='nombre' value='$nombre'>
                        <input type='hidden' name='peso' value='$peso'>
                        <input type='hidden' name='precio' value='$precio'>
                        <input type='hidden' name='mercado' value='$mercado'>
                        <button type='submit' class='btn-plus'>+</button>
                    </form>

                </div>
            </div>

            <div class='card-footer'>
                <p class='nombre'>$nombre - $peso</p>
                <p class='precio'>$".number_format($precio, 0, ',', '.')."</p>
            </div>
        </div>";
    }
}
?>
</div>

</body>
</html>
