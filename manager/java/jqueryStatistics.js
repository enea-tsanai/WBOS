var timerID = null;
function doReload() {
     $('#Statistics').jtable('reload');
     timerID = setTimeout(doReload,180000);
}
var Period,ProductsDisplay;
$(document).ready(function () {  

	$( "#IncomesRadio" ).buttonset();
	$('div[id=IncomesRadio] input[name=radio]').click(function() {
	    
		if ($(this).val() === '1') {
			//console.log($(this).val() );
			Period = "day";
			console.log(Period);
			diplayTable( Period );
		} else if ($(this).val() === '2') {
			Period = "month";
			console.log(Period);
			diplayTable( Period );
		} else if ($(this).val() === '3') {
			Period = "year";
			console.log(Period);
			diplayTable( Period );
		}

			function diplayTable( period ) {
				$('#Statistics').jtable('destroy');
				var html = "<div id='Statistics'></div>";
				$('#StatisticsPageIncomes').append (html);
				console.log(period);
				$('#Statistics').jtable({
		            title: 'Πίνακας Εσόδων',
					paging: false, //Enable paging
		            pageSize: 10, //Set page size (default: 10)
		            sorting: false, //Enable sorting
		            defaultSorting: 'Name ASC', //Set default sorting
		            toolbar: {
		                items: [{
		                        icon: '/web_based_ordering_system/project/jtable.2.3.0/themes/basic/excel.png',
		                        text: 'Εξαγωγή Excel',
		                        click: function () {
		                            /////////*******************///////////
		                            window.location = '/web_based_ordering_system/project/layers/logic/mysql.php?action=export-statistics_incomes_xml&period=incomes-statistics_'+ period;
		                            //e.preventDefault();
		                        }
		                    }]
		                }, 
		            actions: {
		                listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=incomes-statistics_'+ period
		            },
		            fields: {
		                year: {
		                    title: 'Έτος',
		                    width: '50%'
		                },
		                month: {
		                    title: 'Μήνας',
		                    width: '10%'
		                },
		                day: {
		                    title: 'Ημέρα',
		                    width: '10%'
		                },
		                total: {
		                    title: 'Τζίρος ($)',
		                    width: '50%'
		                },
		         		countProducts: {
		                    title: 'Αρ.Προιόντων',
		                    width: '50%'
		                },
		            },
		        });
			$('#Statistics').jtable('load');

			}

	});	

	$( "#ProductsRadio" ).buttonset();
	$('div[id=ProductsRadio] input[name=radio]').click(function() {
	    
		//console.log($('div[id=ProductsRadio] input[type=text]').val());
		var numOfProds = $('div[id=ProductsRadio] input[type=text]').val();

		if( (numOfProds == "") || (numOfProds == '0') ) numOfProds = 5; 
		if ($(this).val() === '1') {
			//console.log($(this).val() );
			ProductsDisplay = "most_sold";
			//console.log(ProductsDisplay);
			diplayTable( ProductsDisplay );
		} else if ($(this).val() === '2') {
			ProductsDisplay = "least_sold";
			//console.log(ProductsDisplay);
			diplayTable( ProductsDisplay );
		}
			function diplayTable( productsdisplay ) {
				$('#Statistics').jtable('destroy');
				var html = "<div id='Statistics'></div>";
				$('#StatisticsPageProducts').append (html);
				console.log(productsdisplay);
				$('#Statistics').jtable({
		            title: 'Προιόντα',
					paging: false, //Enable paging
		            pageSize: 10, //Set page size (default: 10)
		            sorting: false, //Enable sorting
		            defaultSorting: 'Name ASC', //Set default sorting
		            toolbar: {
		                items: [{
		                        icon: '/web_based_ordering_system/project/jtable.2.3.0/themes/basic/excel.png',
		                        text: 'Εξαγωγή XML',
		                        click: function () {
		                            /////////*******************///////////
		                            window.location = '/web_based_ordering_system/project/layers/logic/mysql.php?action=export-statistics_products_xml&display='+productsdisplay+'&numofprods='+numOfProds;
		                            //e.preventDefault();
		                        }
		                    }]
		                }, 
		            actions: {
		                listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=products-statistics&display='+productsdisplay+'&numofprods='+numOfProds
		            },
		            fields: {
		                product_name: {
		                	title: 'Ον.Προιόντος',
		                    width: '50%'
		                },
						countProducts: {
		                    title: 'Πωλήσεις',
		                    width: '50%'
		                },
		                total: {
		                    title: 'Εσόδα (Euro)',
		                    width: '50%'
		                },
		            },
		        });
				$('#Statistics').jtable('load');

			}

	});	
	//doReload();

});