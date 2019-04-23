Event = {
    init: function() {

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
    }
}
$('document').ready(function() {
    Event.init();
});