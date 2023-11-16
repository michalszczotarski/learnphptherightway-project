<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <?php if (isset($_GET["message"]) && $_GET["message"] === "1") : ?>
        <div>
            Files has been successfully uploaded
        </div>
    <?php endif; ?>

    Home Page

    <br>
    <br>

    <a href="/upload">Add files</a><br>
    <a href="/transactions">List of transactions</a>
</body>

</html>