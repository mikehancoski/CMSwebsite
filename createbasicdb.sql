CREATE DATABASE demo_db;
USE demo_db;
CREATE TABLE users (
    id int(11) NOT NULL AUTO_INCREMENT,
    username varchar(50) NOT NULL,
    hashed_password varchar(40) NOT NULL,
    PRIMARY KEY (id)
);
INSERT INTO users (
                    username, hashed_password
                    ) VALUES (
                    'user1', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'
                    );
                    

CREATE TABLE subjects (
id int(11) NOT NULL AUTO_INCREMENT,
menu_name varchar(30) NOT NULL,
position int(3) NOT NULL,
visible tinyint(1) NOT NULL,
PRIMARY KEY (id)
);

INSERT INTO subjects (
menu_name, position, visible
) VALUES (
'About Company Xyz', 1,1
);

INSERT INTO subjects (
menu_name, position, visible
) VALUES (
'Products',2,1
);

INSERT INTO subjects (
menu_name, position, visible
) VALUES (
'Services',3,1
);

INSERT INTO subjects (
menu_name, position, visible
) VALUES (
'Whatever',4,1
);

CREATE TABLE pages (
id int(11) NOT NULL AUTO_INCREMENT,
subject_id int(11) NOT NULL,
menu_name varchar(30) NOT NULL,
position int(3) NOT NULL,
visible tinyint(1) NOT NULL,
content TEXT NOT NULL,
PRIMARY KEY(id)

);

INSERT INTO pages (
    subject_id,menu_name,position,visible,content
)VALUES(
1,'History',1,1,'This is the company history ...'


);

INSERT INTO pages (
    subject_id,menu_name,position,visible,content
)VALUES(
1,'Our Mission',2,1,'Our Corporate Mission statement is ...'


);

INSERT INTO pages (
    subject_id,menu_name,position,visible,content
)VALUES(
2,'ABC 2000',1,1,'The ABC 2000 is a great product.That''s all'


);

INSERT INTO pages (
    subject_id,menu_name,position,visible,content
)VALUES(

2,'ABC 3000',2,1,'this is the ABC 3000 it''s grand'

);


