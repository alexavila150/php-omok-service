<?php $note = array_key_exists('note', $_POST) ? $_POST['note'] : null ?>
<html><head><title>My Guestbook</title></head>
<body>
<h1>Welcome to My Guestbook</h1>
<h2>Please leave me a short note below</h2>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <textarea cols=40 rows=5 name="note" wrap=virtual></textarea>
    <p/>
    <input type=submit value="Send it ">
</form>

<?php
$file = 'data/notes.txt';
addNote($note);
?>

<h2>The entries so far:</h2>
<?php showNotes(); ?>
<?php
function addNote($note) {
    global $file;
    if (!empty($note)) {
        $fp = fopen($file, 'a');
        fputs($fp, nl2br($note) . "<br/>\n");
        fclose($fp);
    }
}
function showNotes() {
    global $file;
    $fd = fopen($file, "r");
    echo fread($fd,filesize("data/notes.txt"));
    fclose($fd);
}
?>
</body>
</html>
