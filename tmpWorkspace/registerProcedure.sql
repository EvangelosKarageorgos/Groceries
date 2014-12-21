begin
    SET @existingLogin = 0;
	SET @custNo = -1;
	
	SET @existingLogin = (select count(*) from Users where login=ilogin);
	if (@existingLogin = 0) then 
    begin
		INSERT INTO Users (login, password, cust_name, email, street, town, post_code, cr_limit, curr_bal)
		VALUES (ilogin, ipass, ifullname, iemail, istreet, itown, ipostcode, 200, 0);
		SET @custNo = LAST_INSERT_ID();		
	end;
	end if;
	
	select @isAuth as IsAuth, @custNo as custNum;
    
end