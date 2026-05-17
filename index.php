<?php
// nos conectamos a la base de datos
require_once 'config.php';

// iniciamos la sesion para saber si el usuario esta logueado o no
session_start();
// traemos todos los juegos de la base de datos
$sql = "SELECT * FROM games WHERE 1=1";   //1=1 no filtra nada, es solo para poder añadir los AND de los filtros sin preocuparnos de si es el primero o no
$filters = [];    //creo el array vacio donde guardaremos los parametros como el valor del buscador o el filtro de plataforma
//usamos $sql y $filters por separado por seguridad, por si el usuario introduce algun dato maligno

if (!empty($_GET['search'])) {  // si el usuario ha escrito algo en el buscador lo filtramos
    $sql .= " AND LOWER(title) LIKE LOWER(:search)";  //LOWER pasa todo a minuscula, hace que la busqueda no sea case sensitiv. LIKE es como = pero sin que sea exactamente igual
    $filters['search'] = '%' . $_GET['search'] . '%';  //los % dindican que el texto puede ser una parte de la palabra, por ejemplo "mari" encontraria "super Mario"
}

// filtro por plataforma, lee el valor del desplegable
if (!empty($_GET['platform'])) {  
    $sql .= " AND platform = :platform";
    $filters['platform'] = $_GET['platform'];
}

// filtro online/offline
if (!empty($_GET['online'])) {
    $sql .= " AND is_online = :online";
    if ($_GET['online'] === 'true') { //si elige online es true
        $filters['online'] = 'true';
    } else {
        $filters['online'] = 'false';
    }
}

// filtro por precio maximo
if (!empty($_GET['max_price'])) {
    $sql .= " AND price <= :max_price";
    $filters['max_price'] = $_GET['max_price'];
}

// ejecutamos la consulta con PDO
$prepared = $pdo->prepare($sql);  //$pdo es la variable de config.php que prepara la consulta con los marcadores de $sql y la almacena en $prepared
$prepared->execute($filters);  //ahora le pasamos los valores de $filters que sustituyen a los marcadores en $sql y se sustituyen
// guardamos los resultados que devuelve  en un array 
$games = [];
while ($fila = $prepared->fetch()) {
    $games[] = $fila;
}
// fetch coge las filas de la tabla de juegos de una en una y el while acaba cuando ya no quedan juegos compatibles con los filtros
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!--esto es para el responsive design-->    
    <title>EasyGames</title>
    <link rel="stylesheet" href="css/styles.css"> <!--enlaza con el css-->
</head>

<body>
    <!-- barra de navegacion -->
    <nav>
        <div class="nav-logo">
            <a href="index.php"><img src="img/logo.png" alt="EasyGames"></a>  <!--inserta el logo y te lleve a la pagina principal-->
        </div>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])){ ?>  <!--este if comprueba si el usuario esta logueado-->
                <!-- si el usuario esta logueado mostramos su nombre y saldo -->
                <p>Hello, <?php echo $_SESSION['username']; ?></p>  <!--p es un elemento de párrafo-->
                <p class="balance" id="balance"><?php echo round($_SESSION['balance'], 2); ?>€</p>
                <a href="profile.php">My Profile</a>
                <a href="logout.php">Logout</a>
            <?php }else{ ?>
                <!-- si no esta logueado mostramos los botones de login y registro -->
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php } ?>
        </div>
    </nav>

    <!--filtros -->
<div class="filters">
    <form method="GET" action="index.php">  <!--method GET hace que los filtros aparezcan en la url-->
        <input type="text" name="search" placeholder="Search games...">
        <select name="platform">  <!--select crea un desplegable con distintas options-->
            <option value="">All platforms</option>
            <option value="PC">PC</option>
            <option value="PlayStation">PlayStation</option>
            <option value="Xbox">Xbox</option>
            <option value="Nintendo">Nintendo</option>
        </select>
        <select name="online">
            <option value="">Online & Offline</option>
            <option value="true">Online only</option>
            <option value="false">Offline only</option>
        </select>
        <input type="number" name="max_price" placeholder="Max price €">
        <button type="submit">Filter</button>
        <a href="index.php">Clear filters</a>
    </form>
</div>

    <!-- catalogo de juegos -->
<div class="catalog">  <!--En css usaremos la clase catalog para darle diseño de rejilla a las cards de los juegos y que queden alineads-->
    <?php if (empty($games)) { ?>
        <p class="no-results">No games found :(</p>
    <?php } else { ?>
        <?php foreach ($games as $game) { ?>
            <div class="game-card" onclick="openModal(<?php echo $game['id']; ?>)">  <!--onclick ejecuta la funcion openModal de JS y hace que se abra la tarjeta con los datos del juego-->
                <img src="img/<?php echo $game['cover_image']; ?>" alt="<?php echo $game['title']; ?>">
                <div class="game-info">
                    <h3><?php echo $game['title']; ?></h3>  <!--titulo del juego-->
                    <p class="price">  <!--precio del juego que si es gratis pone free-->
                        <?php if ($game['price'] > 0) { ?>
                            <?php echo $game['price']; ?>€
                        <?php } else { ?>
                            Free
                        <?php } ?>
                    </p>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

    <!-- modal con los detalles del juego, empieza oculto -->
     <!--esto es lo que se despliega al darle a la card del juego gracias al javascript-->
<div id="modal" class="modal hidden">  <!-- hidden no aparece en las diapositivas pero lo uso porque hace que el desplegable con los datos no se abra hasta que no le des-->
    <div class="modal-content">
        <!-- boton de cerrar, llama a closeModal() en main.js -->
        <button class="close-btn" onclick="closeModal()">X</button>
        <!-- estos elementos empiezan vacios, javascript los rellena al hacer clic en una card -->
        <img id="modal-cover" src="" alt="">
        <div class="modal-info">
            <h2 id="modal-title"></h2>
            <p id="modal-description"></p>
            <p id="modal-platform"></p>
            <p id="modal-online"></p>
            <p id="modal-price"></p>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <!-- si el usuario esta logueado mostramos el boton de compra -->
                <button id="buy-btn" onclick="buyGame()">Buy Now</button>
            <?php } else { ?>
                <!-- si no esta logueado le mandamos al login -->
                <a href="login.php">Login to buy</a>
            <?php } ?>
            <p id="modal-message"></p>

        </div>
    </div>
</div>


<!-- enlazamos el javascript al final del body para que el html ya este cargado -->
<script src="js/main.js"></script>
</body>
</html>