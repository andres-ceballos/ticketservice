$(document).ready(function () {
    //DEFINE TYPE OF USER MESSAGING TO SHOW STYLES IN CHAT
    var type_user_msg = 'RECEIVER';
    //NOTIFICATION NUMBER WHEN NO EXISTS NEW MESSAGES FOR YET...
    var notification_count = 1;

    //PUT DATA INTO MODAL EDIT USER
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

    //SUBMIT MESSAGE CHAT
    $('#form-message').on('submit', function (e) {
        e.preventDefault();

        //TYPE OF USER SENDING MESSAGES
        type_user_msg = 'SENDER';

        //DATA FORM-MESSAGE
        var formData = $(this).serializeArray();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content") },
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            dataType: "json",
            success: function (data) {
                //console.log(data);

                //EMPTY INPUT MESSAGES CHAT
                $('#message_reply').val('');
                type_user_msg = 'RECEIVER';

                //IF NOTIFICATION FOR USER EXISTS... SHOULD BE PUT THE VAL IN THE NOTIFICATION COUNT
                if (data.notification_user > 0) {
                    notification_count = data.notification_user;
                    notification_count++;
                    //IF NOTIFICATION FOR TECH EXISTS...
                } else if (data.notification_tech > 0) {
                    notification_count = data.notification_tech;
                    notification_count++;
                    //console.log(notification_count);
                }

                //DATA FOR UPDATE NOTIFICATION COLUMNS IN DATABASE
                dataNotification = {
                    'action': 'update_notification',
                    'type_user': data.type_user,
                    'incident_id': data.incident_id,
                    'message_counter': notification_count,
                }

                //UPDATE METHOD
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content") },
                    type: "PUT",
                    url: "/incident/" + data.incident_id,
                    data: dataNotification,
                    dataType: "json",
                    success: function (data) {

                        //console.log(data);

                    }, error: function () {
                        console.log('Error!');
                    }
                });

            }, error: function () {
                console.log('Error!');
            }
        });
    });

    //CHAT EVENT-WEBSOCKET FOR SHOW MESSAGE, DATETIME IN REALTIME WITH STYLES
    Echo.channel('chat').listen('NewMessage', (e) => {
        
        //IF USER TECH OR USER NORMAL ACCESS TO CHAT.. RESET NEW MESSAGE COUNTER
        //AVAILABLE WHEN USER SENDER DON'T REFRESH DE PAGE
        if (e.message.reset_count == 1) {
            notification_count = 1;
        } else {
            //console.log(e.message);
            //SHOW NEW MESSAGES IN CHAT FOR USER WHO WRITE
            if (type_user_msg == 'SENDER') {
                $(".chat-message:last").append(
                    '<div class="d-block d-flex flex-row-reverse my-2">' +
                    '<div class="d-block order-2 bg-info w-50 rounded-lg px-3 py-3 pull-right">' +
                    '<p class="m-0">' + e.message.message_reply + '</p>' +
                    ' <small class="d-flex flex-row-reverse">' + e.message.created_at + '</small>' +
                    '</div>' +
                    '</div>');
                //SHOW NEW MESSAGES IN CHAT FOR USER WHO READ
            } else {
                $(".chat-message:last").append(
                    '<div class="d-block">' +
                    '<div class="d-block order-1 bg-dark text-white w-50 rounded-lg px-3 py-3 my-2">' +
                    '<p class="m-0">' + e.message.message_reply + '</p>' +
                    ' <small class="d-flex flex-row-reverse">' + e.message.created_at + '</small>' +
                    '</div>' +
                    '</div>');
            }
            //DEFINE THE ROW WHERE THE MESSAGE NOTIFICATION (SPAN) WILL BE SHOWN
            message_notification_incident = '#message-notification-' + e.message.incident_id;
            //VAR FOR CREATE .AFTER SPAN ID
            message_notification_html = 'message-notification-' + e.message.incident_id;
            //
            //SHOW NEW MESSAGES AND NOTIFICATION IN INDEX FOR TECHS AND USERS
            //IF USER IS TECH... MODIFY INDEX USER
            //console.log(e.message.type_user);
            if (e.message.type_user == 'Tech') {
                //DEFINE THE ROW WHERE THE NEW MESSAGE WILL BE SHOWN
                message_tech_incident = '#message-tech-' + e.message.incident_id;

                //ADD NEW MESSAGE ONLY IN THE ROW AND CELL DEFINE FOR ID
                $(message_tech_incident).text(e.message.message_reply);

                //IF THE SPAN NOTIFICATION EXIST... ONLY MODIFY COUNT NUMBER
                if ($(message_notification_incident).length) {
                    //TAKE VAL OF SPAN ELEMENT
                    var eleMessage = $(message_notification_incident).text();
                    //NOTIFICATION COUNT TAKE VALUE INCREMENT BY 1 FROM SPAN ELEMENT
                    notification_count = eleMessage;
                    notification_count++;
                    //console.log(notification_count);
                    //PUT NEW VALUE IN ROW SPAN ELEMENT CORRESPONDENT
                    $(message_notification_incident).text(notification_count)

                    //ELSE... ADD SPAN STRUCTURE FOR NEW NOTIFICATIONS
                } else {
                    $(message_tech_incident).after(
                        '<span id="' + message_notification_html + '" class="bg-primary mx-2 px-2 text-white rounded-pill">' + notification_count + '</span>'
                    );
                }
                //IF USER IS NORMAL USER... MODIFY INDEX TECH
            } else if (e.message.type_user == 'User') {
                //DEFINE THE ROW WHERE THE NEW MESSAGE WILL BE SHOWN
                message_user_incident = '#message-user-' + e.message.incident_id;

                //ADD NEW MESSAGE ONLY IN THE ROW AND CELL DEFINE FOR ID
                $(message_user_incident).text(e.message.message_reply);

                //IF THE SPAN NOTIFICATION EXIST... ONLY MODIFY COUNT NUMBER
                if ($(message_notification_incident).length) {
                    //TAKE VAL OF SPAN ELEMENT
                    var eleMessage = $(message_notification_incident).text();
                    //NOTIFICATION COUNT TAKE VALUE INCREMENT BY 1 FROM SPAN ELEMENT
                    notification_count = eleMessage;
                    notification_count++;
                    //console.log(notification_count);
                    //PUT NEW VALUE IN ROW SPAN ELEMENT CORRESPONDENT
                    $(message_notification_incident).text(notification_count)

                    //ELSE... ADD SPAN STRUCTURE FOR NEW NOTIFICATIONS
                } else {
                    $(message_user_incident).after(
                        '<span id="' + message_notification_html + '" class="bg-primary mx-2 px-2 text-white rounded-pill">' + notification_count + '</span>'
                    );
                }
            }
        }
    });
});
