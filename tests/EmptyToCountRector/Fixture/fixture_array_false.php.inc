<?php

function fireball3(array|false $test)
{
    return empty($test);
}

?>
-----
<?php

function fireball3(array|false $test)
{
    return $test === false || \count($test) === 0;
}

?>
