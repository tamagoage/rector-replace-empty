<?php

function fireball5(array|int $test)
{
    return empty($test);
}

?>
-----
<?php

function fireball5(array|int $test)
{
    return $test === 0 || \count($test) === 0;
}

?>
