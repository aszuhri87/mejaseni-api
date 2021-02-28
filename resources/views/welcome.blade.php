
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>Team A Score</h1>
        <div id="team1_score"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.1.2/socket.io.js"> </script>
    <script>
        var sock = io("http://127.0.0.1:8080");
        sock.on('action-channel-one:App\\Events\\ActionEvent', function (data) {
            //data.actionId and data.actionData hold the data that was broadcast
            //process the data, add needed functionality here
            var action = data.actionId;
            var actionData = data.actionData;
            if (action == "score_update" && actionData.team1_score) {
                $("#team1_score").html(actionData.team1_score);
            }
        });

    </script>
</body>

</html>
