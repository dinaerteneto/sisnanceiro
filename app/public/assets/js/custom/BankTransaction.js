BankTransaction = {
    init: function() {
        BankTransaction.dataTableInit();
    },

    dataTableInit: function() {
        Main.dataTableOptions.serverSide = true;
        // Main.dataTableOptions.aaSorting = [
        //     [0, 'desc']
        // ];
        Main.dataTableOptions.ajax = {
            url: "/bank-transaction",
            type: 'POST'
        };
        Main.dataTableOptions.columns = [{
                // data: 'status',
                // name: 'status',
                bSortable: false,
                mRender: function(data, type, row) {
                    return '<i class="fa fa-circle" style="color: ' + row.status + '"></i>';
                }
            },
            { data: 'due_date' },
            { data: 'description' },
            { data: 'category_name' },
            { data: 'account_name' },
            { data: 'net_value' },
            {
                bSortable: false,
                mRender: function(data, type, row) {
                    var html = '<a href="/bank-transaction/update/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Alterar lançamento" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                    html += '<a href="/bank-transaction/delete/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Excluir lançamento" class="delete-record btn btn-xs btn-danger" data-title="Excluir este lançamento?" data-ask="Tem certeza que deseja excluir este lançamento?"><i class="fa fa-times"></i></a> ';
                    return html;
                }
            }
        ];
        var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

        $('#dt_basic').on('draw.dt', function() {
            $('[rel="tooltip"]').tooltip();
        });
    }
}

$('document').ready(function() {
    BankTransaction.init();
})