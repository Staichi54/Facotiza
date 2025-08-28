<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facotiza</title>
  <link rel="stylesheet" href="css/principal.css">
</head>
<body>

  <div class="fondo"></div>
  <div class="difuminado"></div>

  <!-- Barra superior -->
  <div class="barraArriba">
    <div class="logo">
      <a href="Principal.php">facotiza</a>
    </div>

    <div class="menu">
      <button onclick="window.location.href='Productos.php'">Productos</button>
      <button onclick="window.location.href='Listas.php'">Listas</button>
    </div>
  </div>

  <!-- Barra de búsqueda -->
  <div class="barraBusqueda">
  <form method="GET" action="Productos.php" class="search-form">
    <input type="text" name="q" placeholder="Buscar...">
    <button type="submit">🔍</button>
  </form>
</div>

  <!-- Texto central -->
  <div class="texto">
    <h1>Tu bolsillo lo agradece</h1>
    <p>Compara, Elige y Ahorra</p>
  </div>

  <!-- Mercados asociados -->
  <div class="barraAbajo">
    <h2>Mercados asociados</h2>
    <div class="mercados">
      <div class="circulo"><img src="Fotos/ara.jpg" alt="Ara"></div>
      <div class="circulo"><img src="Fotos/cooratiendas.png" alt="Cooratiendas"></div>
      <div class="circulo"><img src="Fotos/D1.png" alt="D1"></div>
      <div class="circulo"><img src="Fotos/exito.jpeg" alt="Éxito"></div>
      <div class="circulo"><img src="Fotos/mercacentro.png" alt="Mercacentro"></div>
      <div class="circulo"><img src="Fotos/metro.jpeg" alt="Metro"></div>
      <div class="circulo"><img src="Fotos/zapatoca.jpeg" alt="Zapatoca"></div>
    </div>
  </div>

</body>
</html>
