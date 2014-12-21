drop table 	cart_details;
drop table 	carts;
drop table 	order_details;
drop table 	orders;
drop table 	suppliers;
drop table 	products;
drop table 	product_groups;
drop table 	users;

drop 	FUNCTION 	add_to_cart 	;
drop 	FUNCTION 	convert_cart_to_order 	;
drop 	PROCEDURE 	get_cart 	;
drop 	PROCEDURE 	user_login 	;

--
--select ('drop table') as d, t.table_name, (';') as q from `information_schema`.tables t  where table_schema='groceries'
--
--select ('drop') as d, t.routine_type as rt, t.routine_name, (';') as q from `information_schema`.routines t where routine_schema='groceries'
--