// Manager
$('#m_login-form').submit(function() {
    $.ajax({
        url: "/wbos/manager/layers/logs/waiter_login.php?action=login",
        type: "POST",
        dataType: "json",
        data: $("form#m_login-form").serialize(),
        // beforeSend: function() { $loading.show(); }, // Show spinner
        // complete: function() { $loading.hide()() }, // Hide spinner
        success: function( response ) {
            if(response["Result"] == "OK") {
                loggedWaiter["username"] = response["username"];
                loggedWaiter["uid"] = response["uid"];
                page = "user_panel";
                alert("Something went wrong");
                displayPageContent( page );
            }
            else if (response["Result"] == "ERROR") alert("Λάθος όνομα χρήστη ή κωδικός");
            else alert("ERROR");
        },
        error: function(jqXHR,error, errorThrown) {  
            if(jqXHR.status&&jqXHR.status==400){
                alert(jqXHR.responseText); 
            } else {
                alert("Something went wrong");
            }
        }
    });
    return false;
});
// Waiter
$('#w_login-form').submit(function() {
    $.ajax({
        url: "/wbos/waiter/layers/logs/waiter_login.php?action=login",
        type: "POST",
        dataType: "json",
        data: $("form#w_login-form").serialize(),
        // beforeSend: function() { $loading.show(); }, // Show spinner
        // complete: function() { $loading.hide()() }, // Hide spinner
        success: function( response ) {
            if(response["Result"] == "OK") {
                loggedWaiter["username"] = response["username"];
                loggedWaiter["uid"] = response["uid"];
                page = "user_panel";
                alert("Something went wrong");
                displayPageContent( page );
            }
            else if (response["Result"] == "ERROR") alert("Λάθος όνομα χρήστη ή κωδικός");
            else alert("ERROR");
        },
        error: function(jqXHR,error, errorThrown) {  
            if(jqXHR.status&&jqXHR.status==400){
                alert(jqXHR.responseText); 
            } else {
                alert("Something went wrong");
            }
        }
    });
    return false;
});