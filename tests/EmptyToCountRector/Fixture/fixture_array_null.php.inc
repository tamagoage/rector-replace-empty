<?php

function fireball4(array|null $test)
{
    return empty($test);
}

?>
-----
<?php

function fireball4(array|null $test)
{
    return $test === null || \count($test) === 0;
}

?>
