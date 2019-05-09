Event = {
    init: function() {
        Event.initCalendar();
        Event.addEvent();
        Event.updEvent();
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
            eventRender: function(event, element) {
                element.popover({
                    animation: true,
                    delay: 300,
                    content: '<strong>' + event.title + '</strong><br/>' + event.description,
                    trigger: 'hover',
                    html: true
                });
            },
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
                                    description: r.description,
                                    start: r.start_date,
                                    end: r.end_date,
                                    icon: 'fa-lock'
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
            Event.openModalEvent("/event/create", { date: date.format('DD/MM/YYYY') });
        });
    },

    updEvent: function() {
        var calendar = $('#calendar').fullCalendar('getCalendar');
        calendar.on('eventClick', function(event, jsEvent, view) {
            Event.openModalEvent("/event/update/" + event.id, {});
        });
    }
}
$('document').ready(function() {
    Event.init();
});