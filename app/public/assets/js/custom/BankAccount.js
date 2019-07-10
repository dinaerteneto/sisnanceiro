BankAccount = {

    init: function() {
        BankAccount.dataTableInit();
    },

    dataTableInit: function() {
        Main.dataTableOptions.serverSide = true;
        Main.dataTableOptions.aaSorting = [
            [0, 'desc']
        ];
        Main.dataTableOptions.ajax = {
            url: "/bank-account",
            type: 'POST'
        };
        Main.dataTableOptions.columns = [{
                data: 'name',
                name: 'name',
                searchable: true
            },
            {
                data: 'initial_balance',
                name: 'initial_balance',
                searchable: false
            },
            {
                bSortable: false,
                mRender: function(data, type, row) {
                    var html = '<a href="/bank-account/view/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Visualizar" class="btn btn-xs btn-primary open-modal" target="#remoteModal"><i class="fa fa-search"></i></a> ';
                    html += '<a href="/bank-account/update/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Alterar status da venda" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
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

$(document).ready(function() {
    BankAccount.init();
})