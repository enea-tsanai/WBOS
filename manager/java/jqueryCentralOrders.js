var timerID = null;

$(document).ready(function () {

    function doReload() {
        $('#centralOrdersTable').jtable('reload');
        timerID = setTimeout(doReload,15000);
    }
    //Prepare jTable
    $('#centralOrdersTable').jtable({
        title: 'Νεότερες Παραγγελίες',
        actions: {
            listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=central_orderTable_list'
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
                        $('#centralOrdersTable').jtable('openChildTable',
                                $img.closest('tr'),
                                {
                                    title: OrderData.record.id + ' - Αρ. παραγγελίας',
                                    actions: {
                                        listAction: '/web_based_ordering_system/project/layers/logic/mysql.php?action=orders&id=' + OrderData.record.id
                                    },
                                    fields: { 
                                        order_id: {
                                            type: 'hidden',
                                            defaultValue: OrderData.record.order_id
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
                                            title: 'Κόστος επιπλέον προϊόντων',
                                            width: '30%'
                                        },
                                        pro_sum_price: {
                                            title: 'Συνολικό κόστος προϊόντος',
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
                    //Return image to show on the person row
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

    $('#centralOrdersTable').jtable('load');
    doReload();
});
