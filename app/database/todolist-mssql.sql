CREATE TABLE item( 
      id  INT IDENTITY    NOT NULL  , 
      todolist_id int   NOT NULL  , 
      name nvarchar(max)   NOT NULL  , 
      prior_item_id int   , 
      item_order int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist( 
      id  INT IDENTITY    NOT NULL  , 
      name nvarchar(max)   NOT NULL  , 
      owner_id int   NOT NULL  , 
      company_id int   NOT NULL  , 
      uuid nvarchar(max)   NOT NULL  , 
      prior_list_id int   , 
      list_order int   , 
      color char  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE todolist_user( 
      id  INT IDENTITY    NOT NULL  , 
      todolist_id int   NOT NULL  , 
      user_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE item ADD CONSTRAINT fk_item_1 FOREIGN KEY (todolist_id) references todolist(id); 
ALTER TABLE todolist_user ADD CONSTRAINT fk_todolist_user_1 FOREIGN KEY (todolist_id) references todolist(id); 

  
