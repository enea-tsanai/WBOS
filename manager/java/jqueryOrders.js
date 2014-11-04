var timerID = null;
  
$(document).ready(function () {

    function doReload() {
        $('#OrdersTable').jtable('reload');
        timerID = setTimeout(doReload,20000);
    }
    //Prepare jTable
    $('#OrdersTable').jtable({
        title: 'Πίνακας Παραγγελιών',
        paging: true, //Enable paging
        pageSize: 10, //Set page size (default: 10)
        sorting: true, //Enable sorting
        defaultSorting: 'Name ASC', //Set default sorting
        actions: {
            listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=orderTable_list'
        },
        toolbar: {
        items: [{
                icon: '/web_based_ordering_system/project/jtable.2.3.0/themes/basic/excel.png',
                text: 'Εξαγωγή XML',
                click: function () {
                    window.location = '/web_based_ordering_system/project/layers/logic/mysql.php?action=export-orders_xml';
                    //e.preventDefault();
                }
            }]
        },     
        fields: {

            Phones: {
                title: '',
                width: '5%',
                sorting: false,
                edit: false,
                create: false,
                display: function (OrderData) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="/web_based_ordering_system/project/images/orderr.png" title="Order expand" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        $('#OrdersTable').jtable('openChildTable',
                                $img.closest('tr'),
                                {
                                    title: OrderData.record.id + ' - Αρ. παραγγελίας',
                                    actions: {
                                        listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=orders&id=' + OrderData.record.id
                                    },
                                    fields: { 
                                        order_id: {
                                            type: 'hidden',
                                            defaultValue: OrderData.record.StudentId
                                        },
                                        product_name: {
                                            title: 'Όνομα προϊόντος',
                                            width: '10%'
                                        },
                                        feature: {
                                            title: 'Ιδιότητα',
                                            width: '10%'
                                        },
                                        extra_feature: {
                                            title: 'Επιπλέον υλικά',
                                            width: '20%'
                                        },
                                        extra_text: {
                                            title: 'Παρατηρήσεις',
                                            width: '30%'
                                        },
                                        extr_sum_price: {
                                            title: 'Κόστος επιπλέον προϊόντων (Euro)',
                                            width: '30%'
                                        },
                                        pro_sum_price: {
                                            title: 'Συνολικό κόστος προϊόντος (Euro)',
                                            width: '30%'
                                        },
                                        count: {
                                            title: 'Ποσότητα',
                                            width: '20%'
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
                title: 'Αρ.παραγγελίας',
                key: true,
                create: false,
                edit: false
            },
            total: {
                title: 'Ποσό (Euro)',
                width: '20%'
            },
            countProducts: {
                title: 'Αρ.προϊόντων',
                width: '20%'
            },
            waiter: {
                title: 'Σερβιτόρος',
                width: '20%'
            },
            datetime: {
                title: 'Χρονική στιγμή',
                width: '20%'
            },
        },
    });

    //Load order list from server
    $('#OrdersTable').jtable('load');

   // doReload();
});
