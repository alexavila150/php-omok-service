<?php
if (!empty($_POST)) {
    $comment = $_POST['comment'];
}
?>
<html>
<body>
<p>Enter your comment below.</p>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <textarea cols=40 rows=4 name="comment"> </textarea>
    <p/>
    <input type=submit value="Send">
</form>

<?php
if (!empty($comment)) {
    echo "<b>Your wrote:</b><br>";
    echo $comment;
}
?>

</body>
</html>

