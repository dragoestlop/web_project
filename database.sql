-- tabla de usuarios registrados
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    balance NUMERIC(10,2) DEFAULT 0.00
);

-- tabla del catalogo de juegos
CREATE TABLE games (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    price NUMERIC(10,2) NOT NULL,
    platform VARCHAR(50),
    is_online BOOLEAN DEFAULT false,
    cover_image VARCHAR(255)
);

-- tabla de codigos de recarga de saldo
CREATE TABLE recharge_codes (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    amount NUMERIC(10,2) NOT NULL,
    used BOOLEAN DEFAULT false
);

-- tabla de compras, el codigo de activacion se genera en el momento de comprar
CREATE TABLE purchases (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    game_id INT REFERENCES games(id),
    activation_code VARCHAR(100) NOT NULL,
    purchased_at TIMESTAMP DEFAULT NOW()
);

-- metemos los juegos en el catalogo que hemos creado antes
INSERT INTO games (title, description, price, platform, is_online, cover_image) VALUES
('The Witcher 3: Wild Hunt', 'An open-world RPG where you play as Geralt of Rivia, a monster hunter in a dark fantasy world.', 9.99, 'PlayStation', false, 'witcher3.jpg'),
('Grand Theft Auto V', 'An open-world action game set in the city of Los Santos. Includes online multiplayer mode.', 14.99, 'PC', true, 'gtav.jpg'),
('Minecraft', 'A sandbox game where you build and explore infinite worlds made of blocks.', 19.99, 'PC', true, 'minecraft.jpg'),
('Red Dead Redemption 2', 'An epic tale of life in Americas unforgiving heartland, set in 1899.', 19.99, 'PC', false, 'rdr2.jpg'),
('Counter-Strike 2', 'The legendary competitive first-person shooter, updated with new graphics and mechanics.', 0.00, 'PC', true, 'cs2.jpg'),
('Cyberpunk 2077', 'An open-world action RPG set in the dystopian Night City of the future.', 29.99, 'PC', false, 'cyberpunk.jpg'),
('Stardew Valley', 'A farming simulation RPG where you build your own farm and explore a charming world.', 9.99, 'PC', false, 'stardew.jpg'),
('Elden Ring', 'A challenging open-world action RPG created by FromSoftware and George R.R. Martin.', 39.99, 'PlayStation', false, 'eldenring.jpg'),
('God of War: Ragnarok', 'The epic continuation of Kratos and Atreuss journey through the Norse realms.', 49.99, 'PlayStation', false, 'godofwar.jpg'),
('The Last of Us Part II', 'A critically acclaimed action-adventure set in a post-apocalyptic world.', 39.99, 'PlayStation', false, 'thelastofus2.jpg'),
('Marvel Spider-Man 2', 'Play as both Peter Parker and Miles Morales in this action-packed superhero adventure.', 49.99, 'PlayStation', false, 'spiderman2.jpg'),
('Halo Infinite', 'The legendary sci-fi shooter returns with Master Chief in an open-world adventure.', 29.99, 'Xbox', true, 'haloinfinite.jpg'),
('Starfield', 'Bethesdas epic space RPG set in a vast universe with hundreds of planets to explore.', 39.99, 'Xbox', false, 'starfield.jpg'),
('Gears 5', 'An intense third-person shooter set in a brutal war against the Swarm.', 19.99, 'Xbox', false, 'gears5.jpg');


-- codigos de recarga pregenerados, el campo used empieza en false
INSERT INTO recharge_codes (code, amount) VALUES
('A3F8K2P9', 10.00),
('BX72WQ14', 25.00),
('9MRZL5TK', 50.00),
('KP4NZR81', 5.00),
('W6TBQX23', 100.00),
('MJ59HL7R', 15.00),
('ZQ3FVN8W', 20.00),
('R7KXBT45', 30.00),
('4WNMCJ6P', 75.00),
('YH82RZLT', 10.00),
('TX5KBN3Q', 5.00),
('6PZMWJ9F', 50.00),
('NR4LQXT8', 20.00),
('C9WBKM2Z', 100.00),
('8FQTNZR5', 15.00),
('LV3XPKW7', 25.00),
('JM6BNQR4', 10.00),
('5ZTKWX9L', 40.00),
('QN8RFBP2', 60.00),
('H4LWZMK6', 35.00);