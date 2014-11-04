$(function() {
    var data = [];

    var new_dialog = function (type, row) {
        var dlg = $("#dialog-form").clone();
        var uid = dlg.find(("#uid")),
            username = dlg.find(("#username")),
            name = dlg.find(("#name")),
            lastname = dlg.find(("#lastname")),
            phone_number = dlg.find(("#phone_number"));
            password = dlg.find(("#password"));
            confirm_password = dlg.find(("#confirm_password"));
            add_photo = dlg.find(("#add_photo"));
            state = dlg.find(("#state"));
            tips = $( ".validateTips" );
            allFields = $( [] ).add( username ).add( name ).add( lastname ).add( phone_number ).add( password ).add( confirm_password ).add( add_photo ),
        type = type;
        var configCreate = {
            autoOpen: true,
            height: 520,
            width: 350,
            modal: true,
            buttons: {
                "Δημιουργία": function() {
                    tips = $( ".validateTips" );
                    allFields.removeClass( "ui-state-error" );
                    var ValidForm = bValidfields();   

                    if ( ValidForm ) {     
                        updateTips( "Όλα τα πεδία είναι απαραίτητα." );
                        data = {
                          username: username.val(),
                          name: name.val(),
                          lastname: lastname.val(),
                          phone_number: phone_number.val(),
                          password: password.val(),
                          confirm_password: confirm_password.val(),
                          photo: add_photo.val(),
                        };
                        $.ajax({ 
                            url: '/web_based_ordering_system/project/layers/logic/mysql.php?action=create_waiter',                  //the script to call to get data          
                            type: 'POST',
                            data: data,                        //you can insert url argumnets here to pass to api.php
                            dataType: 'json',                //data format   
                            // async:false,   
                            success: function(response)          //on recieve of reply
                            {
                                var response = $.parseJSON(JSON.stringify(response));
                                console.log(response);
                                $.each(response,function(object){ //first loop of the object      
                                    $( "#users tbody" ).append( "<tr>" +
                                        "<td class='uid' > " + response[object]["uid"] + "</td>" +
                                        "<td class='username' >" + response[object]["username"] + "</td>" +
                                        "<td class='name' > " + response[object]["name"] + "</td>" +
                                        "<td class='lastname' > " + response[object]["lastname"] + "</td>" +
                                        "<td class='phone_number' > " + response[object]["phone_number"] + "</td>" +
                                        "<td class='password' > " + response[object]["password"] + "</td>" +
                                        "<td><img id='image' src='" + response[object]["photo"] + "' height='42' width='42' /></td>" + 
                                        "<td class='state' > " + response[object]["state"] + "</td>" +
                                        "<td><a href='' class='edit'>Edit</a></td>" + "<td><span class='delete'><a href=''>Delete</a></span></td>" + 
                                        "</tr>" );  
                                })
                              },
                            error: function (response) {
                                 console.log(response);
                            }
                        });  
                        $( this ).dialog( "close" );
                    }
                },
                "Ακύρωση": function () {
                    updateTips( "Όλα τα πεδία είναι απαραίτητα." );
                    (dlg).remove();
                }
            },
            
        };
        var configEdit = {
            autoOpen: true,
            height: 520,
            width: 350,
            modal: true,
            buttons: {
                "Υποβολή": function() {
                    tips = $( ".validateTips" );
                    allFields.removeClass( "ui-state-error" );
                    var editValidForm = bValidfields();

                    if ( editValidForm ) {
                        updateTips( "Όλα τα πεδία είναι απαραίτητα." );
                        
                        data = {
                            uid: uid,
                            username: username.val(),
                            name: name.val(),
                            lastname: lastname.val(),
                            phone_number: phone_number.val(),
                            password: password.val(),
                            confirm_password: confirm_password.val(),
                            photo: add_photo.val(),
                        };
                        row.remove();

                        $.ajax({ 
                            url: '/web_based_ordering_system/project/layers/logic/mysql.php?action=edit_waiter',                  //the script to call to get data          
                            type: 'POST',
                            data: data,                        //you can insert url argumnets here to pass to api.php
                            dataType: 'json',                //data format   
                            // async:false,   
                            success: function(response)          //on recieve of reply
                            {
                                var response = $.parseJSON(JSON.stringify(response));
                                console.log(response);
                                $.each(response,function(object){ //first loop of the object      
                                    $( "#users tbody" ).append( "<tr>" +
                                        "<td class='uid' > " + response[object]["uid"] + "</td>" +
                                        "<td class='username' >" + response[object]["username"] + "</td>" +
                                        "<td class='name' > " + response[object]["name"] + "</td>" +
                                        "<td class='lastname' > " + response[object]["lastname"] + "</td>" +
                                        "<td class='phone_number' > " + response[object]["phone_number"] + "</td>" +
                                        "<td class='password' > " + response[object]["password"] + "</td>" +
                                        "<td><img id='image' src='" + response[object]["photo"] + "' height='42' width='42' /></td>" + 
                                        "<td class='state' > " + response[object]["state"] + "</td>" +
                                        "<td><a href='' class='edit'>Edit</a></td>" + "<td><span class='delete'><a href=''>Delete</a></span></td>" + 
                                        "</tr>" );  
                                })
                            },
                            error: function (response) {
                                 console.log(response);
                            }
                        });  
                        $( this ).dialog( "close" );
                    }                        
                },
                "Ακύρωση": function () {
                    updateTips( "Όλα τα πεδία είναι απαραίτητα." );
                    $( this ).dialog( "close" );
                }
            },
            
        };
        if (type === 'Edit') {
            get_data();
            configEdit.title = "Επεξεργασία εγγραφής",
            dlg.dialog(configEdit);
        }
        else if (type === 'Create') {
            configCreate.title = "Δημιουργία νέας εγγραφής",
            dlg.dialog(configCreate);
        }

        function bValidfields() {
            var bValid = true;
            bValid = bValid && checkLength( username, "username", 3, 16 );
            bValid = bValid && checkRegexp( username, /^[a-z]([0-9a-z_])+$/i, "Το username μπορεί να αποτελείται μόνο από τα a-z, 0-9, _, και να ξεκινά με γράμμα." );
            bValid = bValid && checkLength( name, "ονόματος", 3, 16 );
            bValid = bValid && checkRegexp( name, /^([a-zA-Z])|([α-ωά-ώΑ-Ω])+$/i, "Το όνομα μπορεί να αποτελείται μόνο από τα a-zA-Z ή α-ζΑ-Ζ." );
            bValid = bValid && checkLength( lastname, "επώνυμου", 3, 16 );
            bValid = bValid && checkRegexp( lastname, /^([a-zA-Z])|([α-ωά-ώΑ-Ω])+$/i, "Το επώνυμο μπορεί να αποτελείται μόνο από τα a-zA-Z." );
            bValid = bValid && checkLength( phone_number, "αρ. τηλεφώνου", 3, 80 );
            bValid = bValid && checkRegexp( phone_number, /^([0-9])+$/, "Ο αριθμός τηλεφώνου μπορεί να αποτελείται μόνο από αριθμούς." );
            bValid = bValid && checkLength( password, "κωδικού", 5, 16 );
            bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Ο κωδικός μπορεί να αποτελείται μόνο από τα a-z 0-9." );
            bValid = bValid && checkConfirmPassword( password, confirm_password , "Οι κωδικοί δεν ταιριάζουν." );
            return bValid;
        }

        function get_data() {
            var _username = $(row.children().get(1)).text().replace(/ /g,''),
                _name = $(row.children().get(2)).text().replace(/ /g,''),
                _lastname = $(row.children().get(3)).text().replace(/ /g,''),
                _phone_number = $(row.children().get(4)).text().replace(/ /g,''),
                _password = $(row.children().get(5)).text().replace(/ /g,''),
                _add_photo = $(row.children().get(6)).text().replace(/ /g,'');

                uid = $(row.children('td.uid')).text().replace(/ /g,'');
                username.val(_username);
                name.val(_name);
                lastname.val(_lastname);
                phone_number.val(_phone_number);
                password.val(_password);
                add_photo.val(_add_photo);
        }
    };

    function updateTips( t ) {
        tips
        .text( t )
        .addClass( "ui-state-highlight" );
        setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }

    function checkLength( o, n, min, max ) {
        if ( o.val().length > max || o.val().length < min ) {
            o.addClass( "ui-state-error" );
            updateTips( "Το μήκος του " + n + " πρέπει να είναι μεταξύ " +
            min + " και " + max + "." );
            return false;
        } else {
            return true;
        }
    }
 
    function checkRegexp( o, regexp, n ) {
        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass( "ui-state-error" );
            updateTips( n );
            return false;
      } else {
            return true;
      }
    }

	function checkConfirmPassword( pwd, cpwd, n ) {
      if ( pwd.val() != cpwd.val() ) {
		pwd.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
	}

    $.ajax({ 
      url: '/web_based_ordering_system/project/layers/logic/mysql.php?action=list',                  //the script to call to get data          
      type: 'POST',
      data: JSON.stringify(),                        //you can insert url argumnets here to pass to api.php
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',                //data format      
      beforeSend: function() {},
      success: function(data)          //on recieve of reply
      {
  	    //	console.log(data);
  		  $.each(data,function(object){   //first loop of the object	
  			  $( "#users tbody" ).append( "<tr>" +
              "<td class='uid' > " + data[object]["uid"] + "</td>" +
  			  "<td class='username' > " + data[object]["username"] + "</td>" +
              "<td class='name' > " + data[object]["name"] + "</td>" +
  			  "<td class='lastname' > " + data[object]["lastname"] + "</td>" +
  			  "<td class='phone_number' > " + data[object]["phone_number"] + "</td>" +
              "<td class='password' > " + data[object]["password"] + "</td>" +
  			  "<td><img id='image' src='" + data[object]["photo"] + "' height='42' width='42' /></td>" + 
              "<td class='state' > " + data[object]["state"] + "</td>" +
          "<td><a href='' class='edit'>Edit</a></td>" + "<td><span class='delete'><a href=''>Delete</a></span></td>" + 
          "</tr>" );
		    })
      }
    });

    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 500,
      width: 450,
      modal: true,
      buttons: {
      }         
    });

    $(document).on('click', 'span.delete', function () {
        // alert($(this).text());
        var datapost = [],
        datapost = {
            uid: $(this).closest('tr').children('td.uid').text().replace(/ /g,''),
        }

        $.ajax({ 
            url: '/web_based_ordering_system/project/layers/logic/mysql.php?action=delete_waiter',                  //the script to call to get data          
            type: 'POST',
            data: datapost,                        //you can insert url argumnets here to pass to api.php
            dataType: 'json',                       //data format   
            // async:false,   
            success: function(response) {
                var response = $.parseJSON(JSON.stringify(response));
                console.log(response);
            },
            error: function(jqXHR,error, errorThrown) {  
               if(jqXHR.status&&jqXHR.status==400){
                    alert(jqXHR.responseText); 
               }else{
                    alert("Something went wrong");
               }
            }
        });  
        
        $(this).closest('tr').find('td').fadeOut(1000,

                function () {
       // $(this).parents('tr:first').remove();
        });

        return false;
    });
 
    $(document).on('click', 'td a.edit', function () {
        new_dialog('Edit', $(this).parents('tr'));
        return false;
    });

    $("#create-user").button().on('click',function () {
        new_dialog('Create',$(this));
        return false;
    });
     
  });