<?php if (isset($_GET["message"]) && $_GET["message"] === "-1") : ?>
    <div>
        There was a problem with uploading files
    </div>
<?php elseif (isset($_GET["message"]) && $_GET["message"] === "-2") : ?>
    <div>
        No files were uploaded
    </div>
<?php elseif (isset($_GET["message"]) && $_GET["message"] === "-3") : ?>
    <div>
        Files are not a CSV files
    </div>
<?php endif; ?>

<form action="/upload" method="post" enctype="multipart/form-data">
    <label for="file">Add files</label><br>
    <input id="file" type="file" name="csv_files[]" accept=".csv" multiple>
    <br>
    <br>
    <input type="submit" value="send">
</form>

<br>
<br>

<a href="/">Back to home</a>