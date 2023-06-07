CREATE TABLE item( 
      id number(10)    NOT NULL , 
      todolist_id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      prior_item_id number(10)   , 
      item_order number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      owner_id number(10)    NOT NULL , 
      company_id number(10)    NOT NULL , 
      uuid varchar(3000)    NOT NULL , 
      prior_list_id number(10)   , 
      list_order number(10)   , 
      color char  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist_user( 
      id number(10)    NOT NULL , 
      todolist_id number(10)    NOT NULL , 
      user_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE item ADD CONSTRAINT fk_item_1 FOREIGN KEY (todolist_id) references todolist(id); 
ALTER TABLE todolist_user ADD CONSTRAINT fk_todolist_user_1 FOREIGN KEY (todolist_id) references todolist(id); 
 CREATE SEQUENCE item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER item_id_seq_tr 

BEFORE INSERT ON item FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE todolist_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER todolist_id_seq_tr 

BEFORE INSERT ON todolist FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT todolist_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE todolist_user_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER todolist_user_id_seq_tr 

BEFORE INSERT ON todolist_user FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT todolist_user_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
