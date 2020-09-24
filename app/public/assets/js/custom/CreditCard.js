jQuery.validator.addMethod("greaterThanZero", function(value, element) {
    var val = value.replace('.', '');
    val = val.replace(',', '.');
    return this.optional(element) || (parseFloat(val) > 0);
}, "* Amount must be greater than zero");

CreditCard = {
    init: function() {
        CreditCard.formValidate();
    },


    formValidate: function() {
        $('#credit-card-form').validate({
            ignore: null,
            submitHandler: function(form) {
                form.submit();
            },
            rules: {
                'CreditCard[limit]': {
                    required: true,
                    greaterThanZero: true
                },
                'CreditCard[name]': 'required',
                'CreditCard[credit_card_brand_id]': 'required',
                'CreditCard[bank_account_id]': 'required',
                'CreditCard[closing_day]': 'required',
                'CreditCard[payment_day]': 'required',
            },
            messages: {
                'CreditCard[limit]': {
                    required: 'Obrigatório',
                    greaterThanZero: 'Deve ser um valor maior que zero'
                },
                'CreditCard[name]': 'Obrigatório',
                'CreditCard[credit_card_brand_id]': 'Obrigatório',
                'CreditCard[bank_account_id]': 'Obrigatório',
                'CreditCard[closing_day]': 'Obrigatório',
                'CreditCard[payment_day]': 'Obrigatório',
            }
        });
    },

    submitForm: function() {
        $('body').on('submit', '#credit-card-form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.post(url, data, function(json) {
                if (json.success) {
                    $('#btn-search').trigger('click');
                    $('#remoteModal').modal('hide');
                    swal("Sucesso", "Sucesso!!!.", "success");
                } else {
                    swal("Oops...", "Ocorreu algum erro!!!.", "error");
                }
            }, 'json')
        });
    }
}

$('document').ready(function() {
    CreditCard.init();
})
