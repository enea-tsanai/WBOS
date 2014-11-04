$(document).ready(function () {

    $('#FeaturesTable').jtable({
            title: 'Πίνακας Επιπρόσθετων Προιόντων',
            paging: false, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: false, //Enable sorting
            defaultSorting: 'Name ASC', //Set default sorting
            toolbar: { 
                items : false
            },
            actions: {
                listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=features',
                createAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=create_feature',
                updateAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=update_feature',
                deleteAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=delete_feature'
            },
            fields: {
                    Phones: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    display: function (FeaturesData) {
                        //Create an image that will be used to open child table
                        var $img = $('<img src="/web_based_ordering_system/project/images/orderr.png" title="Order expand" />');
                        //Open child table when user clicks the image
                        $img.click(function () {
                            $('#FeaturesTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: FeaturesData.record.name + ': Συσχέτιση με Προιόντα',
                                        actions: {
                                            listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=getPF_association&id=' + FeaturesData.record.id,
                                            deleteAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=deletePF_association&id=' + FeaturesData.record.id,
                                            createAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=create_PF_association&id='+ FeaturesData.record.id
                                        },
                                        fields: {
                                            product_id: {
                                                key: true,
                                                list: false
                                            }, 
                                            name: {
                                                title: 'Όνομα προϊόντος',
                                                width: '40%',
                                                options: '/web_based_ordering_system/project/layers/logic/mysql.php?action=show_PF_association_options'
                                            },
                                        }
                                    }, function (data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                        });
                        return $img;
                    }
                },
                id: {
                    key: true,
                    list: false,
                    edit: false
                },
                name: {
                    title: 'Όνομα',
                    width: '40%'
                },
                price: {
                    title: 'Τιμή (Euro)',
                    width: '40%',
                    defaultValue: 0.0
                },
                type: {
                    title: 'Είδος',
                    width: '40%',
                    options: '/web_based_ordering_system/project/layers/logic/mysql.php?action=show_feature_type_options'
                },               
            }
    });
        $('#FeaturesTable').jtable('load');
});