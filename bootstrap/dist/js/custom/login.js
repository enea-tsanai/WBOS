$('#login_form').submit(function() {
    //$.mobile.showPageLoadingMsg();
    $.ajax({
        url: "/web_based_ordering_system/project/waiter_website/layers/logs/waiter_login.php?action=login",
        type: "POST",
        dataType: "json",
        data: $("form#login_form").serialize(),
        beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
        complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
        success: function( response ) {
            if(response["Result"] == "OK") {
                loggedWaiter["username"] = response["username"];
                loggedWaiter["uid"] = response["uid"];
                page = "user_panel";
                displayPageContent( page );
            }
            else if (response["Result"] == "ERROR") alert("Λάθος όνομα χρήστη ή κωδικός");
            else alert("ERROR");
        },
        error: function(jqXHR,error, errorThrown) {  
            if(jqXHR.status&&jqXHR.status==400){
                alert(jqXHR.responseText); 
            } else {
                // alert("Something went wrong");
            }
        }
    });
    return false;
});