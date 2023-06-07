CREATE TABLE item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `todolist_id` int   NOT NULL  , 
      `name` text   NOT NULL  , 
      `prior_item_id` int   , 
      `item_order` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE todolist( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `name` text   NOT NULL  , 
      `owner_id` int   NOT NULL  , 
      `company_id` int   NOT NULL  , 
      `uuid` text   NOT NULL  , 
      `prior_list_id` int   , 
      `list_order` int   , 
      `color` char  (20)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE todolist_user( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `todolist_id` int   NOT NULL  , 
      `user_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
  
 ALTER TABLE item ADD CONSTRAINT fk_item_1 FOREIGN KEY (todolist_id) references todolist(id); 
ALTER TABLE todolist_user ADD CONSTRAINT fk_todolist_user_1 FOREIGN KEY (todolist_id) references todolist(id); 

  
