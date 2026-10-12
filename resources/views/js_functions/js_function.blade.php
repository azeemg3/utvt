<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    //var jq = $.noConflict();
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    /**@Add new record and update new record in the system*/
    $(document).ready(function() {
        $("#form").submit(function(e) {
            e.preventDefault();
            var action = $(this).attr("data-action");
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: action,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert_success('Operation Successfully..!!');
                    $("#form").trigger("reset");
                    $("#add-new").modal('hide');
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function(ajaxcontent) {
                    vali = ajaxcontent.responseJSON.errors;
                    var errors = '';
                    $.each(vali, function(index, value) {
                        $("#form input[name~='" + index + "']").css('border',
                            '1px solid red');
                        errors += '&#187;' + value + '<br><br>';
                    });
                    swal.fire({
                        icon: 'error',
                        title: errors,
                    });

                }
            });
        });
    });
    /*edit funtion*/
    function edit_rec(thisVal) {
        var mdl = $(thisVal).attr("data-modal");
        $("#" + mdl).modal();
        let action = $(thisVal).attr("data-action");
        $.ajax({
            url: action,
            success: function(data) {
                for (i = 0; i < Object.keys(data).length; i++) {
                    $("#form input[name~='" + Object.keys(data)[i] + "']").val(Object.values(data)[i]);
                    $("#form select[name~='" + Object.keys(data)[i] + "']").val(Object.values(data)[i]);
                }
                $('.select2').select2();
            }
        });
    }

    function alert_success(val) {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: val,
            showConfirmButton: false,
            timer: 2000
        })
    }
    $(document).on("change", "#country", function() {
        CID = $(this).val();
        $.ajax({
            url: "{{ url('settings/city') }}/" + CID + "",
            success: function(data) {
                var htmlData = '';
                for (i in data) {
                    htmlData += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                }
                $("#cityList").html(htmlData);
            }
        });
    });
    /*delete records*/
    function del_rec(id, route) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $("#" + id).hide();
                    },
                });
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
            }
        });
    }
    $(document).on('click', '.del_rec', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = $(this).data('action');
        Swal.fire({
            title: "Are you sure?",
            text: "You are going to deleting this record!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.value==true) {
                $.ajax({
                    url: url+"/"+id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Record has been deleted.",
                            icon: "success"
                        });
                        $('.data-table').DataTable().ajax.reload();
                    },
                });
            }else{

            }
        });
    });
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
    {{--  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>  --}}
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyBtb6OSkTsypZnJ2o1hVTJvGQw9JV__sc0",
            authDomain: "tour-vision-5a892.firebaseapp.com",
            projectId: "tour-vision-5a892",
            storageBucket: "tour-vision-5a892.appspot.com",
            messagingSenderId: "904313745421",
            appId: "1:904313745421:web:ccd18829c454a88a3e6544",
            measurementId: "G-6XDB12B751"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        const reminderNotifyIcon = '{{ asset("dist/img/AdminLTELogo.png") }}';
        var shownReminderIds = {};
        var shownLeadNotifyIds = {};
        var genericToastShown = false;
        var refreshInFlight = false;
        var leadSoundReady = false;
        var markReadInFlight = {};

        try {
            shownReminderIds = JSON.parse(sessionStorage.getItem('shown_lead_reminders') || '{}');
        } catch (e) {
            shownReminderIds = {};
        }
        try {
            shownLeadNotifyIds = JSON.parse(sessionStorage.getItem('shown_lead_notifies') || '{}');
        } catch (e) {
            shownLeadNotifyIds = {};
        }

        function markLeadNotifyShown(id) {
            if (!id) return;
            shownLeadNotifyIds[String(id)] = Date.now();
            try {
                sessionStorage.setItem('shown_lead_notifies', JSON.stringify(shownLeadNotifyIds));
            } catch (e) {}
        }

        function wasLeadNotifyShown(id) {
            return !!id && !!shownLeadNotifyIds[String(id)];
        }

        function markNotificationsRead(ids) {
            if (!ids) return;
            if (!Array.isArray(ids)) ids = [ids];
            ids = ids.map(String).filter(function(id) {
                if (!id || markReadInFlight[id]) return false;
                markReadInFlight[id] = true;
                markLeadNotifyShown(id);
                return true;
            });
            if (!ids.length) return;

            $.ajax({
                url: "{{ route('notification.mark_read') }}",
                type: 'POST',
                data: {
                    ids: ids,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res && typeof res.unread !== 'undefined') {
                        $(".count_notify").text(Math.max(Number(res.unread), 0));
                    }
                },
                complete: function() {
                    ids.forEach(function(id) { delete markReadInFlight[String(id)]; });
                }
            });
        }

        var leadAudioCtx = null;

        function unlockLeadAudio() {
            try {
                var Ctx = window.AudioContext || window.webkitAudioContext;
                if (!Ctx) return;
                if (!leadAudioCtx) {
                    leadAudioCtx = new Ctx();
                }
                if (leadAudioCtx.state === 'suspended') {
                    leadAudioCtx.resume();
                }
                leadSoundReady = true;
            } catch (e) {}
        }

        function playLeadAlertSound() {
            try {
                unlockLeadAudio();
                if (!leadAudioCtx) return;
                var ctx = leadAudioCtx;
                var now = ctx.currentTime;
                [0, 0.18, 0.36].forEach(function(offset, i) {
                    var osc = ctx.createOscillator();
                    var gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = i === 2 ? 980 : 740;
                    gain.gain.setValueAtTime(0.0001, now + offset);
                    gain.gain.exponentialRampToValueAtTime(0.22, now + offset + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.0001, now + offset + 0.15);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + offset);
                    osc.stop(now + offset + 0.16);
                });
            } catch (e) {}
        }

        function showLeadAlertPopup(item) {
            var title = (item && item.title) || 'New Lead';
            var body = (item && item.body) || 'A new lead is available.';
            var clickAction = (item && item.url) || '{{ url("lms/all_leads") }}';
            var notifyId = item && item.id ? String(item.id) : '';
            var nType = (item && item.type) || '';

            if (notifyId && wasLeadNotifyShown(notifyId)) {
                return false;
            }
            if (notifyId) {
                markLeadNotifyShown(notifyId);
                markNotificationsRead([notifyId]);
            }

            playLeadAlertSound();

            if (typeof Swal !== 'undefined' && (nType === 'lead_transfer' || nType === 'lead_assigned' || nType === 'lead_available')) {
                Swal.fire({
                    icon: 'info',
                    title: title,
                    html: body + '<br><br><a href="' + clickAction + '">Open Lead</a>',
                    confirmButtonText: 'Open',
                    showCancelButton: true,
                    cancelButtonText: 'Dismiss'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = clickAction;
                    }
                });
            }

            if (window.Notification && Notification.permission === 'granted') {
                try {
                    var n = new Notification(title, {
                        body: body,
                        icon: reminderNotifyIcon,
                        tag: 'lead-alert-' + (notifyId || Date.now()),
                        requireInteraction: false
                    });
                    n.onclick = function(event) {
                        event.preventDefault();
                        window.focus();
                        if (clickAction) {
                            window.location.href = clickAction;
                        }
                    };
                } catch (e) {}
            }

            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    closeButton: true,
                    newestOnTop: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    preventDuplicates: true,
                    timeOut: 10000
                };
                toastr.info('<a href="' + clickAction + '"><strong>' + title + '</strong><br>' + body + '</a>');
            }
            return true;
        }

        function markReminderShown(reminderId) {
            if (!reminderId) {
                return;
            }
            shownReminderIds[String(reminderId)] = Date.now();
            try {
                sessionStorage.setItem('shown_lead_reminders', JSON.stringify(shownReminderIds));
            } catch (e) {}
        }

        function wasReminderShown(reminderId) {
            return !!reminderId && !!shownReminderIds[String(reminderId)];
        }

        function showLeadReminderPopup(item) {
            var reminderId = item && item.reminder_id ? String(item.reminder_id) : '';
            if (wasReminderShown(reminderId)) {
                return;
            }
            if (reminderId) {
                markReminderShown(reminderId);
            }

            var title = (item && item.title) || 'Lead Reminder Due';
            var body = (item && item.body) || 'You have a follow-up reminder.';
            var clickAction = (item && item.url) || '{{ url("lms/lead-reminder") }}';

            // One channel only: browser notification when allowed, otherwise toast.
            if (window.Notification && Notification.permission === 'granted') {
                var n = new Notification(title, {
                    body: body,
                    icon: reminderNotifyIcon,
                    tag: 'lead-reminder-' + (reminderId || Date.now())
                });
                n.onclick = function(event) {
                    event.preventDefault();
                    window.focus();
                    if (clickAction) {
                        window.location.href = clickAction;
                    }
                };
            } else if (typeof toastr !== 'undefined') {
                toastr.warning('<a href="' + clickAction + '">' + title + ': ' + body + '</a>');
            }
        }

        function scheduleLeadReminderNotification(dueIso, meta) {
            if (!dueIso) {
                return;
            }
            try {
                var items = JSON.parse(localStorage.getItem('lead_reminder_timers') || '[]');
                var reminderId = meta && meta.reminder_id ? String(meta.reminder_id) : '';
                items = items.filter(function(i) {
                    if (reminderId && String(i.reminder_id) === reminderId) {
                        return false;
                    }
                    return new Date(i.due).getTime() > Date.now() - 60000;
                });
                items.push(Object.assign({ due: dueIso }, meta || {}));
                localStorage.setItem('lead_reminder_timers', JSON.stringify(items));
            } catch (e) {}
            var ms = new Date(dueIso).getTime() - Date.now();
            if (isNaN(ms)) {
                return;
            }
            if (ms < 0) {
                ms = 0;
            }
            setTimeout(function() {
                refreshNotifications();
            }, Math.min(ms + 800, 2147483647));
        }

        function armStoredReminderTimers() {
            try {
                var items = JSON.parse(localStorage.getItem('lead_reminder_timers') || '[]');
                var now = Date.now();
                items.forEach(function(item) {
                    if (item.reminder_id && wasReminderShown(item.reminder_id)) {
                        return;
                    }
                    var ms = new Date(item.due).getTime() - now;
                    if (isNaN(ms)) {
                        return;
                    }
                    if (ms <= 0) {
                        refreshNotifications();
                    } else {
                        setTimeout(function() {
                            refreshNotifications();
                        }, Math.min(ms + 800, 2147483647));
                    }
                });
            } catch (e) {}
        }

        function startFCM() {
            if (!window.Notification) {
                return;
            }
            var swUrl = "{{ url('firebase-messaging-sw.js') }}";
            var permissionPromise = Notification.permission === 'granted'
                ? Promise.resolve('granted')
                : Notification.requestPermission();

            permissionPromise.then(function(permission) {
                if (permission !== 'granted') {
                    return;
                }
                var ready = navigator.serviceWorker
                    ? navigator.serviceWorker.register(swUrl).then(function(reg) {
                        try {
                            messaging.useServiceWorker(reg);
                        } catch (e) {}
                        return messaging.getToken();
                    })
                    : messaging.requestPermission().then(function() {
                        return messaging.getToken();
                    });

                return ready.then(function(token) {
                    if (!token) {
                        return;
                    }
                    $.ajax({
                        url: '{{ url('store_token') }}',
                        type: 'POST',
                        data: { token: token },
                        dataType: 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                });
            }).catch(function() {});
        }

        messaging.onMessage(function(payload) {
            var type = (payload.data && payload.data.type) || '';
            var playSound = (payload.data && payload.data.play_sound) === '1'
                || type === 'lead_assigned'
                || type === 'lead_available'
                || type === 'lead_transfer';

            if (playSound || type.indexOf('lead_') === 0) {
                showLeadAlertPopup({
                    id: (payload.data && (payload.data.lead_id || payload.messageId)) || ('fcm-' + Date.now()),
                    title: (payload.notification && payload.notification.title) || (payload.data && payload.data.title) || 'New Lead',
                    body: (payload.notification && payload.notification.body) || (payload.data && payload.data.body) || '',
                    url: (payload.data && payload.data.url) || (payload.data && payload.data.click_action) || '{{ url("lms/all_leads") }}'
                });
            } else {
                showLeadReminderPopup({
                    title: (payload.notification && payload.notification.title) || (payload.data && payload.data.title) || 'Notification',
                    body: (payload.notification && payload.notification.body) || (payload.data && payload.data.body) || '',
                    url: (payload.data && payload.data.url) || (payload.data && payload.data.click_action) || (payload.notification && payload.notification.click_action) || '',
                    reminder_id: payload.data && payload.data.reminder_id
                });
            }
            $(".count_notify").text(Number($(".count_notify").text() || 0) + 1);
        });

        function refreshNotifications() {
            if (refreshInFlight) {
                return;
            }
            refreshInFlight = true;
            $.ajax({
                url: "{{ url('fetch-notification') }}",
                dataType: "JSON",
                cache: false,
                success: function(data) {
                    $(".count_notify").text(Number(data.count_notify || 0) + Number(data.reminder_count || 0));
                    var lead_notify = '';
                    if (data.reminder_count > 0) {
                        lead_notify += `<div class="dropdown-divider"></div>
                        <a href="{{ url('lms/lead-reminder') }}" class="dropdown-item">
                            <i class="fas fa-bullhorn mr-2"></i> Please click here for reminders
                            <span class="float-right text-muted text-sm"></span>
                        </a>`;
                    }

                    var list = data.notify_data || [];
                    for (var i in list) {
                        if (!Object.prototype.hasOwnProperty.call(list, i)) continue;
                        var row = list[i];
                        var payload = row.data || {};
                        var msg = payload.data || '';
                        var link = payload.url || '{{ url("lms/all_leads") }}';
                        lead_notify += `<div class="dropdown-divider"></div>
                        <a href="` + link + `" class="dropdown-item js-notification-item" data-notify-id="` + row.id + `">
                            <i class="fas fa-bullhorn mr-2"></i> ` + msg + `
                            <span class="float-right text-muted text-sm">Just now</span>
                        </a>`;
                    }
                    $("#lead-notification").html(lead_notify);

                    // Dedicated lead alerts — show once, then mark read so they never repeat
                    if (data.lead_alerts && data.lead_alerts.length) {
                        var alreadySeen = [];
                        data.lead_alerts.forEach(function(item) {
                            if (item.id && wasLeadNotifyShown(item.id)) {
                                alreadySeen.push(item.id);
                                return;
                            }
                            showLeadAlertPopup(item);
                        });
                        if (alreadySeen.length) {
                            markNotificationsRead(alreadySeen);
                        }
                    }

                    var showedDue = false;
                    if (data.due_notifications && data.due_notifications.length) {
                        data.due_notifications.forEach(function(item) {
                            showLeadReminderPopup(item);
                            showedDue = true;
                        });
                    }

                    if (!showedDue && !genericToastShown) {
                        if (data.reminder_count > 0) {
                            toastr.warning('<a href="{{ route('lead.lead_reminder', 1) }}">You have ' + data.reminder_count + ' Unread Reminder!</a>');
                            genericToastShown = true;
                        } else if ((data.lead_alerts || []).length === 0 && data.count_notify > 0) {
                            toastr.success('<a href="{{ route('all-notifications') }}">You have ' + data.count_notify + ' Notifications!</a>');
                            genericToastShown = true;
                        }
                    }
                },
                complete: function() {
                    refreshInFlight = false;
                }
            });
        }

        $(document).ready(function() {
            startFCM();
            armStoredReminderTimers();
            $(document).one('click keydown touchstart', unlockLeadAudio);

            // Hover or click on bell notification => mark as read (won't show again)
            $(document).on('mouseenter click', '.js-notification-item', function() {
                var id = $(this).data('notify-id');
                if (id) {
                    markNotificationsRead([id]);
                    $(this).addClass('text-muted');
                }
            });

            // Opening the bell dropdown also marks visible items as read after short hover time
            $(document).on('shown.bs.dropdown', '.nav-item.dropdown', function() {
                var ids = [];
                $(this).find('.js-notification-item').each(function() {
                    var id = $(this).data('notify-id');
                    if (id) ids.push(id);
                });
                if (ids.length) {
                    // slight delay so accidental open without looking doesn't clear instantly
                    var self = this;
                    clearTimeout(self._markReadTimer);
                    self._markReadTimer = setTimeout(function() {
                        markNotificationsRead(ids);
                    }, 800);
                }
            });

            refreshNotifications();
            setInterval(refreshNotifications, 5000);
        });
    </script>
    <!--<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>-->
