<?php
session_start();

// ---- Colores y direcciones por mercado ----
$mercadosInfo = [
    "Ara" => ["color" => "#ff0000", "direccion" => "Cra 10 #20-30", "logo" => "Fotos/ara.jpg"],
    "Cooratiendas" => ["color" => "#008000", "direccion" => "Cl 45 #12-15", "logo" => "Fotos/cooratiendas.png"],
    "D1" => ["color" => "#800080", "direccion" => "Av 7 #50-22", "logo" => "Fotos/D1.png"],
    "Exito" => ["color" => "#FFD700", "direccion" => "Cl 80 #30-40", "logo" => "Fotos/exito.jpeg"],
    "Mercacentro" => ["color" => "#FF8C00", "direccion" => "Cra 15 #25-10", "logo" => "Fotos/mercacentro.png"],
    "Metro" => ["color" => "#006400", "direccion" => "Av 68 #55-60", "logo" => "Fotos/metro.jpeg"],
    "Zapatoca" => ["color" => "#0000FF", "direccion" => "Cl 12 #34-56", "logo" => "Fotos/zapatoca.jpeg"]
];

// ---- Agregar desde Productos.php ----
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nombre'])) {
    $producto = [
        'nombre'   => $_POST['nombre'],
        'peso'     => $_POST['peso'],
        'precio'   => floatval($_POST['precio']),
        'mercado'  => $_POST['mercado'],
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
}

// ---- Actualizar cantidades ----
if (isset($_GET['accion']) && isset($_GET['id']) && isset($_SESSION['lista'][$_GET['id']])) {
    $id = intval($_GET['id']);
    if ($_GET['accion'] === 'sumar') {
        $_SESSION['lista'][$id]['cantidad']++;
    } elseif ($_GET['accion'] === 'restar') {
        $_SESSION['lista'][$id]['cantidad']--;
        if ($_SESSION['lista'][$id]['cantidad'] <= 0) {
            unset($_SESSION['lista'][$id]);
            $_SESSION['lista'] = array_values($_SESSION['lista']); // reindexar
        }
    }
}

// ---- Eliminar lista completa ----
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar_lista'])) {
    unset($_SESSION['lista']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Facotiza - Mi Lista</title>
  <link rel="stylesheet" href="css/lista.css"/>
</head>
<body>

  <?php include "navbar.php"; ?>  <!-- Franja naranja con botones -->

  <div class="contenedor">
    <h1>🛒 Mi Lista</h1>
    <div class="print-header">Facotiza</div>

    <?php if (!empty($_SESSION['lista'])): ?>
        <?php
        // Agrupar por mercado
        $mercados = [];
        foreach ($_SESSION['lista'] as $id => $producto) {
            $mercados[$producto['mercado']][] = ['id'=>$id] + $producto;
        }

        $total = 0;
        foreach ($mercados as $mercado => $productos):
            $info = $mercadosInfo[$mercado] ?? ["color"=>"orange","direccion"=>"Sin dirección","logo"=>""];
            $color = $info["color"];
            $direccion = $info["direccion"];
            $logo = $info["logo"];
            $colorTexto = ($mercado === "Éxito") ? "black" : "white";
        ?>
            <div class="mercado" style="background:<?= $color ?>; color:<?= $colorTexto ?>;">
                <div class="mercado-info">
                    <div class="circulo-logo">
                        <img src="<?= $logo ?>" alt="<?= $mercado ?>">
                    </div>
                    <div>
                        <div class="mercado-nombre"><?= $mercado ?></div>
                        <div class="mercado-direccion"><?= $direccion ?></div>
                    </div>
                </div>
            </div>

            <table>
                <tr><th>Nombre</th><th>Peso</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th></tr>
                <?php foreach ($productos as $p): 
                    $subtotal = $p['precio'] * $p['cantidad'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= $p['nombre'] ?></td>
                    <td><?= $p['peso'] ?></td>
                    <td>$<?= number_format($p['precio'],0,",",".") ?></td>
                    <td class="qty-cell">
                        <a href="?accion=restar&id=<?= $p['id'] ?>" class="btn btn-restar">−</a>
                        <span class="qty"><?= $p['cantidad'] ?></span>
                        <a href="?accion=sumar&id=<?= $p['id'] ?>" class="btn btn-sumar">+</a>
                    </td>
                    <td>$<?= number_format($subtotal,0,",",".") ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>

        <div class="total">TOTAL: $<?= number_format($total,0,",",".") ?></div>

        <div class="acciones">
            <form method="POST" action="Listas.php" style="display:inline;">
                <input type="hidden" name="eliminar_lista" value="1">
                <button type="submit" class="btn-eliminar">🗑 Eliminar lista</button>
            </form>

            <button onclick="window.print()" class="btn-imprimir">🖨 Imprimir lista</button>
        </div>

    <?php else: ?>
        <p class="vacio">No hay productos en tu lista.</p>
    <?php endif; ?>
  </div>

</body>
</html>
