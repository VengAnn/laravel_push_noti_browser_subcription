<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Push Notifications</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="card">
        <div class="card-header">
            <button onclick="askForPermission()" class="btn btn-success">Enable Notifications</button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for='title'>{{ __('Title') }}</label>
                    <input type='text' class='form-control' id='title' name='title'>
                </div>
                <div class="col-md-3">
                    <label for='body'>{{ __('Body') }}</label>
                    <input type='text' class='form-control' id='body' name='body'>
                </div>
                <div class="col-md-3">
                    <label for='idOfProduct'>{{ __('ID of Product') }}</label>
                    <input type='text' class='form-control' id='idOfProduct' name='idOfProduct'>
                </div>
                <div class="col-md-3">
                    <input type="button" value="{{ 'Send Notification' }}" onclick="sendNotification()"
                        class="btn btn-info" />
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        navigator.serviceWorker.register("{{ URL::asset('service-worker.js') }}");

        
    function askForPermission() {
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                // get service worker
                navigator.serviceWorker.ready.then((sw) => {
                    // subscribe
                    sw.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: "BBAUMFmXYjwgnzwKv3vfl799l-Ze9XHn6hsTEpvvMegcKq8JhtLwSDoeu_unSrbSApMphZk6l4xWKlFSw_ITCnw"
                    }).then((subscription) => {
                        console.log(JSON.stringify(subscription));
                        alert('sub', JSON.stringify(subscription));
                        saveSub(JSON.stringify(subscription));
                    }).catch((error) => {
                        console.error("Subscription failed: ", error);
                        alert('sub failed', error);
                    });
                });
            }
        });
    }

    function saveSub(sub) {
            $.ajax({
                type: 'post',
                url: '{{ URL('save-push-notification-sub') }}',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'sub': sub
                },
                 success: function(data) {
                    console.log('Subscription saved:', data);
                },
                error: function(error) {
                    console.error('Failed to save subscription:', error);
                }
            });
    }

    function sendNotification() {
            $.ajax({
                type: 'post',
                url: '{{ URL('send-push-notification') }}',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'title': $("#title").val(),
                    'body': $("#body").val(),
                    'idOfProduct': $("#idOfProduct").val(),
                },
               success: function(data) {
                    alert('Notification sent successfully');
                    console.log(data);
                },
                error: function(error) {
                    console.error('Failed to send notification:', error);
                }
            });
    }


    </script>
</body>

</html>

{{--
Public Key:
BBAUMFmXYjwgnzwKv3vfl799l-Ze9XHn6hsTEpvvMegcKq8JhtLwSDoeu_unSrbSApMphZk6l4xWKlFSw_ITCnw

Private Key:
swyeZxFX6fTvzjy01FnCxc3G56Wa042EMWBVDOniqlc --}}