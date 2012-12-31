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
        project = string_to_slug(prompt("Project name?", "PROJECT"));
        $('.project').each(function() {
            $(this).html(project);
        });
    });

});

function string_to_slug(str) {
    str = str.replace(/^\s+|\s+$/g, '');
    str = str.toLowerCase();

    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to   = "aaaaeeeeiiiioooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++)
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));

    str = str.replace(/[^a-z0-9 -]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-');

    return str;
}
