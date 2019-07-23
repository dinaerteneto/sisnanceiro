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
        BankTransaction.formValidate();
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

    formValidate: function() {

        $('#bank-transaction-form').validate({
            ignore: null,
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
    }
}

$('document').ready(function() {
    BankTransaction.init();
})