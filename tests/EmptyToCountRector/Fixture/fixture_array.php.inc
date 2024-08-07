<?php

function fireball1()
{
    $test = [1, 2, 3];
    return empty($test);
}

?>
-----
<?php

function fireball1()
{
    $test = [1, 2, 3];
    return \count($test) === 0;
}

?>
