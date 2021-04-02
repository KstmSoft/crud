CREATE TABLE IF NOT EXISTS epico_items (
	id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(255) NOT NULL,
	category varchar(255) NOT NULL,
	cost_price decimal(15,2) NOT NULL,
	unit_price decimal(15,2) NOT NULL,
	pic_filename varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
)