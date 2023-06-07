PRAGMA foreign_keys=OFF; 

CREATE TABLE item( 
      id  INTEGER    NOT NULL  , 
      todolist_id int   NOT NULL  , 
      name text   NOT NULL  , 
      prior_item_id int   , 
      item_order int   , 
 PRIMARY KEY (id),
FOREIGN KEY(todolist_id) REFERENCES todolist(id)) ; 

CREATE TABLE todolist( 
      id  INTEGER    NOT NULL  , 
      name text   NOT NULL  , 
      owner_id int   NOT NULL  , 
      company_id int   NOT NULL  , 
      uuid text   NOT NULL  , 
      prior_list_id int   , 
      list_order int   , 
      color char  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist_user( 
      id  INTEGER    NOT NULL  , 
      todolist_id int   NOT NULL  , 
      user_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(todolist_id) REFERENCES todolist(id)) ; 

 
 
  
