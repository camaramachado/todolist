CREATE TABLE item( 
      id  SERIAL    NOT NULL  , 
      todolist_id integer   NOT NULL  , 
      name text   NOT NULL  , 
      prior_item_id integer   , 
      item_order integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist( 
      id  SERIAL    NOT NULL  , 
      name text   NOT NULL  , 
      owner_id integer   NOT NULL  , 
      company_id integer   NOT NULL  , 
      uuid text   NOT NULL  , 
      prior_list_id integer   , 
      list_order integer   , 
      color char  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist_user( 
      id  SERIAL    NOT NULL  , 
      todolist_id integer   NOT NULL  , 
      user_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE item ADD CONSTRAINT fk_item_1 FOREIGN KEY (todolist_id) references todolist(id); 
ALTER TABLE todolist_user ADD CONSTRAINT fk_todolist_user_1 FOREIGN KEY (todolist_id) references todolist(id); 

  
