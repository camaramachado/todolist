SET IDENTITY_INSERT item ON; 

INSERT INTO item (id,todolist_id,name,prior_item_id,item_order) VALUES (1,1,'papel A4',null,1); 

INSERT INTO item (id,todolist_id,name,prior_item_id,item_order) VALUES (2,1,'canetas',null,2); 

INSERT INTO item (id,todolist_id,name,prior_item_id,item_order) VALUES (3,1,'caneta azul',2,3); 

INSERT INTO item (id,todolist_id,name,prior_item_id,item_order) VALUES (4,1,'caneta vermelha',2,4); 

INSERT INTO item (id,todolist_id,name,prior_item_id,item_order) VALUES (5,2,'Folha de ponto',null,1); 

INSERT INTO item (id,todolist_id,name,prior_item_id,item_order) VALUES (6,3,'Horas extras',null,1); 

SET IDENTITY_INSERT item OFF; 

SET IDENTITY_INSERT todolist ON; 

INSERT INTO todolist (id,name,owner_id,company_id,uuid,prior_list_id,list_order,color) VALUES (1,'Expedição',1,1,'juah93k',null,1,'#a8dadc'); 

INSERT INTO todolist (id,name,owner_id,company_id,uuid,prior_list_id,list_order,color) VALUES (2,'RH',1,1,'84jfkl3',null,2,'#ffb703'); 

INSERT INTO todolist (id,name,owner_id,company_id,uuid,prior_list_id,list_order,color) VALUES (3,'Folha de pagamento',2,1,'490j49t',2,3,'#2a9d8f'); 

SET IDENTITY_INSERT todolist OFF; 

SET IDENTITY_INSERT todolist_user ON; 

INSERT INTO todolist_user (id,todolist_id,user_id) VALUES (1,1,1); 

INSERT INTO todolist_user (id,todolist_id,user_id) VALUES (2,2,1); 

INSERT INTO todolist_user (id,todolist_id,user_id) VALUES (3,3,2); 

SET IDENTITY_INSERT todolist_user OFF; 
