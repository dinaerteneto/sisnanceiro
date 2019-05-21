Event = {
    init: function() {
        Event.initCalendar();
        Event.addEvent();
        Event.updEvent();
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
            editable: true,
            droppable: true,

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

        $(".js-status-update a").click(function() {
            var selText = $(this).text();
            var $this = $(this);
            $this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
            $this.parents('.dropdown-menu').find('li').removeClass('active');
            $this.parent().addClass('active');
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