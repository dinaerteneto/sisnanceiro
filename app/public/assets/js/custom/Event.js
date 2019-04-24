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
            console.log();
            var href = "event/create";
            var target = "#remoteModal";
            var data = {
                'start_date': date,
                'end_date': date
            };
            $.ajax({
                url: href,
                data: data,
                success: function(html) {
                    $(target).html(html);
                },
                complete: function() {
                    $(target).modal('show');
                    Form.init();
                }
            });
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
    }
}
$('document').ready(function() {
    Event.init();
});