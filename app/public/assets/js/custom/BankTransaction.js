jQuery.validator.addMethod("greaterThanZero", function(value, element) {
    var val = value.replace('.', '');
    val = val.replace(',', '.');
    return this.optional(element) || (parseFloat(val) > 0);
}, "* Amount must be greater than zero");

BankTransaction = {
    init: function() {

        if ($('#BankInvoiceDetail_bank_category_id').length > 0) {
            BankTransaction.initSelect2();
        }
        BankTransaction.setPaid();
        BankTransaction.formValidate();
        BankTransaction.submitForm();

    },

    initSelect2: function() {
        $('#BankInvoiceDetail_bank_category_id').select2({
            data: data,
            formatNoMatches: function(term) {
                return 'Nenhum produto encontrado.';
            },
            formatSearching: function() {
                return 'Procurando...';
            },
            formatResult: function(data) {
                return data.html;
            },
            formatSelection: function(data) {
                return data.selection;
            },
            escapeMarkup: function(m) {
                return m;
            }
        });
    },

    setPaid: function() {
        $('body').on('click', '.set-paid', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            $.post(href, function(response) {
                if (response.success) {
                    $.smallBox({
                        title: "Sucesso!",
                        content: "<i class='fa fa-clock-o'></i> <i>Lançamento definido como pago com sucesso.</i>",
                        color: "#659265",
                        iconSmall: "fa fa-check fa-2x fadeInRight animated",
                        timeout: 4000
                    });
                    dataTables.ajax.reload();
                }
            }, 'json');
        });
    },

    formValidate: function() {
        $('#bank-transaction-form').validate({
            ignore: null,
            submitHandler: function(form) {
                form.submit();
            },
            rules: {
                'BankInvoiceDetail[net_value]': {
                    required: true,
                    greaterThanZero: true
                },
                'BankInvoiceDetail[due_date]': 'required',
                'BankInvoiceDetail[bank_category_id]': 'required',
                'BankInvoiceDetail[bank_account_id]': 'required',
            },
            messages: {
                'BankInvoiceDetail[net_value]': {
                    required: 'Obrigatório',
                    greaterThanZero: 'Deve ser um valor maior que zero'
                },
                'BankInvoiceDetail[due_date]': 'Obrigatório',
                'BankInvoiceDetail[bank_category_id]': 'Obrigatório',
                'BankInvoiceDetail[bank_account_id]': 'Obrigatório',
            }
        });
    },

    submitForm: function() {
        $('body').on('submit', '#bank-transaction-form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.post(url, data, function(json) {
                if (json.success) {
                    $('#btn-search').trigger('click');
                    $('#remoteModal').modal().hide();
                    swal("Sucesso", "Sucesso!!!.", "success");
                } else {
                    swal("Oops...", "Ocorreu algum erro!!!.", "error");
                }
            }, 'json')
        });
    }
}

$('document').ready(function() {
    BankTransaction.init();
})