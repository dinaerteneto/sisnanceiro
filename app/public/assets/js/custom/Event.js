Event = {
    init: function() {
        Form.masks();
        Event.initCalendar();
        Event.addEvent();
        Event.formValidate();
    },

    initCalendar: function() {
        var hdr = {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'prev,today,next'
        };

        $('#calendar').fullCalendar({
            header: hdr,
            editable: true,
            droppable: true,
            windowResize: function(event, ui) {
                $('#calendar').fullCalendar('render');
            }
        });
    },

    addEvent: function() {
        var calendar = $('#calendar').fullCalendar('getCalendar');
        calendar.on('dayClick', function(date, jsEvent, view) {
            console.log('clicked on ' + date.format());
        });
    },

    formValidate: function() {
        $('#event-form').validate({
            rules: {
                'Event[name]': 'required',
                'Event[start_date]': 'required',
                'Event[start_time]': 'required',
                'Event[end_date]': 'required',
                'Event[end_time]': 'required',
                'Event[zipcode]': 'required',
                'Event[address]': 'required',
                'Event[address_number]': 'required',
                'Event[city]': 'required'
            },
            messages: {
                'Event[name]': 'Obrigatório',
                'Event[start_date]': 'Obrigatório',
                'Event[start_time]': 'Obrigatório',
                'Event[end_date]': 'Obrigatório',
                'Event[end_time]': 'Obrigatório',
                'Event[zipcode]': 'Obrigatório',
                'Event[address]': 'Obrigatório',
                'Event[address_number]': 'Obrigatório',
                'Event[city]': 'Obrigatório'
            },
            highlight: function(element) {
                $(element).removeClass('is-valid').addClass('is-invalid');
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    },

    formValidateBootstrap: function() {
        $('#event-form').bootstrapValidator({
            container: '#messages',
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                'Event[name]': {
                    validators: {
                        notEmpty: {
                            message: 'Nome do evento é obrigatório'
                        }
                    }
                },
                'Event[start_date]': {
                    validators: {
                        notEmpty: {
                            message: 'Data de início do evento é obrigatório'
                        }
                    }
                },
                'Event[start_time]': {
                    validators: {
                        notEmpty: {
                            message: 'Hora de início do evento é obrigatório'
                        }
                    }
                },
                'Event[end_date]': {
                    validators: {
                        notEmpty: {
                            message: 'Hora de término do evento é obrigatório'
                        }
                    }
                },
                'Event[end_time]': {
                    validators: {
                        notEmpty: {
                            message: 'Hora de término do evento é obrigatório'
                        }
                    }
                },
                'Event[zipcode]': {
                    validators: {
                        notEmpty: {
                            message: 'Cep do evento é obrigatório'
                        }
                    }
                },
                'Event[address]': {
                    validators: {
                        notEmpty: {
                            message: 'Endereço do evento é obrigatório'
                        }
                    }
                },
                'Event[address_number]': {
                    validators: {
                        notEmpty: {
                            message: 'Número do evento é obrigatório'
                        }
                    }
                },
                'Event[city]': {
                    validators: {
                        notEmpty: {
                            message: 'Cidade do evento é obrigatório'
                        }
                    }
                },
            }
        });
    }
}
$('document').ready(function() {
    Event.init();
});