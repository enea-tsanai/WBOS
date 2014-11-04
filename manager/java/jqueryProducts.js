$(document).ready(function () {                                                    

    $('#ProductTable').jtable({
        title: 'Πίνακας Προιόντων',
		paging: false, //Enable paging
        pageSize: 10, //Set page size (default: 10)
        sorting: false, //Enable sorting
        defaultSorting: 'Name ASC', //Set default sorting
        toolbar: { 
            items : false
        },
        actions: {
            listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=products',
            createAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=create_product',
            updateAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=update_product',
            deleteAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=delete_product',
        },
        fields: {
            id: {
                key: true,
                list: false,
				edit: false,
            },
            name: {
                title: 'Όνομα',
                width: '40%'
            },
            price: {
                title: 'Τιμή (Euro)',
                width: '40%'
            },
     		description: {
                title: 'Περιγραφή',
                width: '40%'
            },
			category_id: {
                title: 'Γονική Κατηγορία',
                width: '40%',
                options: '/web_based_ordering_system/project/layers/logic/mysql.php?action=show_category'
            },

			
        }
    });
	
	$('#ProductTable').jtable('load');
});