$(document).ready(function () {
    $('#edit-user').on('show.bs.modal', function (e) {
        var modal = $(this);
        var user = $(e.relatedTarget).data('user');

        var url = '{{route("admin.update", ":id")}}'
        url = url.replace(':id', user.id);

        modal.find('#form-edit-user').attr('action', url);
        modal.find('#firstname').val(user.firstname);
        modal.find('#lastname').val(user.lastname);
        modal.find('#email').val(user.email);
        modal.find('#phone_ext').val(user.phone_ext);
        modal.find('#role_id').val(user.role_id);
    });

    Echo.channel('chat').listen('NewMessage', (e) => {
        console.log(e.message);
    });
});
