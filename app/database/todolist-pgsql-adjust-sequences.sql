SELECT setval('item_id_seq', coalesce(max(id),0) + 1, false) FROM item;
SELECT setval('todolist_id_seq', coalesce(max(id),0) + 1, false) FROM todolist;
SELECT setval('todolist_user_id_seq', coalesce(max(id),0) + 1, false) FROM todolist_user;