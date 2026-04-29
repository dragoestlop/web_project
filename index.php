<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<header class="main-header">
  <div class="logo">
    <h1>Game Shop</h1> <!-- meter logo aquí-->
  </div>
  
  <form class="search-container" id="search-form">
    <input type="search" id="search-input" placeholder="Search..." aria-label="Search games"> 
    <button type="submit">Search</button>
  </form>

  <nav class="user-controls"> <!-- Queremos que el carrito solo se despliegue, no nos lleve a otro html -->
    <button id="cart-toggle" class="btn-icon">
      🛒 Cart <span id="cart-count">0</span>
    </button>
    <a href="login.html" class="btn-login">login</a>
  </nav>
</header>


<!--Es para hacer pruebas con CSS y HTML, estos juegos deberian estar en una base de datos-->
<main>
    <section id="catalogo">
        <article class="game-card">
            <img src="" alt="">
            <h2>GTA V</h2>
            <p>Xbox Series X</p>
            <p>19.99€</p>
            <button class="btn-add-to-cart">Add to cart</button>
        </article>
        <article class="game-card">
            <img src="" alt="">
            <h2>Fifa</h2>
            <p>PS5</p>
            <p>59.99€</p>
            <button class="btn-add-to-cart">Add to cart</button>
        </article>
        <article class="game-card">
            <img src="" alt="">
            <h2>Rocket League</h2>
            <p>PC</p>
            <p>19.99€</p>
            <button class="btn-add-to-cart">Add to cart</button>
        </article>
        <article class="game-card">
            <img src="" alt="">
            <h2>Mario Kart</h2>
            <p>Nintendo Switch</p>
            <p>59.99€</p>
            <button class="btn-add-to-cart">Add to cart</button>    
        </article>
    </section>
</main>


</body>
</html>


<!--Esto es para probar que funciona la extension de php para cuando añadamos las cosas del servidor-->
<?php
echo "Funciona PHP";
?>