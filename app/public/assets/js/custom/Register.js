Register = {
    init: function () {
        Register.formValidate()
    },

    formValidate: function() {
        $('#form-register').validate({
            ignore: null,
            submitHandler: function(form) {
                form.submit();
            },
            rules: {
                'Register[firstname]': 'required',
                'Register[lastname]': 'required',
                'Register[email]': 'required',
                'Register[gender]': 'required',
                'Register[birthdate]': 'required'
            },
            messages: {
                'Register[firstname]': 'Obrigatório',
                'Register[lastname]': 'Obrigatório',
                'Register[email]': 'Obrigatório',
                'Register[gender]': 'Obrigatório',
                'Register[birthdate]': 'Obrigatório',
            }
        });
    }
}

$('document').ready(function() {
    Register.init();
})
