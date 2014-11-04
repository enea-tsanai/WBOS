// JavaScript Document
/*********Validations********/

function foo(waiter_id)
{
			document.write("<h1>Hello World!</h1><p>Have a nice day!</p>");
}


function foo()
{
			document.write("<h1>Hello World!</h1><p>Have a nice day!</p>");
}


function delete_confirm(waiter_username,waiter_id)
{
	reply = confirm("Διαγραφή του σερβιτόρου " + waiter_username +" ;");
	
	if(reply == false)
	{
		window.location = "location:waiters.php?action=view_waiter";
	}
	else
	{
		window.location = "waiters.php?action=delete_waiter&waiter_id="+ waiter_id;
	}
}


