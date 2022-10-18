<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laravel Streaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<h1 class="text-bold">Laravel Streaming</h1>

<script>

    let totalDataLength = 0;

    const eventSource = new EventSource("/stream");

    eventSource.onmessage = function (event) {

        const result = JSON.parse(event.data);

        totalDataLength += result.data.length;
        console.log('totalDataLength', totalDataLength);

        result.data.forEach(function(user) {

            console.log('ID:', user.id);
            console.log('Name:', user.name);
            console.log('Email:', user.email);
            console.log('verified_at:', user.email_verified_at);

            console.log('--------------------------------------');
        });

        // Whenever the total data length is equal the total data count,
        // which means it was last chunk ( message ) therefore, we've to close the connection.
        if (result.dataCount === totalDataLength) {
            console.log('condition');

            eventSource.close();
        }

    };

</script>
</body>
</html>
