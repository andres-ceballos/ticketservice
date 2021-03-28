$(document).ready(function () {
    //****************************************** GLOBAL

    //ALERT NOTIFICATIONS
    if ($('.notification-alert').length) {
        $('.notification-alert').delay(3000).fadeOut(500);
        $('.container-notification').delay(3500).fadeOut(500);
    }

    //****************************************** ADMIN PANEL

    //PUT DATA INTO MODAL EDIT USER
    $('#edit-user').on('show.bs.modal', function (e) {
        var modal = $(this);

        //SAVE USER'S DATA SELECT FOR EDIT BUTTON IN VAR USER
        var user = $(e.relatedTarget).data('user');

        //TAKE VAR GLOBAL URL FROM APP.BLADE
        var url = url_admin_update;
        url = url.replace(':id', user.id);

        //PUT DATA IN MODAL EDIT USER
        modal.find('#form-edit-user').attr('action', url);
        modal.find('#firstname').val(user.firstname);
        modal.find('#lastname').val(user.lastname);
        modal.find('#email').val(user.email);
        modal.find('#phone_ext').val(user.phone_ext);
        modal.find('#role_id').val(user.role_id);
    });

    //MAIN CHECKBOX FOR SELECT OR DESELECTED ALL CHEXBOXES SECONDARIES
    $('#select-all-checkbox').on('change', function () {
        if ($(this).prop('checked')) {
            $('.checkbox-delete').prop('checked', true);
        } else {
            $('.checkbox-delete').prop('checked', false);
        }
    });

    //BUTTON FOR DELETE REGISTERS SELECTED
    $('.btn-all-delete').on('click', function (e) {
        e.preventDefault();

        var checkbox_selected = [];

        //SAVE ID CHECKBOXES SELECTED INTO ARRAY
        $('.checkbox-delete:checked').each(function () {
            checkbox_selected.push($(this).attr('data-id'));
        });

        if (checkbox_selected.length <= 0) {
            alert('Debes seleccionar por lo menos un registro para esta acción');
            //
            //IF SELECTED AT LEAST 1 ROW...
        } else {
            var confirmation_delete = confirm('¿Estás seguro de eliminar los registros seleccionados?');

            if (confirmation_delete == true) {
                //SEPARETE BY ',' ALL IDs IN THE ARRAY
                //TO BE PROCESSED IN THE DESTROY-ALL CONTROLLER METHOD
                var join_checkbox_selected = checkbox_selected.join(',');

                //console.log(join_checkbox_selected);
                //DELETE SELECT CHECKBOXES
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content") },
                    type: 'DELETE',
                    url: $(this).data('url'),
                    data: 'ids=' + join_checkbox_selected,
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            //DELETE ROW IN VIEW FOR REGISTERS DESTROY
                            $('.checkbox-delete:checked').each(function () {
                                $(this).parents('tr').remove();
                            });

                            //ADD NOTIFICATION
                            $('.main-container').prepend(
                                '<div class="container position-relative d-flex flex-row-reverse">' +
                                '<div style="z-index: 1;" class="notification-alert alert alert-success position-absolute m-0 mt-1 col-md-4">' +
                                '<p class="m-0 text-center font-weight-bold">' +
                                data.success +
                                '</p>' +
                                '</div>' +
                                '</div>'
                            );
                            //DESELECT MAIN CHECKBOX
                            $('#select-all-checkbox').prop('checked', false);

                            //NOTIFICATION DISSAPEAR
                            $('.notification-alert').delay(3000).fadeOut(500);
                            //
                        } else {
                            alert('Algo ha salido mal. Por favor recarga la página e intenta nuevamente.')
                        }

                    }, error: function () {
                        console.log('Error!');
                    }
                });
            }
        }
    });

    //****************************************** TECH PANEL

    //CREATE INCIDENT-WEBSOCKET FOR REMOVE OR
    //BUILD AND SHOW NEW ROW TABLE INCIDENT IN TECH PANEL
    Echo.channel('incident').listen('NewIncident', (e) => {
        if (e.incident.action == 'delete-row') {
            //console.log(e.incident);

            //REMOVE ROW WHEN ACCEPT INCIDENT FOR EVERYONE ELSE IN PANEL TECH TABLE
            delete_row_incident = '.btn-accept-' + e.incident.incident_id;
            $(delete_row_incident).parents('tr').remove();

        } else {
            var url = url_incident_update;
            url = url.replace(':id', e.incident['detail_incident'].incident_id);

            $('.no-registers-row').remove();

            //NEW ROW TABLE STRUCTURE
            $('.table-body').prepend(
                ' <tr class="text-center">' +
                '<td class="align-middle">' + e.incident['incident'].title + '</td>' +
                '<td class="align-middle" style="min-width: 20rem; max-width: 20rem;">' +
                '<div class="d-flex">' +
                '<div id="message-user-' + e.incident['detail_incident'].incident_id + '" class="col-10 text-truncate">' +
                e.incident['incident'].message_reply +
                '</div>' +
                '<span id="message-notification-' + e.incident['detail_incident'].incident_id + '" class="bg-primary mx-2 px-2 text-white rounded-pill">1</span>' +
                '</div>' +
                '</td>' +
                '<td class="align-middle">' + e.incident['incident'].firstname + ' ' + e.incident['incident'].lastname + '</td>' +
                '<td class="align-middle">' +
                '<p class="m-0">NO</p>' +
                '</td>' +
                '<td class="align-middle">' +
                '<small>' +
                '<p class="m-0 bg-danger border border-danger py-1 px-1 rounded-lg ">SIN REALIZAR</p>' +
                '</small>' +
                '</td>' +
                '<td class="align-middle">0</td>' +
                '<td class="align-middle">' + e.incident['incident_created'] + '</td>' +
                '<td class="align-middle">' +
                '<form action="' + url + '" method="POST">' +
                _token +
                method +
                '<input type="hidden" name="action" value="update_tech_id">' +
                '<button class="btn btn-md btn-secondary">Aceptar</button>' +
                '</form>' +
                '</td>' +
                '<td class="align-middle">&nbsp;</td>' +
                '</tr>'
            );

            //ADD NOTIFICATION
            $('.notification-new-incident').prepend(
                '<div class="container container-notification">' +
                '<div class="row justify-content-center">' +
                '<div class="col-md-12">' +
                '<div class="card">' +
                '<div style="z-index: 1;" class="notification-alert alert alert-info position-absolute m-0 mt-1 col-md-4">' +
                '<p class="m-0 text-center font-weight-bold">' +
                'Nueva solicitud generada' +
                '</p>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>'
            );

            $('.notification-alert').delay(3000).fadeOut(500);
        }
    });

    //****************************************** USER PANEL

    //CREATE INCIDENT-WEBSOCKET FOR SHOW NEW TECH NAME ASSIGNED FOR INCIDENT IN USER PANEL
    Echo.channel('panel-notification').listen('NewPanelNotification', (e) => {
        //console.log(e.panel_notification);

        //SHOW NOTIFICATION NEW TECH ASSIGNED IN PANEL USER
        if (e.panel_notification.panel == 'User') {
            //UNIQUE ROW AFFECTED TO ADD TECH NAME
            tech_assigned_incident = '.tech-assigned-' + e.panel_notification.incident_id;

            //ADD TECH NAME AND PILL NEW NOTIFICATION
            $(tech_assigned_incident).append(
                e.panel_notification.tech_name +
                '<br><span class="bg-primary mx-2 px-2 text-white rounded-pill">' +
                'Nuevo' +
                '</span>'
            );

            //SHOW NOTIFICATION NEW RATING IN PANEL TECH
        } else if (e.panel_notification.panel == 'Tech') {
            star_rating_incident = '.star-rating-' + e.panel_notification.incident_id;

            //EMPTY DIV CONTAINER STARS
            $(star_rating_incident).empty();

            //PUT NEW CONTENT FOR STAR RATING
            //COUNT OF STARS 1 TO 5
            for (var i = 1; i <= 5; i++) {
                //SHOW YELLOW STAR WHILE STAR RATING COMPLETE
                if (i <= e.panel_notification.star_rating) {
                    $(star_rating_incident).append(
                        '<div class="d-inline-block star-rating">' +
                        '<i class="fa fa-star" style="color:orange"></i>' +
                        '</div>'
                    );
                    //WHEN THE STAR RATING NUMBER OF DATABASE HAS COMPLETE,
                    //SHOW BLACK STARS FOR THE REST
                } else {
                    $(star_rating_incident).append(
                        '<div class="d-inline-block star-rating">' +
                        '<i class="fa fa-star"></i>' +
                        '</div>'
                    );
                }

                //APPLY MARGIN TO STAR ICON, EXCEPT THE LAST ONE 
                $('.star-rating').css({ 'margin-right': '.25rem' });
                $('.star-rating:last-of-type').css({ 'margin-right': '0' });
            }
        } else if (e.panel_notification.panel == 'User-Chat') {
            close_request_incident = '.btn-message-' + e.panel_notification.incident_id;

            //EMPTY DIV INPUT-BTN SEND MESSAGE CHAT USER
            $(close_request_incident).empty();

            //PUT MESSAGE CLOSE REQUEST
            $(close_request_incident).append(
                '<p>SOLICITUD CERRADA</p>'
            );
        }
    });

    //CHAT MESSAGES USER-TECH VIEW
    //DEFINE TYPE OF USER MESSAGING TO SHOW STYLES IN CHAT
    var type_user_msg = 'RECEIVER';
    //NOTIFICATION NUMBER WHEN NO EXISTS NEW MESSAGES FOR YET...
    var notification_count = 1;

    //SUBMIT MESSAGE CHAT
    $('#form-message').on('submit', function (e) {
        e.preventDefault();

        //TYPE OF USER SENDING MESSAGES
        type_user_msg = 'SENDER';

        //DATA FORM-MESSAGE
        var formData = $(this).serializeArray();

        //CREATE DETAIL INCIDENT MESSAGES
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

                //UPDATE DETAIL INCIDENT NOTIFICATIONS
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
            chat_message_incident = '.chat-message-' + e.message.incident_id + ':last';
            //SHOW NEW MESSAGES IN CHAT FOR USER WHO WRITE
            if (type_user_msg == 'SENDER') {
                $(chat_message_incident).append(
                    '<div class="d-block d-flex flex-row-reverse my-2">' +
                    '<div class="d-block order-2 bg-info w-50 rounded-lg px-3 py-3 pull-right">' +
                    '<p class="m-0">' + e.message.message_reply + '</p>' +
                    '<small class="d-flex flex-row-reverse text-muted font-weight-bold mt-2">' + e.message.created_at + '</small>' +
                    '</div>' +
                    '</div>');
                //SHOW NEW MESSAGES IN CHAT FOR USER WHO READ
            } else {
                $(chat_message_incident).append(
                    '<div class="d-block">' +
                    '<div class="d-block order-1 bg-dark text-white w-50 rounded-lg px-3 py-3 my-2">' +
                    '<p class="m-0">' + e.message.message_reply + '</p>' +
                    '<small class="d-flex flex-row-reverse text-muted font-weight-bold mt-2">' + e.message.created_at + '</small>' +
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

    //*************************************************** SERVICE RATING
    $('#form-service-rating').on('submit', function (e) {
        e.preventDefault();

        var formRating = $(this).serializeArray();

        //TAKE ID INCIDENT FROM URL
        incident_id = parseInt(window.location.pathname.split('/').pop());

        // console.log(incident_id);

        var radio_star_selected = [];

        //SAVE ID RADIO SELECTED INTO ARRAY
        $('.checkbox-star:checked').each(function () {
            radio_star_selected.push($(this).attr('value'));
        });

        //IF ARRAY RADIO_STAR IS EMPTY SHOW ALERT
        if (Array.isArray(radio_star_selected) && !radio_star_selected.length) {
            alert('Por favor, selecciona una valoración entre 1 y 5 estrellas');
            //ELSE... SEND DATA FOR PUT AJAX METHOD
        } else {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content") },
                type: "PUT",
                url: "/incident/" + incident_id,
                data: formRating,
                dataType: "json",
                success: function (data) {
                    //console.log(data);

                    //ADD NOTIFICATION
                    $('.main-container').prepend(
                        '<div class="container position-relative d-flex flex-row-reverse">' +
                        '<div style="z-index: 1;" class="notification-alert alert alert-success position-absolute m-0 mt-1 col-md-4">' +
                        '<p class="m-0 text-center font-weight-bold">' +
                        data.success +
                        '</p>' +
                        '</div>' +
                        '</div>'
                    );

                    //NOTIFICATION DISSAPEAR
                    $('.notification-alert').delay(3000).fadeOut(500);

                    //RETURN TO HOME
                    window.location.href = '/';

                }, error: function () {
                    console.log('Error!');
                }
            });
        }

    });
});
