<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
</head>

<body>
    <button id="pay-button" style="display:none">Pay!</button>

    <form action="{{route('user.membership.payment_post')}}" id="submit_form" method="post">
        @csrf
        <input type="hidden" name="json" id="json_callback">
        <input type="hidden" name="transaction" value="{{json_encode($transaction)}}">
    </form>

    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{$snapToken}}', {
                onSuccess: function(result) {
                    console.log(result);
                    send_response_to_form(result);
                },
                onPending: function(result) {
                    console.log(result);
                    send_response_to_form(result);
                },
                onError: function(result) {
                    console.log(result);
                    send_response_to_form(result);
                },
                onClose: function() {
                    history.back();
                }
            })
        });

        payButton.click();

        function send_response_to_form(result){
            document.getElementById('json_callback').value = JSON.stringify(result);
            document.getElementById('submit_form').submit();
        }
    </script>
</body>

</html>
