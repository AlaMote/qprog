<?php
function square_equation($a, $b, $c)
{

    $d = $b * $b - 4 * $a * $c;
    if ($d < 0) {
        return array("<");
    } else {
        if ($d == 0) {
            return array("=", floor(-$b / (2 * $a) * 100) / 100);
        } else {
            return array(">", floor(((-$b - sqrt($d)) / (2 * $a)) * 100) / 100, floor(((-$b + sqrt($d)) / (2 * $a)) * 100) / 100);
        }
    }
}
print_r(square_equation($_GET["a"], $_GET["b"], $_GET["c"]));