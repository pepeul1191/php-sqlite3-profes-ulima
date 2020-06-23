-- migrate:up

CREATE TABLE teachers (
	id serial PRIMARY KEY,
	names VARCHAR(40) NOT NULL,
	last_names VARCHAR(40) NOT NULL,
	img VARCHAR(120) NOT NULL
) 

-- migrate:down

DROP TABLE teachers;