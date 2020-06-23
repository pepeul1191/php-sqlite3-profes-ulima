-- migrate:up

CREATE TABLE teachers_carrers (
	id serial PRIMARY KEY,
	teacher_id INT NOT NULL,
	carrer_id INT NOT NULL,
	FOREIGN KEY (teacher_id) REFERENCES teachers (id),
    FOREIGN KEY (carrer_id) REFERENCES carrers (id)
) 

-- migrate:down

DROP TABLE teachers_carrers;