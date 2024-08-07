<?php

function fireball9()
{
    $test = 123;
    return empty($test);
}

?>
-----
<?php

function fireball9()
{
    $test = 123;
    return $test === 0;
}

?>
