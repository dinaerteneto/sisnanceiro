var PaymentTax = {
    init: function() {
        PaymentTax.addCreditCardTax();
        PaymentTax.delCreditCardTax();
        PaymentTax.onSubmit();
    },
    addCreditCardTax: function() {
        $('body').on('click', '#add-credit-card-tax', function(){
            $('.credit-card-field').first().clone().appendTo($('#credit-card-body'));
            $('input[name="CreditCard[order][]"]').last().val('');
            $('input[name="CreditCard[value][]"]').last().val('');
            Form.masks();
        });
    },
    delCreditCardTax: function() {
        $('body').on('click', '.del-credit-card-tax', function(){
            if($('.del-credit-card-tax').length <= 1) {
                return false;
            }
            $(this).parents('.credit-card-field').first().remove();
        });
    },
    validate: function() {
        var error = '';
        if($('.credit-card-value').length > 0) {
            $('.credit-card-value').each(function(index, element) {
                if($(element).val() == '') {
                    error = 'Todos os valores da taxa (%) devem ser preenchidos.';
                }
            });
        }
        var order = [];
        if($('.credit-card-order').length > 0) {
            $('.credit-card-order').each(function(index, element) {
                if($(element).val() == '') {
                    error = 'Todos N. de vezes devem ser preenchidos.';
                } else {
                    order.push($(element).val());
                }
            });
            var newOrder = order.slice().sort();
            for(var i = 0; i < newOrder.length -1; i++) {
                if(newOrder[i + 1] == newOrder[i]) {
                    error = 'Não pode haver número de vezes igual.';
                }
            }
        }
        return error;
    },
    onSubmit: function() {
        $('body').on('submit', '#form-payment-tax', function(e) {
            e.preventDefault();
            var error = PaymentTax.validate();
            if(error != '') {
                $('.payment-tax-form .error').html('<div class="alert alert-danger" id="form-payment-tax-error" role ="alert">' + error + '</div>');
                return false;
            } else {
                return true;
            }
        });
    }
}
$('document').ready(function(){
    PaymentTax.init();
})
