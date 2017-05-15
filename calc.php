<?php
$params = array();
$num_params = array();

foreach ($_POST as $par => $item) {
    if (!$item) {
        $params[$par] = 0;
        $num_params[] = 0;
    } else {
        $params[$par] = $_POST[$par];
        $num_params[] = $_POST[$par];
    }
}

$coefs = array();
$lims = array();

function get_sum($n)
{
    $sum = 0;
    for ($i = 0; $i < $n; $i++) {
        $sum += $i;
    }
    return $sum;
}

function out_matrix($matrix, $params, $har = 0)
{
    echo "
<br><table id='matrix'>";
    for ($i = 0; $i < $params["var_count"]; $i++) {
        if ($i == 0)
            if ($har == 0)
                echo "<tr><td id='c_td' rowspan='" . $params["var_count"] . "'>C = </td><td class='left_border top_border'></td>";
            else
                echo "<tr></td><td class='left_border top_border'></td>";
        else if ($i == $params["var_count"] - 1)
            echo "<tr><td class='left_border bottom_border'></td>";
        else
            echo "<tr><td class='left_border'></td>";
        for ($j = 0; $j < $params["var_count"]; $j++) {
            if ($har == 1 && $i == $j)
                echo "<td class='main_td'>" . $matrix[$i][$j] . " - λ</td>";
            else
                echo "<td class='main_td'>" . $matrix[$i][$j] . "</td>";
        }
        if ($i == 0)
            if ($har == 1)
                echo "<td class='right_border top_border'></td><td id='c_td' rowspan='" . $params["var_count"] . "'> = 0</td></tr>";
            else
                echo "<td class='right_border top_border'></td></tr>";
        else if ($i == $params["var_count"] - 1)
            echo "<td class='right_border bottom_border'></td></tr>";
        else
            echo "<td class='right_border'></td></tr>";
    }
    echo "</table>";
}

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

function get_equation($params)
{
    $coef_count = $params["var_count"] * 2 + get_sum($params["var_count"]) + 1;

    $str = "";
    $i = 0;
    foreach ($params as $par => $value) {
        if ($i++ > $coef_count - 2)
            break;
        if ($value != 0) {
            $str .= $value . $par;

            if ($i <= $coef_count - 2)
                $str .= " + ";
        }
        $coefs[] = $value;
    }
    if ($params["const"] != 0) {
        $str .= " + " . $params["const"];
        $coefs[] = $params["const"];
    }
    return $str;
}
function out_header($params, $num_params) {
    // -------- рівняння
    echo "<label>F<sub>" . $params["func"] . "</sub> = ";
    $coef_count = $params["var_count"] * 2 + get_sum($params["var_count"]) + 1;
    echo get_equation($params);
//print_r($coefs);
    echo "</label><br>";

// -------- обмеження
    echo "<label>при</label><br><label>";
    $lims_count = $params["var_count"] + 2;

    for ($i = 0; $i < $params["lim_count"]; $i++) {
        $lims[] = array();
        $j_start = $coef_count + ($i * $lims_count);
        $j_end = $coef_count + ($i * $lims_count) + $lims_count;
        $vars = 0;
        for ($j = $j_start; $j < $j_end; $j++) {
            $lims[$i][] = $num_params[$j];
            if ($vars < $params["var_count"]) {
                if ($num_params[$j] == 0) {
                    $vars++;
                    continue;
                }
                echo $num_params[$j] . "x<sub>" . (++$vars) . "</sub>";
            } else {
                echo " " . $num_params[$j];
            }

            if ($vars < $params["var_count"] && $num_params[$j + 1] != 0)
                echo " + ";

        }
        echo "<br>";
    }
    echo "</label>";
}

/*print_r($params);
print_r($num_params);*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Квадратичне програмування</title>
    <script src="jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div id="header"><input type="button" class="butt" value="Назад" id="calc_back">
        <label>Задача з квадратичного програмування</label></div>
</header>
<div id="loader"><br><br><br>
    <img src="image/loading.gif" width="100">
</div>
<?php

//print_r($lims);
echo "<div id='calc_1'>";
out_header($params, $num_params);
$q_equation = array();
for ($i = $params["var_count"]; $i < count($num_params) - 4 - $lims_count * $params["lim_count"]; $i++) {
    $q_equation[] = $num_params[$i];
}
//print_r($q_equation);
$matrix = array();
for ($i = 0; $i < $params["var_count"]; $i++) {
    $matrix[] = array();
    $matrix[$i][$i] = $q_equation[$i];
    for ($j = $i + 1; $j < $params["var_count"]; $j++) {
        $matrix[$i][$j] = $q_equation[$j + $params["var_count"] - 1 + $i] / 2;
    }
}
for ($i = 0; $i < $params["var_count"]; $i++) {
    for ($j = $i + 1; $j < $params["var_count"]; $j++) {
        $matrix[$j][$i] = $matrix[$i][$j];
    }
}
out_matrix($matrix, $params);
out_matrix($matrix, $params, 1);


if ($params["var_count"] == 2) {
    echo "<br><label>(" . $matrix[0][0] . " - λ)(" . $matrix[1][1] . " - λ) - (" . $matrix[0][1] . ")(" . $matrix[1][0] . ") = 0</label>";
    echo "<br><label>" . ($matrix[0][0] * $matrix[1][1]) . " + " . ($matrix[0][0] * -1) . "λ + " .
        ($matrix[1][1] * -1) . "λ + λ<sup>2</sup> - " . $matrix[0][1] * $matrix[1][0] . " = 0</label>";
    echo "<br><label>λ<sup>2</sup> + " . (($matrix[0][0] * -1) + ($matrix[1][1] * -1)) . "λ + " .
        ($matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0]) . " = 0</label>";
    $roots = square_equation(1, (($matrix[0][0] * -1) + ($matrix[1][1] * -1)), ($matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0]));

    echo "<br>";
    if ($roots[0] == "<") {
        echo "<br><label>D < 0 => Квадратична форма є неозначеною</label>";
    } else if ($roots[0] == "=") {
        if ($roots[1] < 0) {
            echo "<br><label>Корінь рівняння [" . $roots[1] . "] менше нуля => Квадратична форма є від'ємно означеною</label>";
        } else if ($roots[1] > 0) {
            echo "<br><label>Корінь рівняння [" . $roots[1] . "] більше нуля => Квадратична форма є додатньо означеною</label>";
        } else {
            echo "<br><label>Корінь рівняння дорівнює нулю => Квадратична форма є неозначеною</label>";
        }
    } else {
        if ($roots[1] < 0 && $roots[2] < 0) {
            echo "<br><label>Корені рівняння [" . $roots[1] . "], [" . $roots[2] . "] менше нуля => Квадратична форма є від'ємно означеною</label>";
        } else if ($roots[1] > 0 && $roots[2] > 0) {
            echo "<br><label>Корені рівняння [" . $roots[1] . "], [" . $roots[2] . "] більше нуля => Квадратична форма є додатньо означеною</label>";
        } else if (($roots[1] > 0 && $roots[2] == 0) || ($roots[1] == 0 && $roots[2] > 0)) {
            echo "<br><label>Один з коренів рівняння [" . $roots[1] . "], [" . $roots[2] . "] дорівнює нулю => Квадратична форма є додатньо напівозначеною</label>";
        } else if (($roots[1] < 0 && $roots[2] == 0) || ($roots[1] == 0 && $roots[2] < 0)) {
            echo "<br><label>Один з коренів рівняння [" . $roots[1] . "], [" . $roots[2] . "] дорівнює нулю => Квадратична форма є від'ємно напівозначеною</label>";
        } else {
            echo "<br><label>Корені рівняння [" . $roots[1] . "], [" . $roots[2] . "] мають різні знаки => Квадратична форма є неозначеною</label>";
        }
    }
} else if ($params["var_count"] == 3) {
    echo "<br><label>(" . $matrix[0][0] . " - λ)((" . $matrix[1][1] . " - λ)(" . $matrix[2][2] . " - λ) - (" . $matrix[2][1] . " * " . $matrix[1][2] . "))";
    echo " - " . $matrix[1][0] . " * ((" . $matrix[2][1] . " * " . $matrix[0][2] . ") - (" . $matrix[0][1] . " * (" . $matrix[2][2] . " - λ)))";
    echo " + " . $matrix[2][0] . " * ((" . $matrix[0][1] . " * " . $matrix[1][2] . ") - (" . $matrix[0][2] . " * (" . $matrix[1][1] . " - λ))) = 0</label>";

    echo "<br><label>(" . $matrix[0][0] . " - λ)(" . $matrix[1][1] * $matrix[2][2] . " + " . $matrix[1][1] * -1 . "λ + " .
        $matrix[2][2] * -1 . "λ + λ<sup>2</sup> - " . $matrix[2][1] * $matrix[1][2] . ")";
    echo " - " . $matrix[1][0] . " * (" . $matrix[2][1] * $matrix[0][2] . " - " . $matrix[0][1] * $matrix[2][2] .
        ($matrix[0][1] < 0 ? " - " : " + ") . ($matrix[0][1] < 0 ? ($matrix[0][1] * -1) : $matrix[0][1]) . "λ)";
    echo " + " . $matrix[2][0] . " * (" . $matrix[0][1] * $matrix[1][2] . " - " . $matrix[0][2] * $matrix[1][1] .
        ($matrix[0][2] < 0 ? " - " : " + ") . ($matrix[0][2] < 0 ? ($matrix[0][2] * -1) : $matrix[0][2]) . "λ)) = 0</label>";


} else {

}
echo "<br><br><input class=\"butt\" type='button' value='-->' id='calc_1_next'></div>";
echo "<div id='calc_2'>";
out_header($params, $num_params);
echo "<label>Запишемо функцію Лагранжа</label><br>";
echo "<label>L(X, Λ) = ";
echo get_equation($params);
//print_r($coefs);
$lim_last = $params["var_count"] + 1;
for ($i = 0; $i < $params["lim_count"]; $i++) {
    if ($params["func"] === "max") {
        if ($lims[$i][$lim_last - 1] === "≤" || $lims[$i][$lim_last - 1] === "=")
            echo " + ";
        else
            echo " - ";
    } else {
        if ($lims[$i][$lim_last - 1] === "≤" || $lims[$i][$lim_last - 1] === "=")
            echo " - ";
        else
            echo " + ";
    }
    if (count($lims) === 1) {
        echo "λ(" . $lims[$i][$lim_last];
    } else {
        echo "λ<sub>" . ($i + 1) . "</sub>(" . $lims[$i][$lim_last];
    }
    for ($j = 0; $j < $params["var_count"]; $j++) {
        if ($lims[$i][$j] > 0) {
            echo " - ";
        } else {
            echo " + ";
        }
        echo abs($lims[$i][$j]) . "x<sub>" . ($j + 1), "</sub>";
    }
}
echo ")</label><br>";
echo "<table>";
if ($params["var_count"] == 2) {
    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label> = " . $num_params[0] . " + " . ($num_params[2] * 2) . "x<sub>1</sub> + " .
        ($num_params[4]);
    for ($i = 0; $i < count($lims); $i++) {
        if (count($lims) == 1)
            echo " + " . $lims[$i][0] * -1 . "λ";
        else {
            echo " + " . $lims[$i][0] * -1 . "λ<sub>" . ($i + 1) . "</sub>";
        }
    }
    echo " ≤ 0, причому </label></td>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label>x<sub>1</sub><sup>*</sup> = 0</label></td>
        </tr>
        <tr>
            <td><label>∂x<sub>1</sub></label></td>
            <td><label>∂x<sub>1</sub></label></td>
        </tr>";

    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label> = " . $num_params[1] . " + " . ($num_params[3] * 2) . "x<sub>2</sub> + " .
        ($num_params[4]);
    for ($i = 0; $i <count($lims); $i++) {
        if (count($lims) == 1)
            echo " + " . $lims[$i][1] * -1 . "λ";
        else {
            echo " + " . $lims[$i][1] * -1 . "λ<sub>" . ($i + 1) . "</sub>";
        }
    }
    echo " ≤ 0, причому </label></td>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label>x<sub>2</sub><sup>*</sup> = 0</label></td>
        </tr>
        <tr>
            <td><label>∂x<sub>2</sub></label></td>
            <td><label>∂x<sub>2</sub></label></td>
        </tr>";

    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label> = ";

    for ($i = 0; $i < $params["lim_count"]; $i++) {
        echo $lims[$i][$lim_last];
        for ($j = 0; $j < $params["var_count"]; $j++) {
            if ($lims[$i][$j] > 0) {
                echo " - ";
            } else {
                echo " + ";
            }
            echo abs($lims[$i][$j]) . "x<sub>" . ($j + 1), "</sub>";
        }
    }
    echo " ≥ 0, причому </label></td>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label>λ<sup>*</sup> = 0</label></td>
        </tr>
        <tr>
            <td><label>∂λ</label></td>
            <td><label>∂λ</label></td>
        </tr>";
}
echo "</table>";
echo "<br><label>де (x<sub>1</sub><sup>*</sup>, x<sub>2</sub><sup>*</sup>, λ<sup>*</sup>) - координати сідлової точки</label>";
echo "<br><br><input class=\"butt\" type='button' value='<--' id='calc_2_back'></div>";
echo "</div>";

?>

<footer>
    <span class="left">Бас Євгеній &copy; 2017</span><span class="right">
    <a href="https://vk.com/id189464921"><img src="image/vk.png" alt="Профіль ВК" title="Профіль ВК"></a></span>
</footer>
<script src="script.js"></script>
</body>
</html>
