<?php

function fireball2(array|string $test)
{
    return empty($test);
}

?>
-----
<?php

function fireball2(array|string $test)
{
    return in_array($test, ['', '0'], true) || \count($test) === 0;
}

?>
