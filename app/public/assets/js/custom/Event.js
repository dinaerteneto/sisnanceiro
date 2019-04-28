Event = {
    init: function() {
        Event.initCalendar();
        Event.addEvent();
        Event.updEvent();
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
            },
            events: function(start, end, timezone, callback) {
                $.ajax({
                    url: '/event/load',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        start: start.format(),
                        end: end.format()
                    },
                    success: function(json) {
                        var events = [];
                        if (!!json.data) {
                            $.map(json.data, function(r) {
                                events.push({
                                    id: r.id,
                                    title: r.name,
                                    start: r.start_date,
                                    end: r.end_date
                                });
                            });
                        }
                        callback(events);
                    }
                })
            }
        });
    },

    openModalEvent: function(url, data) {
        var target = "#remoteModal";
        $.ajax({
            url: url,
            data: data,
            success: function(html) {
                $(target).html(html);
            },
            complete: function() {
                $(target).modal('show');
            }
        });
    },

    addEvent: function() {
        var calendar = $('#calendar').fullCalendar('getCalendar');
        calendar.on('dayClick', function(date, jsEvent, view) {
            Event.openModalEvent("event/create", { date: date.format('DD/MM/YYYY') });
        });
    },

    updEvent: function() {
        var calendar = $('#calendar').fullCalendar('getCalendar');
        calendar.on('eventClick', function(event, jsEvent, view) {
            Event.openModalEvent("event/update/" + event.id, {});
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