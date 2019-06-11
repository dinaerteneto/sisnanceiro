Person = {

    contNewContact: 0,
    contNewAddress: 0,

    init: function() {
        Person.addContact();
        Person.addAddress();
        Person.removeContainer();
    },

    addContact: function() {
        $('#add-contact').on('click', function(e) {
            e.preventDefault();
            Person.contNewContact++;
            $.get('/person/add-contact', { id: 'N' + Person.contNewContact }, function(html) {
                $('#container-contact').append(html);
                Form.masks();
            });
        });
    },

    addAddress: function() {
        $('#add-address').on('click', function(e) {
            e.preventDefault();
            Person.contNewAddress++;
            $.get('/person/add-address', { id: 'N' + Person.contNewAddress }, function(html) {
                $('#container-address').append(html);
                Form.masks();
            });
        });
    },

    removeContainer: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('body').on('click', '.remove-container', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var containerName = $(this).attr('data-target-container');
            var container = $(this).closest('.' + containerName);
            console.log(container);
            $.post(href, function(json) {
                if (json.success) {
                    $(container).remove();
                }
            }, 'json');
        });
    }

}

$('document').ready(function() {
    Person.init();
});