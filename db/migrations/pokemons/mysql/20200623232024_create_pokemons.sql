-- migrate:up

CREATE TABLE pokemons (
	id INT AUTO_INCREMENT PRIMARY KEY,
	number	INT NOT NULL,
	name	VARCHAR(40) NOT NULL,
	type_1	VARCHAR(30) NOT NULL,
	type_2	VARCHAR(30) NOT NULL,
	weight	FLOAT NOT NULL,
	height	FLOAT,
	img	VARCHAR(150)
)

-- migrate:down

DROP TABLE pokemons;