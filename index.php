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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyGames</title>
    <!-- aqui enlazamos el css que haremos despues -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <!-- barra de navegacion -->
    <nav>
        <div class="nav-logo">
            <a href="index.php">EasyGames</a>
        </div>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- si el usuario esta logueado mostramos su nombre y saldo -->
                <span>Hello, <?php echo $_SESSION['username']; ?></span>
                <span class="balance">€<?php echo number_format($_SESSION['balance'], 2); ?></span>
                <a href="profile.php">My Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- si no esta logueado mostramos los botones de login y registro -->
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- seccion de busqueda y filtros -->
    <div class="filters">
        <!-- el metodo GET hace que los filtros aparezcan en la url -->
        <form method="GET" action="index.php">
            <input
                type="text"
                name="search"
                placeholder="Search games..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
            >
            <select name="platform">
                <option value="">All platforms</option>
                <option value="PC" <?php echo (isset($_GET['platform']) && $_GET['platform'] === 'PC') ? 'selected' : ''; ?>>PC</option>
                <option value="PlayStation" <?php echo (isset($_GET['platform']) && $_GET['platform'] === 'PlayStation') ? 'selected' : ''; ?>>PlayStation</option>
                <option value="Xbox" <?php echo (isset($_GET['platform']) && $_GET['platform'] === 'Xbox') ? 'selected' : ''; ?>>Xbox</option>
            </select>
            <select name="online">
                <option value="">Online & Offline</option>
                <option value="true" <?php echo (isset($_GET['online']) && $_GET['online'] === 'true') ? 'selected' : ''; ?>>Online only</option>
                <option value="false" <?php echo (isset($_GET['online']) && $_GET['online'] === 'false') ? 'selected' : ''; ?>>Offline only</option>
            </select>
            <input
                type="number"
                name="max_price"
                placeholder="Max price €"
                value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>"
            >
            <button type="submit">Filter</button>
            <a href="index.php">Clear filters</a>
        </form>
    </div>

    <!-- catalogo de juegos -->
    <div class="catalog">
        <?php if (empty($games)): ?>
            <!-- si no hay juegos que coincidan con el filtro -->
            <p class="no-results">No games found.</p>
        <?php else: ?>
            <?php foreach ($games as $game): ?>
                <!-- una card por cada juego -->
                <div class="game-card" onclick="openModal(<?php echo $game['id']; ?>)">
                    <img src="img/<?php echo htmlspecialchars($game['cover_image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
                    <div class="game-info">
                        <h3><?php echo htmlspecialchars($game['title']); ?></h3>
                        <span class="platform"><?php echo htmlspecialchars($game['platform']); ?></span>
                        <span class="online-badge">
                            <?php echo $game['is_online'] ? 'Online' : 'Offline'; ?>
                        </span>
                        <p class="price">
                            <?php echo $game['price'] > 0 ? '€' . number_format($game['price'], 2) : 'Free'; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- modal con los detalles del juego, empieza oculto -->
    <div id="modal" class="modal hidden">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">✕</button>
            <img id="modal-cover" src="" alt="">
            <div class="modal-info">
                <h2 id="modal-title"></h2>
                <p id="modal-description"></p>
                <span id="modal-platform"></span>
                <span id="modal-online"></span>
                <p id="modal-price"></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button id="buy-btn" onclick="buyGame()">Buy Now</button>
                <?php else: ?>
                    <a href="login.php">Login to buy</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- enlazamos el javascript -->
    <script src="js/main.js"></script>
</body>
</html>