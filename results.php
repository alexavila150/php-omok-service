<html>
<body>
<h1>Iteration Results</h1>
<b>Here are 10 iterations of the formula:<br/>
    y = x <sup>2</sup>
</b>
<p/>
<!-- PHP Calculations start here!  -->
<?php
$num = $_POST['data'];
for($i = 0; $i < 10; $i++){
    $num = $num ** 2;
    echo $i + 1 . ". " . $num . "<br>";
}

?>
</body>
</html>
