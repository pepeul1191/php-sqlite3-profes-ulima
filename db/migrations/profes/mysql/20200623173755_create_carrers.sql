-- migrate:up

CREATE TABLE carrers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL
) 

-- migrate:down

DROP TABLE carrers;