$(document).ready(function () {

    $('ul.thumbnails li div.space').each(function(index) {
        var space = $(this);
        $.ajax({
            url: '?get_size='+$(this).attr('data-path'),
            beforeSend: function () {
                space.html('...');
            },
            success: function(data) {
                space.html(data);
            }
        });
    });


    $('#vhost-example-button').click(function() {
        $('#vhost-example').toggle(200);
    });

    $('.project').click(function() {
        project = prompt("Project name?", "PROJECT");
        $('.project').each(function() {
            $(this).html(project);
        });
    });

    $('.path').click(function() {
        project = prompt("Path ?", "project");
        $('.path').each(function() {
            $(this).html(project);
        });
    });
});
