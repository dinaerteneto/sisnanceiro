BankAccount = {

    init: function() {
        BankAccount.dataTableInit();
        BankAccount.changeIsBank();
        $('body').on('change', '#BankAccount_physical', function() {
            BankAccount.changeTypePerson($(this).val());
        });
    },

    changeTypePerson: function(sValue) {
        $('#BankAccount_cpf_cnpj').removeClass('cpf');
        $('#BankAccount_cpf_cnpj').unmask();
        if (sValue == 1) {
            //pessoa fisica
            $('#BankAccount_cpf_cnpj').mask('999.999.999-99');
            $('#BankAccount_legal_name').prev().html('Nome completo');
            $('#BankAccount_cpf_cnpj').prev().html('CPF');
        } else {
            //pessoa juridica
            $('#BankAccount_legal_name').prev().html('Razão social');
            $('#BankAccount_cpf_cnpj').prev().html('CNPJ');
            $('#BankAccount_cpf_cnpj').mask('99.999.999/9999-99');
        }
    },

    changeIsBank: function() {
        $('body').on('click', "#bankaccount_send_bank_account, #bankaccount_default_online_transaction", function() {
            BankAccount.requiredDataAccount();
        });
    },

    requiredDataAccount: function() {
        $('div#data-account').hide();
        $('#bankaccount-send_bank_account').removeAttr('disabled');

        /*
        if ($('#bankaccount-default_online_transaction').is(':checked')) {
            if (!$('#bankaccount-send_bank_account').is(':checked')) {
                $('#bankaccount-send_bank_account').trigger('click');
            }
            $('#bankaccount-send_bank_account').attr('disabled', 'disabled');
            $('div#data-account').removeClass('hidden');
            $('div#data-account').show();
        }
        */

        if ($("#bankaccount_send_bank_account").is(':checked')) {
            $('div#data-account').removeClass('d-none');
            $('div#data-account').show();
        }
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
                    var html = '<a href="/bank-account/update/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Alterar dados da conta" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                    html += '<a href="/bank-account/delete/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Excluir esta conta" class="delete-record btn btn-xs btn-danger" data-title="Excluir esta conta?" data-ask="Tem certeza que deseja excluir a conta: ' + row.name + '?"><i class="fa fa-times"></i></a> ';
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