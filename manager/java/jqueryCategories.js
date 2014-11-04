$(document).ready(function () {                                                    

    $('#CategoryTable').jtable({
            title: 'Πίνακας Κατηγοριών',
            paging: false, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: false, //Enable sorting
            defaultSorting: 'Name ASC', //Set default sorting
            toolbar: { 
                items : false
            },
            actions: {
                listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=categories',
                createAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=create_category',
                updateAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=update_category',
                deleteAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=delete_category',
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
                description: {
                    title: 'Περιγραφή',
                    width: '40%'
                },
                fid: {
                    title: 'Γονική Κατηγορία',
                    width: '40%',
                    options: '/web_based_ordering_system/project/layers/logic/mysql.php?action=show_category'
                },

                
            }
        });
        
        $('#CategoryTable').jtable('load');
});