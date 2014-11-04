<?php
    include_once '../layers/logic/mysql.php';
    // include '/layers/logs/waiter_login.php';
?>

<!DOCTYPE html> 
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Multi-page template</title> 
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <!-- <script src="/web_based_ordering_system/project/java/waiter_side/waiter.js" type="text/javascript"></script> -->
    <style>
        .content-orderTable {   
            list-style:none;
            margin:0;
            font-size:110%;
        }

        .content-categories {
            list-style:none;
            margin:0;
        }
        .content-products {
            list-style:none;
            margin:0;
        }

    </style>
  
<script>

// GLOBAL VARS

    var Order=[],
        page="",
        count=[],
        loggedWaiter=[],
        Categories=[],
        ClickedProduct=[],
        ProductFeatures=[],
        ClickedCategoryId,
        ClickedOrderProductId;
        //a=false;
        console.log("initialize");

    $(document).ready(function() {

        page="";
        page = $.mobile.activePage.attr('id');
        // $("#"+page).hide();
        displayPageContent( page );

// LOGIN AND SESSION HANDLING FUNCTIONS

// Login ----------------

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

// Logout ----------------

        $('#logoutButton').live('click', function() {
            $.ajax({
                url: "/web_based_ordering_system/project/waiter_website/layers/logs/waiter_login.php?action=logout",
                type: "POST",
                dataType: "json",
                data: "",
                beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                success: function( response ) {
                    // console.log(response);
                    if(response["Result"] == "OK") {
                        page = "home_page";
                        loggedWaiter["username"] = "";
                        loggedWaiter["uid"] = "";
                        loggedWaiter["state"] = "loggedOUT";
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { 
                            transition: "slide"
                        });
                    }
                    else if (response["Result"] == "ERROR") {
                        page = "home_page";
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { 
                            transition: "slide"
                        });
                        // alert("Αποτυχία αποσύνδεσης");
                    }
                    else {
                        alert("ERROR");
                        page = "home_page";
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { 
                            transition: "slide"
                        });
                    }
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

            $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php", {
                transition: "slide"
            });
            // $.mobile.showPageLoadingMsg();
            return false;
        });

// Session ----------------

        function set_session( session ) {
            loggedWaiter = session;
        }

        function check_session() {
            // console.log(loggedWaiter);
            if( typeof(loggedWaiter['state'])!='undefined' && loggedWaiter['state'] == "loggedIN" ) {
                return true;
            } else return false;
        }

// USER PANEL OPTIONS

// Make a New Order ----------------

        $('#new_order').submit(function() {
            displayPageContent( "new_order_window" );
            return false;
        });

// View my submitted orders ----------------

        $('#my_orders').submit(function() {
            displayPageContent( "my_orders_window" );
            return false;
        });    

// NEW ORDER FUNCTIONS ----------------

// Get Elements ----------------

// Categories ----------------

        function getCategories() {
            // page="";
            $('#new_order_window').ready(function() {
                // $.mobile.showPageLoadingMsg();
            $.ajax({
                    url: "/web_based_ordering_system/project/layers/logic/mysql.php?action=categories",
                    type: "POST",
                    dataType: "json",
                    data: JSON.stringify(),                        // you can insert url argumnets here to pass to api.php
                    beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                    complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                    success: function(data) {
                        // console.log(data);
                        var data = $.parseJSON(JSON.stringify(data));
                        Categories = data;
                        var html = "";
                        html = "<li data-role='list-divider'>Κατηγορίες</li>";
                        if( data["Result"] == "OK") {
                            $.each(data.Records,function(object) {   // first loop of the object  
                                if(data.Records[object]["fid"] == -1 )
                                html += "<li><a href='#' category_id='" + data.Records[object]["id"] + "'>" + data.Records[object]["name"] +"</a></li>";
                            })
                            $('#categories_list').empty();
                            $('#categories_list').append (html);
                            $('#categories_list').listview("refresh");
                        } else if( data.Result == "ERROR") {
                            html += "<li> Καμία Καταχώρηση </li>";
                            $('#categories_list').empty();
                            $('#categories_list').append (html);
                            $('#categories_list').listview("refresh");
                        } else console.log("Request Error");
                        return data;
                    },
                    error: function(jqXHR,error, errorThrown) {  
                        if(jqXHR.status&&jqXHR.status==400){
                            alert(jqXHR.responseText); 
                        } else {
                            alert("Something went wrong");
                        
                        }
                    }
                });
            });
        }

// Display the Subcategories of the selected Category ----------------

        function display_sub_categories() {
            html = "<li data-role='list-divider'>Υποκατηγορίες</li>";
            $.each(Categories.Records,function(object) {   // first loop of the object  
                if(Categories.Records[object]["fid"] == ClickedCategoryId )
                html += "<li><a href='#' category_id='" + Categories.Records[object]["id"] + "'>" + Categories.Records[object]["name"] +"</a></li>";
            })
            $('#sub_categories_list').empty();
            $('#sub_categories_list').append (html);
            $('#sub_categories_list').listview("refresh");
        }

// Handle Click on a Categories list item ----------------

        $('#categories_list a').live('click', function() {
           check_sub_categories( $(this).attr('category_id') );
        });
        
// Handle Click on a Sub-Categories list item ----------------

        $('#sub_categories_list a').live('click', function() {
            check_sub_categories( $(this).attr('category_id') )
        });

// Check if any sub-categories exist before displaying any products ----------------

        function check_sub_categories( clicked_category_id ) {
            ClickedCategoryId = clicked_category_id;
           // console.log(Categories);
           // console.log(ClickedCategoryId);
            if(Categories.Records) {
                var sub_categories = false;
                $.each(Categories.Records,function(object) {
                    // console.log(Categories.Records[object]["fid"]); 
                    if (Categories.Records[object]["fid"] == ClickedCategoryId) {
                        sub_categories = true;                    
                        return false;
                    }
                });
                if(sub_categories == true) displayPageContent("sub_categories_window");
                else displayPageContent("products_window");
            }
        }

// Products ----------------

        function getProducts() {
            $('#products_window').ready(function() {
               // console.log("ClickedCategoryId selected = " + ClickedCategoryId);
                var data = {
                    id: ClickedCategoryId,
                    //  name: name.val(),
                    //  fid: lastname.val(),
                };
                // $.mobile.showPageLoadingMsg();
                $.ajax({
                    url: "/web_based_ordering_system/project/layers/logic/mysql.php?action=category_products",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                    complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                    success: function(data) {
                            // console.log(data);
                            var data = $.parseJSON(JSON.stringify(data));
                            var html = "";
                            html = "<li data-role='list-divider'>Προιόντα</li>";
                            html += "<li data-role='fieldcontain'>" +
                                    "<div class='ui-grid-b' style=font-size:80%>" +
                                        "<div class='ui-block-a'>Προιόν</div>" +
                                        "<div class='ui-block-b'>Τιμή</div>" +
                                        "<div class='ui-block-c'></div>" +
                                    "</div></li>"
                        if( data["Result"] == "OK") {
                            $.each(data.Records,function(object) {   // first loop of the object  
                                html += "<li><a href='#' product_id='" + data.Records[object]["id"] + "' product_name='" + data.Records[object]["name"] + "' product_price='" + data.Records[object]["price"] + "'>" + 
                                data.Records[object]["name"] + " " +
                                data.Records[object]["price"] + " Euro" +"</a></li>";
                            })
                            $('#products_list').empty();
                            $('#products_list').append (html);
                            $('#products_list').listview("refresh");
                        } else if( data.Result == "ERROR") {
                            html += "<li> Καμία Καταχώρηση </li>";
                            $('#products_list').empty();
                            $('#products_list').append (html);
                            $('#products_list').listview("refresh");
                        } else console.log("Request Error");

                    },
                    error: function(jqXHR,error, errorThrown) {  
                        if(jqXHR.status&&jqXHR.status==400){
                            alert(jqXHR.responseText); 
                        } else {
                            // alert("Something went wrong")
                        }
                    }
                });
            });
        }

// Handle click on a products list item

        $('#products_list a').live('click', function() {
            var _ClickedProduct=[];
            _ClickedProduct = {
                id: $(this).attr('product_id'),
                name: $(this).attr('product_name'),
                price:  parseFloat( $(this).attr('product_price') ),
                // category ,
                // feature ,
                //  count: ,
            };
            ClickedProduct = _ClickedProduct;
            getFeatures();
            setTimeout(function() {
                $("div[id=Product_dialog] form").empty();
                var extraF = false;
                var html = "";
                html += "<div data-role='fieldcontain'>";
                if(ProductFeatures.Records) {
                    html += "<fieldset id=add_features data-role='controlgroup' data-type='horizontal' data-mini='true'><p data-role='fieldcontain'>Προτίμιση</p>";
                    $.each(ProductFeatures.Records,function(object) {
                        if (ProductFeatures.Records[object]["type"] == "Μοναδικό") {
                            html += "<input type='radio' name='radio-choice-b' id='"+ ProductFeatures.Records[object]["id"] +"' value='" +
                            ProductFeatures.Records[object]["name"] + "'checked='checked' />" +
                            "<label for='"+ProductFeatures.Records[object]["id"]+"'>" + ProductFeatures.Records[object]["name"] + "</label>";
                        } else extraF = true;
                    });
                }
                html += "</div></fieldset>";
                if (extraF == true) {
                    html += "<div data-role='fieldcontain'>" +
                    "<fieldset id=add_extrafeatures data-role='controlgroup' data-type='vertical' data-mini='true'><p data-role='fieldcontain'>Επιπλέον Υλικά</p>";
                    $.each(ProductFeatures.Records,function(object) {
                        if (ProductFeatures.Records[object]["type"] == "Extra") {
                            html += "<input type='checkbox' name='checkbox-choice-c' id='"+ ProductFeatures.Records[object]["id"] +"' value='" +
                            ProductFeatures.Records[object]["name"] + "'price='" + ProductFeatures.Records[object]["price"] + "'/>" +
                            "<label for='"+ProductFeatures.Records[object]["id"]+"'>" + ProductFeatures.Records[object]["name"] + " : " +
                             ProductFeatures.Records[object]["price"] + " Euro" + "</label>";
                        }
                    })
                    html += "</div></fieldset>";
                }
                html +=  "<div>" +
                "<label for='slider-fill'>Ποσότητα:</label>" +
                "<input type='range' name='slider-fill' id='slider-fill' value='1' min='1' max='10' data-highlight='true' />" +
                "<div data-role='fieldcontain'>" +
                "    <label for='textarea'>Παρατηρήσεις</label>" +
                "    <textarea cols='40' rows='8' name='textarea' id='textarea' data-mini='true'></textarea>";
                $('#'+ page + ' div[id=Product_dialog] form').append(html).trigger('create');
                $( "#Product_dialog" ).popup( "open" );
            }, 2000 ); 
        });

// Handle POPUP submit form of the selected product

        $( "#submit" ).live('click', function() {
            // $( "#Product_dialog" ).popup( "close" );
            // console.log(ClickedProduct);
            var feature = $("input[name=radio-choice-b]:checked").val();
            if( typeof(feature) == 'undefined') feature = "-";
            var extrafeatures=[];
            var count_ = parseInt($("input[name=slider-fill]").val());
            // console.log("count_:" + count_);
            $("input[name=checkbox-choice-c]:checked").each(function() {
                var extrafeature=[];
                extrafeature.name = $(this).attr('value');
                extrafeature.price = parseFloat($(this).attr('price'));
                extrafeatures.push(extrafeature);
            });
            extrafeatures.sum_price = 0.0;
            for (i=0;i< extrafeatures.length;i++) {
                extrafeatures.sum_price += extrafeatures[i].price;
            }
            var extratext = $("textarea[name=textarea]").val();
            // console.log(feature);    
            // console.log(extrafeatures);            
            // console.log(Order);        
            ClickedProduct["feature"] = feature;
            ClickedProduct["extrafeatures"] = extrafeatures;
            ClickedProduct["extratext"] = extratext;
            var pos;
            for (i=0; i< Order.length; i++) {
                if( Order[i].id == (ClickedProduct["id"]) &&
                    Order[i].name == (ClickedProduct["name"]) &&
                    Order[i].price == (ClickedProduct["price"]) &&
                    Order[i].feature == (ClickedProduct["feature"]) &&
                    check_extrafeatures( i ) &&
                    Order[i].extratext == (ClickedProduct["extratext"]) ) { 
                        pos = i;
                }
            }
            if ( typeof( pos ) != 'undefined' ) {
                Order[pos].count += count_;
                Order[pos].sum_price = Order[pos].count * Order[pos].price;
                // displayOrdersTable();
            } else {
                Order.push(ClickedProduct);
                var last_ = Order.length - 1;
                if ( typeof(Order[last_].count) == 'undefined') {
                    Order[last_].feature = feature;
                    Order[last_].extrafeatures = extrafeatures;
                    Order[last_].extratext = extratext;
                    Order[last_].count = count_;
                    Order[last_].sum_price = Order[last_].count * Order[last_].price + extrafeatures.sum_price;
                }
                for (i=0;i< Order.length;i++) {
                    Order[i].pos = i;
                }
                // populateOrdersTable( ClickedProduct );
            }
            console.log(Order);
            updateOrder();
            displayOrdersTable();
        });

// Handle POPUP cancel form of the selected product

        $( "#cancel" ).live('click', function() {
            // $( "#Product_dialog" ).popup( "close" );
        });

// Features ----------------

        function getFeatures() {
            var data = {
                id: ClickedProduct["id"],
                // name: name.val(),
                // fid: lastname.val(),
                };
                // $.mobile.showPageLoadingMsg();
                $.ajax({
                    url: "/web_based_ordering_system/project/layers/logic/mysql.php?action=product_features",
                    type: "POST",
                    dataType: "json",
                    data: data,                        // you can insert url argumnets here to pass to api.php
                    beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                    complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                    success: function(data) {
                        var data = $.parseJSON(JSON.stringify(data));
                        ProductFeatures = data;
                        if( data["Result"] == "OK") {
                            // console.log("features Ok");
                            // console.log(data);
                        } else if( data.Result == "ERROR") {
                            // console.log("NO records");
                        } else alert("failed to connect to server");

                    },
                    error: function(jqXHR,error, errorThrown) {  
                        if(jqXHR.status&&jqXHR.status==400){
                            alert(jqXHR.responseText); 
                        } else {
                            // alert("Something went wrong");
                        }
                    }
                });
        }

// Check for extra features when submitting the POPUP form of the selected product ----------------

        function check_extrafeatures( pos ) {
            for(j=0; j<Order[pos].extrafeatures.length; j++) {
                if ( typeof ( ClickedProduct["extrafeatures"][j] ) == 'undefined' ) return false;
                else if ( Order[pos].extrafeatures[j].name != ClickedProduct["extrafeatures"][j].name ||
                    Order[pos].extrafeatures.length != ClickedProduct["extrafeatures"].length ) return false;
            }
            return true;
        }

// ORDER TABLE HANDLING FUNCTIONS ----------------

// Submit Order ----------------

        function submitOrder( order ) {
            var products = [];
            var ex = [];
            var totalProductsNum=0;
            for (i=0; i<order.length;i++) {
                for (j=0; j<order[i]["extrafeatures"].length;j++) {
                    ex.push(Order[i]["extrafeatures"][j]["name"]);
                }
                var extrafeatures_ = ex.join();
                ex.splice(0, ex.length);  
                var product = { 
                        "id" : Order[i]["id"],
                        "name" : Order[i]["name"],
                        "price" : Order[i]["price"],
                        "feature" : Order[i]["feature"],
                        "extrafeatures" : extrafeatures_,
                        "extratext" : Order[i]["extratext"],
                        "sum_price" : order[i]["sum_price"],
                        "f_sum_price" : order[i]["extrafeatures"]["sum_price"],
                        "count" : order[i]["count"]
                };
                products.push(product);
                totalProductsNum += order[i]["count"];
            }
            order.countProducts=totalProductsNum;
            // console.log(loggedWaiter);
            var data = {
                signature:{
                    "waiter_uid": loggedWaiter["uid"],
                    "waiter_username": loggedWaiter["username"],
                    "datetime": order.timestamp,
                    "total": order.total,
                    "countProducts": order.countProducts
                },
                products: products
            };
            // console.log(data);
            // $.mobile.showPageLoadingMsg();
            // console.log(data);
            $.ajax({
                url: "/web_based_ordering_system/project/layers/logic/mysql.php?action=submit_order",
                type: "POST",
                dataType: "json",
                data: data,
                beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                success: function(data) {
                    // console.log(data);
                    var data = $.parseJSON(JSON.stringify(data));
                    ProductFeatures = data;
                    if( data["Result"] == "Ok") {
                        console.log("Order submitted successfully");
                        // console.log(data);
                    } else if( data.Result == "ERROR") {
                        console.log("Error submitting order");
                    } else alert("failed to connect to server");
                },
                error: function(jqXHR,error, errorThrown) {  
                    if(jqXHR.status&&jqXHR.status==400){
                        alert(jqXHR.responseText); 
                    } else {
                        // alert("Something went wrong");
                    }
                }
            });
            Order.splice(0, Order.length); 
            updateOrder();
            displayPageContent( "user_panel");
        }

// Display Order Table ----------------

        function displayOrdersTable() {
            $('#new_order').ready(function() {
                // console.log(page);
                // $.mobile.showPageLoadingMsg();
                var html = "";
                html += "<li data-role='list-divider'>Παραγγελία</li>";
                html += "<li data-role='fieldcontain'>" +
                "<div class='ui-grid-d' style=font-size:80%>" +
                    "<div class='ui-block-a'>Προιόν</div>" +
                    "<div class='ui-block-b'>Τιμή</div>" +
                    "<div class='ui-block-c'>Περιγραφή</div>" +
                    "<div class='ui-block-d'>Επιπλέον</div>" +
                    "<div class='ui-block-e'></div>" +
                "</div></li>";

                var pageToAppend = page;
                $('#'+ page + ' div form ul').empty();
                $('#'+ page + ' div form ul').append (html);
                $('#'+ page + ' div form ul').listview("refresh");

                for (i=0;i< Order.length;i++) populateOrdersTable( Order[i]);
                var total = Order["total"];
                if( typeof(total) == 'undefined' ) total = 0.0;
                var html_close = "";
                html_close = "<li data-theme='e' style=font-size:85%> Συνολικό Ποσό Παραγγελίας: " + total + " Euro </li>" +
                 " <li class='ui-body ui-body-b'>"+
                " <fieldset class='ui-grid-a'>" +
                "    <div class='ui-block-a'><button type='submit' data-theme='d'>Καθαρισμός</button></div>" +
                "    <div class='ui-block-b'><button type='submit' data-theme='a'>Αποστολή</button></div>" +
                " </fieldset>" +
                "</li>";
                $('#'+ page + ' div form ul').append (html_close).trigger('create');
                $('#'+ page + ' div form ul').listview("refresh");
            });
        }

// Populate the Order Table with selected product elements ----------------

        function populateOrdersTable( product ) {
            // console.log("populateOrdersTable");
            // console.log(product);
            var count;
            if ( typeof(product["count"]) == 'undefined' ) count=1;
            else count = product["count"];
            var prod_extrafeatures = [];
            // console.log(product["extrafeatures"].length);
            for (k=0;k<product["extrafeatures"].length;k++) {
                prod_extrafeatures.push(product["extrafeatures"][k]["name"]);
            }
            if ( typeof(product["feature"]) == 'undefined' ) product["feature"] = "-";
            var html = "";
            html += "<li class='Product' id='" + product["pos"] + "' >" + 
            "<a><div class='ui-grid-d'>" +
            "<div class='ui-block-a'>" + product["name"] + "</div>" + 
            "<div class='ui-block-b'>" + product["price"] + " Euro</div>" +
            "<div class='ui-block-c'>" + product["feature"] + "</div>" +
            "<div class='ui-block-d'>" + prod_extrafeatures + " " + product["extrafeatures"].sum_price + " Euro</div>" +
            "<div class='ui-block-e'>" + product["extratext"] + "</div>" +
            "</div></a>" +
            "<span class='ui-li-count'>" + count + "</span>" +
            "<a class='remove'  data-icon='minus' data-transition='slideup' data-rel='dialog' href='#'' title='Remove' data-theme='c'></a>" + 
            "</li>" ;          
            $('#'+ page + ' div form ul').append (html).trigger('create');
            $('#'+ page + ' div form ul').listview("refresh");
            prod_extrafeatures.splice(0, prod_extrafeatures.length);  
        }

// Update the Order after adding or deleting a product element from Orders Table ----------------

        function updateOrder() {
            var total=0.0;
            for (i=0;i< Order.length;i++) {
                Order[i].pos = i;
                total += Order[i].sum_price;
            }
            Order.total = total;
            // console.log("updateOrder");
            // console.log(Order);
        }

// Handle clicks on the Order Table elements and buttons ----------------

        $('#order_table').live('click', function(event) {
            // clear selected row ---
            $(event.target).closest('.remove').each(function() {
                var $product = $(this).closest('.Product');
                var pos = $product.attr('id');
               // console.log("Deleted pos: " + pos + " from Order");
               // console.log(Order);
                if(pos!=-1) Order.splice(pos, 1);               
                $product.remove();
                updateOrder();
                displayOrdersTable();
            })
            // clear all the Order Table elements ---
            $(event.target).closest('.ui-block-a').each(function() {   
                Order.splice(0, Order.length);  
                updateOrder();
                displayOrdersTable();
            })
            // submit order ---
            $(event.target).closest('.ui-block-b').each(function() {
                // Order.waiter = current_waiter;
                var datetime = new Date();
                Order.timestamp = ISODateString( datetime );
                // console.log("datetime" + Order.timestamp);   
                var data = [];
                data = Order;
                if(data.length) {
                    console.log( data );
                    submitOrder( data );
                    // $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#user_panel", {
                    //   transition: "slide"
                    // });
                }
            })
        })

// Convert date format to complete desired format ----------------

        function ISODateString(d){
          function pad(n){return n<10 ? '0'+n : n}
          return d.getFullYear()+'-'
              + pad(d.getMonth()+1)+'-'
              + pad(d.getDate()) +' '
              + pad(d.getHours())+':'
              + pad(d.getMinutes())+':'
              + pad(d.getSeconds())
        }

// VIEW THE SUBMITTED ORDERS IN A TABLE OF ORDERS ELEMENTS

// Get My Order Elements from database  ----------------

         function getMyOrdersTable() {
            $('#my_orders_window').ready(function() {
               //  console.log("ClickedCategoryId selected = " + ClickedCategoryId);
               // console.log(loggedWaiter);
               // console.log("getMyOrdersTable");
               var waiterId = loggedWaiter["username"];
                var data = {
                    id: waiterId,
                //  name: name.val(),
                //  fid: lastname.val(),
                };
                // $.mobile.showPageLoadingMsg();
                $.ajax({
                    url: "/web_based_ordering_system/project/layers/logic/mysql.php?action=getMyOrdersTable",
                    type: "POST",
                    dataType: "json",
                    data: data,                        // you can insert url argumnets here to pass to api.php
                    beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                    complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                    success: function(data) {
                            // console.log(data);
                            var data = $.parseJSON(JSON.stringify(data));
                            var html = "";
                            // html = "<li data-role='list-divider'>Προιόντα</li>";
                            html += "<li data-role='list-divider'>" +
                            "<div class='ui-grid-d'>" +
                                "<div class='ui-block-a'>id</div>" +
                                "<div class='ui-block-b'>Σύνολο</div>" +
                                "<div class='ui-block-c'>Προϊόντα</div>" +
                                "<div class='ui-block-d'>Ώρα</div>" +
                                "<div class='ui-block-e'></div>" +
                            "</div></li>";
                        if( data["Result"] == "OK") {
                            $.each(data.Records,function(object) {   // first loop of the object  
                                html += "<li><a><div class='ui-grid-d' href='#' order_id='" + data.Records[object]["id"] + "'>" + 
                                "<a><div class='ui-grid-c'>" +
                                "<div class='ui-block-a'>" + data.Records[object]["id"] + "</div>" + 
                                "<div class='ui-block-b'>" + data.Records[object]["total"] + " $</div>" +
                                "<div class='ui-block-c'>" + data.Records[object]["countProducts"] + "</div>" +
                                "<div class='ui-block-d'>" + data.Records[object]["datetime"] + "</div>" +
                                "</div></a></li>";
                            })

                            $('#myorders').empty();
                            $('#myorders').append (html);
                            $('#myorders').listview("refresh");
                        } else if( data.Result == "ERROR") {
                            html += "<li> Καμία Καταχώρηση </li>";
                            $('#myorders').empty();
                            $('#myorders').append (html);
                            $('#myorders').listview("refresh");
                        } else console.log("Request Error");

                    },
                    error: function(jqXHR,error, errorThrown) {  
                        if(jqXHR.status&&jqXHR.status==400){
                            alert(jqXHR.responseText); 
                        } else {
                            alert("Something went wrong");
                        }
                    }
                });
            });
        }

// Display the My Orders Table ----------------

        function displayMyOrdersTable() {
            $('#MY_orders').ready(function() {
                // console.log(page);
                // console.log("displayMyOrdersTable");
                // $.mobile.showPageLoadingMsg();
                var html = "";
                html += "<li data-role='list-divider'>Οι Παραγγελείες μου</li>";
                var pageToAppend = page;
                $('#'+ page + ' div form ul').empty();
                $('#'+ page + ' div form ul').append (html);
                $('#'+ page + ' div form ul').listview("refresh");
                for (i=0;i< Order.length;i++) populateOrdersTable( Order[i]);
            });
        }

// Populate the My Orders Table with orders elements from database ----------------

        function populateΜyOrdersTable( OrdersTable ) {
            // console.log("populateOrdersTable");
            var html = "";
            html += "<li data-role='fieldcontain'>" +
            "<div class='ui-grid-d'>" +
                "<div class='ui-block-a'>id</div>" +
                "<div class='ui-block-b'>Σύνολο</div>" +
                "<div class='ui-block-c'>Προϊόντα</div>" +
                "<div class='ui-block-d'>Ώρα</div>" +
                "<div class='ui-block-e'></div>" +
            "</div></li>";
            html += "<li class='Product' id='" + OrdersTable["id"] + "' >" + 
            "<a><div class='ui-grid-d'>" +
                "<div class='ui-block-a'>" + OrdersTable["total"] + "</div>" + 
                "<div class='ui-block-b'>" + OrdersTable["countProducts"] + " $</div>" +
                "<div class='ui-block-c'>" + OrdersTable["datetime"] + "</div>" +
            "</div></a>" +
            "<span class='ui-li-count'>" + count + "</span>" +
            "<a class='remove' data-icon='minus' data-transition='slideup' data-rel='dialog' href='#'' title='Remove' data-theme='c'></a>" + 
            "</li>" ;          
            $('#'+ page + ' div form ul').append (html).trigger( 'create' );
            $('#'+ page + ' div form ul').listview( "refresh" );
            OrdersTable.splice( 0, prod_extrafeatures.length );  
        }       

// HANDLE PAGE CHANGES

        $('div:jqmData(role="page")').live('pagebeforeshow',function() {
            page = $.mobile.activePage.attr('id');
            // console.log("page changed-> " + page);
            switch (page) {
                case "login_window":
                    if( loggedWaiter["state"] == "loggedIN" ) {
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#user_panel", { transition: "slide" });
                        // console.log("login_window!!!");
                    }
                break;
                case "user_panel":
                    if( loggedWaiter["state"] != "loggedIN" ) {
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                    // console.log("login_window!!!");
                    }
                    // $("#user_panel").show();
                    // console.log("user_panel!!!");
                break;
                case "new_order_window":
                    if( loggedWaiter["state"] != "loggedIN" ) {
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                        // console.log("login_window!!!");
                    } else {
                        // console.log("new_order_window");
                        displayOrdersTable();
                        getCategories();
                        // getFeatures();
                    }
                break;
                case "products_window":
                    if( loggedWaiter["state"] != "loggedIN" ) {
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                        // console.log("login_window!!!");
                    } else {
                        displayOrdersTable();
                        getProducts();
                    }
                break;
                case "my_orders_window":
                    if( loggedWaiter["state"] != "loggedIN" ) {
                        $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                        // console.log("login_window!!!");
                    } else {
                        displayMyOrdersTable();
                        getMyOrdersTable();
                    }
                break;
            } 
        });

// DISPLAY PAGE FUNCTION

        function displayPageContent( page ) {
            // $("#user_panel").hide();
            // console.log("page: " + page);
            // $("#login_window").hide();
            // $("#user_panel").hide();
            var loggedIN = "";
            $.ajax({
                url: "/web_based_ordering_system/project/waiter_website/layers/logs/waiter_login.php?action=get_session",
                type: "POST",
                dataType: "json",
                data: "",
                beforeSend: function() { $.mobile.showPageLoadingMsg(); }, // Show spinner
                complete: function() { $.mobile.hidePageLoadingMsg() }, // Hide spinner
                success: function( response ) {
                    if(response["Result"] == "OK") {
                        loggedWaiter["username"] = response["username"];
                        loggedWaiter["uid"] = response["uid"];
                        loggedWaiter["state"] = "loggedIN";
                        set_session( loggedWaiter );
                        console.log(loggedWaiter);
                        switch (page) {
                            case "login_window":
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#user_panel", { transition: "slide" });
                                // console.log("login_window!!!");
                            break;
                            case "user_panel":
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#user_panel", { transition: "slide" });
                                // $("#user_panel").show();
                                // console.log("user_panel!!!");
                            break;
                            case "new_order_window":
                                // console.log("new_order_window");
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#new_order_window", { transition: "slide" });
                                displayOrdersTable();
                                getCategories();
                            break;
                            case "sub_categories_window":
                                // console.log("new_order_window");
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#sub_categories_window", { transition: "slide" });
                                display_sub_categories();
                                displayOrdersTable();
                            break;
                            case "products_window":
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#products_window", { transition: "slide" });
                                displayOrdersTable();
                                getProducts();
                            break;
                            case "my_orders_window":
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#my_orders_window", { transition: "slide" });
                                displayMyOrdersTable();
                                getMyOrdersTable();
                            break;
                        }
                    }
                    else if (response["Result"] == "No_Session") {
                        loggedWaiter["state"] = "loggedOUT";
                        console.log(loggedWaiter);
                        switch (page) {
                            case "login_window":
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                                // console.log("login_window!!!");
                            break;
                            case "user_panel":
                                page = "login_window";
                                // $(page).hide();
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                                // console.log("user_panel!!!");
                            break;
                            case "new_order_window":
                                // console.log("new_order_window");
                                // $(page).hide();
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                            break;
                            case "products_window":
                                // $(page).hide();
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                            break;
                            case "my_orders_window":
                                // $(page).hide();
                                $.mobile.changePage( "/web_based_ordering_system/project/waiter_website/waiter_test.php#login_window", { transition: "slide" });
                            break;
                        } 
                        // $("#login_window").show();
                        // alert("Αποσυνδεμένος");
                    }
                    else alert("ERROR");
                    // return false;
                },
                error: function(jqXHR,error, errorThrown) {  
                    if(jqXHR.status&&jqXHR.status==400){
                        alert(jqXHR.responseText); 
                    } else {
                        // alert("Something went wrong");
                    }
                    // return false;
                }
            });
            // return false;
        }
     });

</script>
</head>

<body>

    <!-- ////////////// Page 1 ////////////////////
    <?//php session_start();?>
    <div data-role="page" id="home_window" data-dom-cache="true">
      <div data-role="header">
        <h1>Home</h1>
      </div>

      <div data-role="content">
        <a href="#login_window" data-transition="slide"> Log In </a>
      </div>
    </div>
     -->
    <!-- ////////////// Page 1 //////////////////// -->

    <div data-role="page" id="login_window" data-add-back-btn="true">

        <div data-role="header">
            <h1>Σύνδεση</h1>
        </div>

        <div data-role="content">
            <p> Είσοδος στο σύστημα </p>

            
            <form id="login_form" name="login" method="post" action="waiter_login.php?action=login">
                <table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
                    <tr><td>
                        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
                            <tr>
                                <td colspan="3"><strong>Είσοδος Σερβιτόρου</strong></td>
                            </tr>
                            <tr>
                                <td width="78">Όνομα Χρήστη</td>
                                <td width="6">:</td>
                                <td width="294"><input name="username" type="text" id="myusername"></td>
                            </tr>
                            <tr>
                                <td>Κωδικός</td>
                                <td>:</td>
                                <td><input name="password" type="password" id="mypassword"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><input type="submit" value="Υποβολή"></td>
                            </tr>
                        </table>
                    </td></tr>
                </table>
            </form>
      
        </div>

    </div>

    <!-- ////////////// Page 2 //////////////////// -->

    <div data-role="page" id="user_panel" data-add-back-btn="true">

        <div data-role="header">
            <h1>Κεντρική</h1>
            <div id="logoutButton" data-type="horizontal" style="top:-5px;position:absolute;float:right;z-index:10;display:inline;" align="right" class="ui-btn-right"> 
                <a href="" data-role="button" data-icon="delete" data-ajax="false">Αποσύνδεση</a> 
            </div>
        </div>

        <form id="new_order" method="post" action="/web_based_ordering_system/project/layers/logic/mysql.php?action=category_list">
            <input type="submit" value="Νέα Παραγγελία" />
        </form>

        <form id="my_orders" method="post" action="/web_based_ordering_system/project/layers/logic/mysql.php?action=my_orders">
            <input type="submit" value="Οι παραγγελείες μου" />
        </form>

    </div>

    <!-- ////////////// Page 3 //////////////////// -->

    <div data-role="page" id="new_order_window" data-add-back-btn="true">
        
        <div id="new_order" data-role="header">
            <h1>Νέα Παραγγελία - Κατηγορίες</h1>
            <div id="logoutButton" data-type="horizontal" style="top:-5px;position:absolute;float:right;z-index:10;display:inline;" align="right" class="ui-btn-right"> 
                <a href="" data-role="button" data-icon="delete" data-ajax="false">Αποσύνδεση</a> 
            </div>
        </div>

        <div class="content-categories" data-role="content">
            <ul data-role="listview" id="categories_list" data-inset="true"></ul>
        </div>

        <div class="content-orderTable">
            <form>
                <ul data-role="listview" id="order_table" data-divider-theme='a' data-theme="c" data-inset="true"></ul>
            </form>
        </div>

    </div>

    <!-- ////////////// Page 4 //////////////////// -->

    <div data-role="page" id="sub_categories_window" data-add-back-btn="true">

        <div id="new_order" data-role="header">
            <h1>Νέα Παραγγελία - Υποκατηγορίες</h1>
            <div id="logoutButton" data-type="horizontal" style="top:-5px;position:absolute;float:right;z-index:10;display:inline;" align="right" class="ui-btn-right"> 
                <a href="" data-role="button" data-icon="delete" data-ajax="false">Αποσύνδεση</a> 
            </div>
        </div>

        <div class="content-categories" data-role="content">
            <ul data-role="listview" id="sub_categories_list" data-inset="true"></ul>
        </div>

        <div class="content-orderTable">
            <form>
                <ul data-role="listview" id="order_table" data-divider-theme='a' data-theme="c" data-inset="true"></ul>
            </form>
        </div>

    </div>


    <!-- ////////////// Page 5 //////////////////// -->

    <div data-role="page" id="products_window" data-add-back-btn="true">

        <div id="new_order" data-role="header">
            <h1>Νέα Παραγγελία - Προϊόντα</h1>
            <div id="logoutButton" data-type="horizontal" style="top:-5px;position:absolute;float:right;z-index:10;display:inline;" align="right" class="ui-btn-right"> 
                <a href="" data-role="button" data-icon="delete" data-ajax="false">Αποσύνδεση</a> 
            </div>
        </div>

        <div class="content-products" data-role="content">
            <ul data-role="listview" id="products_list" data-inset="true"></ul>
        </div>

        <div class="content-orderTable">
            <form>
                <ul data-role="listview" id="order_table" data-divider-theme='a' data-theme="c" data-inset="true"></ul>
            </form>
        </div>

        <div data-role="popup" id="Product_dialog" data-theme="a" data-mini="true" class="ui-corner-all">
            <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
            <form></form>
            <a href="#" id="cancel" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-mini="true">Ακύρωση</a>    
            <a href="#" id="submit" data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="b" data-mini="true">Υποβολή</a>  
        </div>
        
        </div><!-- /popup -->


    <!-- ////////////// Page 6 //////////////////// -->

    <div data-role="page" id="my_orders_window" data-add-back-btn="true">

        <div id="MY_orders" data-role="header">
            <h1>Οι παραγγελείες μου</h1>
            <div id="logoutButton" data-type="horizontal" style="top:-5px;position:absolute;float:right;z-index:10;display:inline;" align="right" class="ui-btn-right"> 
                <a href="" data-role="button" data-icon="delete" data-ajax="false">Αποσύνδεση</a> 
            </div>
        </div>
        
        <div class="myOrdersTable">
            <form>
                <ul data-role="listview"  id="myorders" data-divider-theme='e' data-theme="c" data-inset="true"></ul>
            </form>
        </div>

        <!--
        <div class="MyOrders" data-role="content">
            <ul data-role="listview" id="myorders" data-inset="true"></ul>
        </div>
        -->

    </div>

</body>
</html>