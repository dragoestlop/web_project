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
('The Witcher 3: Wild Hunt', 'An open-world RPG where you play as Geralt of Rivia, a monster hunter in a dark fantasy world.', 9.99, 'PC', false, 'witcher3.jpg'),
('Grand Theft Auto V', 'An open-world action game set in the city of Los Santos. Includes online multiplayer mode.', 14.99, 'PC', true, 'gtav.jpg'),
('Minecraft', 'A sandbox game where you build and explore infinite worlds made of blocks.', 19.99, 'PC', true, 'minecraft.jpg'),
('Red Dead Redemption 2', 'An epic tale of life in Americas unforgiving heartland, set in 1899.', 19.99, 'PC', false, 'rdr2.jpg'),
('Counter-Strike 2', 'The legendary competitive first-person shooter, updated with new graphics and mechanics.', 0.00, 'PC', true, 'cs2.jpg'),
('Cyberpunk 2077', 'An open-world action RPG set in the dystopian Night City of the future.', 29.99, 'PC', false, 'cyberpunk.jpg'),
('Stardew Valley', 'A farming simulation RPG where you build your own farm and explore a charming world.', 9.99, 'PC', false, 'stardew.jpg'),
('Elden Ring', 'A challenging open-world action RPG created by FromSoftware and George R.R. Martin.', 39.99, 'PC', false, 'eldenring.jpg');

-- codigos de recarga pregenerados, el campo used empieza en false
INSERT INTO recharge_codes (code, amount) VALUES
('TOPUP-ALPHA-001', 10.00),
('TOPUP-BETA-002', 25.00),
('TOPUP-GAMMA-003', 50.00),
('TOPUP-DELTA-004', 5.00),
('TOPUP-OMEGA-005', 100.00),
('TOPUP-SIGMA-006', 15.00),
('TOPUP-THETA-007', 20.00),
('TOPUP-KAPPA-008', 30.00),
('TOPUP-LAMBDA-009', 75.00),
('TOPUP-ZETA-010', 10.00);