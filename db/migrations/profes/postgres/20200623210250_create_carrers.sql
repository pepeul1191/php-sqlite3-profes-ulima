-- migrate:up

CREATE TABLE carrers (
	id serial PRIMARY KEY,
	name VARCHAR(30) NOT NULL
) 

-- migrate:down

DROP TABLE carrers;