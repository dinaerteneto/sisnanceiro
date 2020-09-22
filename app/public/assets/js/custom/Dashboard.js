Dashboard = {
    init: function () {
        Dashboard.initCalendar();
    },

    initCalendar: function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var hdr = {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'prev,today,next'
        };

        var calendar = $('#calendar').fullCalendar({
            locale: 'pt-br',
            header: hdr,

            selectable: false,
            selectHelper: false,
            editable: false,
            allDayDefault: true,

            header: {
                left: 'title', //,today
                center: '',
                right: '' //month, agendaDay,
            },

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
                    url: '/dashboard/calendar',
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
                                    className: r.className
                                });
                            });
                        }
                        callback(events);
                    }
                })
            }
        });

        /* hide default buttons */
        //$('.fc-toolbar .fc-right, .fc-toolbar .fc-center').hide();

        // calendar prev
        $('#calendar-buttons #btn-prev').click(function() {
            calendar.fullCalendar('prev');
            return false;
        });

        // calendar next
        $('#calendar-buttons #btn-next').click(function() {
            calendar.fullCalendar('next');
            return false;
        });

        // calendar today
        $('#calendar-buttons #btn-today').click(function() {
            calendar.fullCalendar('today');
            return false;
        });

        // calendar month
        $('#mt').click(function() {
            calendar.fullCalendar('changeView', 'month');
        });

        // calendar agenda week
        $('#ag').click(function() {
            calendar.fullCalendar('changeView', 'agendaWeek');
        });

        // calendar agenda day
        $('#td').click(function() {
            calendar.fullCalendar('changeView', 'agendaDay');
        });
    }
}
$('document').ready(function() {
    Dashboard.init();
})
