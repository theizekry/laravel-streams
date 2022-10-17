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

<p class="p-">

</p>

<script>
    const eventSource = new EventSource("/stream");

    eventSource.onmessage = function (event) {
        const data = JSON.parse(event.data);

        console.log('Is Last Chunk', event.data.isLastChunk);

        if (event.data.isLastChunk === 'last') {
            console.log('inside last condition');

            eventSource.stop();
        }

        data.forEach(function(user) {

            console.log('ID:', user.id);
            console.log('Name:', user.name);
            console.log('Email:', user.email);
            console.log('verified_at:', user.email_verified_at);

            console.log('--------------------------------------');
        });
    };


</script>
</body>
</html>
