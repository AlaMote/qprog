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

function floor_res($val, $dec = 2)
{
    return floor($val * pow(10, $dec)) / pow(10, $dec);
}

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
            if ($i == 1)
                $str .= $value . $par;
            else
                $str .= get_koef($value) . $par;

            /*if ($i <= $coef_count - 2)
                $str .= " + ";*/
        }
        $coefs[] = $value;
    }
    if ($params["const"] != 0) {
        $str .= get_koef($params["const"]);
        $coefs[] = $params["const"];
    }

    return $str;
}

function out_header($params, $num_params)
{
    // -------- рівняння
    echo "<label id='asd'>F<sub>" . $params["func"] . "</sub> = ";
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
    return $lims;
}

function get_koef($koef, $f = 0)
{
    if ($f)
        return $koef;
    if ($koef < 0) {
        return " - " . abs($koef);
    } else {
        return " + " . $koef;
    }
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

// ------------------- 1
echo "<div id='calc_1'>";
$lims = out_header($params, $num_params);
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

// ------------- 2
echo "<div id='calc_2'>";
out_header($params, $num_params);
echo "<label>Запишемо функцію Лагранжа</label><br>";
echo "<label>L(X, Λ) = ";
echo get_equation($params);
//print_r($lims);
$lim_last = $params["var_count"] + 1;

$wo_0 = array();
for ($i = 0; $i < $params["lim_count"]; $i++) {
    if (($lims[$i][0] == 1 && $lims[$i][1] == 0 && $lims[$i][$lim_last] == 0) ||
        ($lims[$i][0] == 0 && $lims[$i][1] == 1 && $lims[$i][$lim_last] == 0)
    ) {
        continue;
    }
    $wo_0[] = $lims[$i];
}

for ($i = 0; $i < count($wo_0); $i++) {
    if ($params["func"] === "max") {
        if ($wo_0[$i][$lim_last - 1] === "≤" || $wo_0[$i][$lim_last - 1] === "=")
            echo " + ";
        else
            echo " - ";
    } else {
        if ($wo_0[$i][$lim_last - 1] === "≤" || $wo_0[$i][$lim_last - 1] === "=")
            echo " - ";
        else
            echo " + ";
    }
    if (count($wo_0) === 1) {
        echo "λ(" . $wo_0[$i][$lim_last];
    } else {
        echo "λ<sub>" . ($i + 1) . "</sub>(" . $wo_0[$i][$lim_last];
    }
    for ($j = 0; $j < $params["var_count"]; $j++) {
        if ($wo_0[$i][$j] > 0) {
            echo " - ";
        } else {
            echo " + ";
        }
        echo (abs($wo_0[$i][$j]) == 1 ? "" : abs($wo_0[$i][$j])) . "x<sub>" . ($j + 1), "</sub>";
    }
    echo ")";
}
echo "</label><br>";
echo "<table>";
if ($params["var_count"] == 2) {
    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label> = " . $num_params[0] . get_koef($num_params[2] * 2) . "x<sub>1</sub> " .
        get_koef($num_params[4]) . "x<sub>2</sub>";
    for ($i = 0; $i < count($wo_0); $i++) {
        if (count($wo_0) == 1)
            echo get_koef($wo_0[$i][0] * -1) . "λ";
        else {
            echo get_koef($wo_0[$i][0] * -1) . "λ<sub>" . ($i + 1) . "</sub>";
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
            <td rowspan='2'><label> = " . $num_params[1] . get_koef($num_params[3] * 2) . "x<sub>2</sub> " .
        get_koef($num_params[4]) . "x<sub>1</sub>";
    for ($i = 0; $i < count($wo_0); $i++) {
        if (count($wo_0) == 1)
            echo get_koef($wo_0[$i][1] * -1) . "λ";
        else {
            echo get_koef($wo_0[$i][1] * -1) . "λ<sub>" . ($i + 1) . "</sub>";
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

    for ($i = 0; $i < count($wo_0); $i++) {
        if ($i == 0)
            echo $wo_0[$i][$lim_last];
        else
            echo get_koef($wo_0[$i][$lim_last]);
        for ($j = 0; $j < $params["var_count"]; $j++) {
            if ($wo_0[$i][$j] > 0) {
                echo " - ";
            } else {
                echo " + ";
            }
            echo abs($wo_0[$i][$j]) . "x<sub>" . ($j + 1), "</sub>";
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
echo "<br><br>
<input class=\"butt\" type='button' value='<--' id='calc_2_back'>
<input class=\"butt\" type='button' value='-->' id='calc_2_next'>
</div>";
echo "</div>";

// ----------------------- 3

echo "<div id='calc_3'>";
out_header($params, $num_params);
echo "<br><label>Обмеження, що відповідають нерівностям, запишемо у вигляді</label><br><br>";

echo "<label>" . get_koef($num_params[2] * 2, 1) . "x<sub>1</sub> " .
    get_koef($num_params[4]) . "x<sub>2</sub>";
for ($i = 0; $i < count($wo_0); $i++) {
    if (count($wo_0) == 1)
        echo get_koef($wo_0[$i][0] * -1) . "λ";
    else {
        echo get_koef($wo_0[$i][0] * -1) . "λ<sub>" . ($i + 1) . "</sub>";
    }
}
echo " ≤ " . get_koef($num_params[0] * -1, 1) . "</label>";

echo "<br><label>" . get_koef($num_params[4], 1) . "x<sub>1</sub>" . get_koef($num_params[3] * 2) . "x<sub>2</sub> ";
for ($i = 0; $i < count($wo_0); $i++) {
    if (count($wo_0) == 1)
        echo get_koef($wo_0[$i][1] * -1) . "λ";
    else {
        echo get_koef($wo_0[$i][1] * -1) . "λ<sub>" . ($i + 1) . "</sub>";
    }
}
echo " ≤ " . get_koef($num_params[1] * -1, 1) . "</label>";

echo "<br><label>";

for ($i = 0; $i < count($wo_0); $i++) {

    for ($j = 0; $j < $params["var_count"]; $j++) {
        if ($wo_0[$i][$j] > 0) {
            echo " - ";
        } else {
            echo " + ";
        }
        echo abs($wo_0[$i][$j]) . "x<sub>" . ($j + 1), "</sub>";
    }
    echo " ≥ " . get_koef($wo_0[$i][$lim_last] * -1, 1) . "<br>";
}
echo "</label>";
echo "<br><label>Вводимо додаткові змінні для зведення нерівностей до рівнянь</label><br><br>";

$need_minus_1 = 0;
echo "<label>" . get_koef($num_params[2] * 2, 1) . "x<sub>1</sub> " .
    get_koef($num_params[4]) . "x<sub>2</sub>";
for ($i = 0; $i < count($wo_0); $i++) {
    if (count($wo_0) == 1)
        echo get_koef($wo_0[$i][0] * -1) . "λ";
    else {
        echo get_koef($wo_0[$i][0] * -1) . "λ<sub>" . ($i + 1) . "</sub>";
    }
}
echo " + v<sub>1</sub> = " . get_koef($num_params[0] * -1, 1) . "</label>";
if ($num_params[2] < 0) {
    $need_minus_1 = 1;
}
echo "<br><label>" . get_koef($num_params[4], 1) . "x<sub>1</sub>" . get_koef($num_params[3] * 2) . "x<sub>2</sub> ";
for ($i = 0; $i < count($wo_0); $i++) {
    if (count($wo_0) == 1)
        echo get_koef($wo_0[$i][1] * -1) . "λ";
    else {
        echo get_koef($wo_0[$i][1] * -1) . "λ<sub>" . ($i + 1) . "</sub>";
    }
}
echo " + v<sub>2</sub> = " . get_koef($num_params[1] * -1, 1) . "</label>";
if ($num_params[4] < 0) {
    $need_minus_1 = 1;
}
echo "<br><label>";

for ($i = 0; $i < count($wo_0); $i++) {

    for ($j = 0; $j < $params["var_count"]; $j++) {
        if ($wo_0[$i][$j] > 0) {
            echo " - ";
        } else {
            echo " + ";
        }
        echo abs($wo_0[$i][$j]) . "x<sub>" . ($j + 1), "</sub>";
    }
    echo " - w<sub>" . ($i + 1) . "</sub> = " . get_koef($wo_0[$i][$lim_last] * -1, 1) . "<br>";
    if ($wo_0[$i][$lim_last] < 0) {
        $need_minus_1 = 1;
    }
}
echo "</label>";

echo "<br><br>
<input class=\"butt\" type='button' value='<--' id='calc_3_back'>
<input class=\"butt\" type='button' value='-->' id='calc_3_next'>
";
echo "</div>";


// ------------------ 4

echo "<div id='calc_4'>";
out_header($params, $num_params);

if ($need_minus_1) {
    echo "<br><label>Для зведення задачі до канонічної форми помножимо рівняння на (–1)</label><br><br>";
    $mult = 1;
    if ($num_params[0] * -1 < 0) {
        $mult = -1;
    }
    echo "<label>" . get_koef($num_params[2] * 2 * $mult, 1) . "x<sub>1</sub> " .
        get_koef($num_params[4] * $mult) . "x<sub>2</sub>";
    for ($i = 0; $i < count($wo_0); $i++) {
        if (count($wo_0) == 1)
            echo get_koef($wo_0[$i][0] * -1 * $mult) . "λ";
        else {
            echo get_koef($wo_0[$i][0] * -1 * $mult) . "λ<sub>" . ($i + 1) . "</sub>";
        }
    }
    echo ($mult == 1 ? " + v<sub>1</sub> = " : " - v<sub>1</sub> = ") . get_koef($num_params[0] * -1 * $mult, 1) . "</label>";

    $mult = 1;
    if ($num_params[1] * -1 < 0) {
        $mult = -1;
    }
    echo "<br><label>" . get_koef($num_params[4] * $mult, 1) . "x<sub>1</sub>" .
        get_koef($num_params[3] * 2 * $mult) . "x<sub>2</sub> ";
    for ($i = 0; $i < count($wo_0); $i++) {
        if (count($wo_0) == 1)
            echo get_koef($wo_0[$i][1] * -1 * $mult) . "λ";
        else {
            echo get_koef($wo_0[$i][1] * -1 * $mult) . "λ<sub>" . ($i + 1) . "</sub>";
        }
    }
    echo ($mult == 1 ? " + v<sub>2</sub> = " : " - v<sub>2</sub> = ") . get_koef($num_params[1] * -1 * $mult, 1) . "</label>";

    echo "<br><label>";

    for ($i = 0; $i < count($wo_0); $i++) {
        $mult = 1;
        if ($wo_0[$i][$lim_last] > 0) {
            $mult = -1;
        }
        for ($j = 0; $j < $params["var_count"]; $j++) {

            echo ($j == 0 ? get_koef($wo_0[$i][$j] * -1 * $mult, 1) : get_koef($wo_0[$i][$j] * -1 * $mult))
                . "x<sub>" . ($j + 1), "</sub>";
        }
        echo ($mult == 1 ? " - w<sub>" . ($i + 1) . "</sub> = " :
                " + w<sub>" . ($i + 1) . "</sub> = ") . get_koef($wo_0[$i][$lim_last] * -1 * $mult, 1) . "<br>";
    }
    echo "</label>";
}

echo "<br>
<label>Введемо штучні змінні.</label><br>
<label>Маємо таку задачу лінійного програмування:</label><br><br>
<label>maxF' = ";
if ($params["func"] === "max") {
    echo "-Mα<sub>1</sub> - Mα<sub>2</sub>";
} else {
    echo "Mα<sub>1</sub> + Mα<sub>2</sub>";
}
echo "</label><br>";

$a_count = 0;

$mult = 1;
if ($num_params[0] * -1 < 0) {
    $mult = -1;
}
$need_shtuch = 1;
if (($num_params[2] * 2 * $mult == 1) || ($num_params[4] * $mult) == 1) {
    $need_shtuch = 0;
}
echo "<label>" . get_koef($num_params[2] * 2 * $mult, 1) . "x<sub>1</sub> " .
    get_koef($num_params[4] * $mult) . "x<sub>2</sub>";
for ($i = 0; $i < count($wo_0); $i++) {
    if ($wo_0[$i][0] * -1 * $mult == 1) {
        $need_shtuch = 0;
    }
    if (count($wo_0) == 1)
        echo get_koef($wo_0[$i][0] * -1 * $mult) . "λ";
    else {
        echo get_koef($wo_0[$i][0] * -1 * $mult) . "λ<sub>" . ($i + 1) . "</sub>";
    }
}
if ($mult == 1) {
    $need_shtuch = 0;
}
if ($need_shtuch) {
    $a_count++;
    echo ($mult == 1 ? " + v<sub>1</sub> = " : " - v<sub>1</sub> + α<sub>1</sub> = ") . get_koef($num_params[0] * -1 * $mult, 1) . "</label>";
} else
    echo ($mult == 1 ? " + v<sub>1</sub> = " : " - v<sub>1</sub> = ") . get_koef($num_params[0] * -1 * $mult, 1) . "</label>";


$need_shtuch = 1;
$mult = 1;
if ($num_params[1] * -1 < 0) {
    $mult = -1;
}
$need_shtuch = 1;
if (($num_params[4] * $mult) == 1 || ($num_params[3] * 2 * $mult) == 1) {
    $need_shtuch = 0;
}
echo "<br><label>" . get_koef($num_params[4] * $mult, 1) . "x<sub>1</sub>" .
    get_koef($num_params[3] * 2 * $mult) . "x<sub>2</sub> ";
for ($i = 0; $i < count($wo_0); $i++) {
    if ($wo_0[$i][1] * -1 * $mult == 1) {
        $need_shtuch = 0;
    }
    if (count($wo_0) == 1)
        echo get_koef($wo_0[$i][1] * -1 * $mult) . "λ";
    else {
        echo get_koef($wo_0[$i][1] * -1 * $mult) . "λ<sub>" . ($i + 1) . "</sub>";
    }
}
if ($mult == 1) {
    $need_shtuch = 0;
}
if ($need_shtuch) {
    $a_count++;
    echo ($mult == 1 ? " + v<sub>2</sub> = " : " - v<sub>2</sub> + α<sub>2</sub> = ") . get_koef($num_params[1] * -1 * $mult, 1) . "</label>";
} else
    echo ($mult == 1 ? " + v<sub>2</sub> = " : " - v<sub>2</sub> = ") . get_koef($num_params[1] * -1 * $mult, 1) . "</label>";

echo "<br><label>";

for ($i = 0; $i < count($wo_0); $i++) {
    $need_shtuch = 1;
    $mult = 1;
    if ($wo_0[$i][$lim_last] > 0) {
        $mult = -1;
    }
    for ($j = 0; $j < $params["var_count"]; $j++) {
        if ($wo_0[$i][$j] * -1 * $mult == 1) {
            $need_shtuch = 0;
        }
        echo ($j == 0 ? get_koef($wo_0[$i][$j] * -1 * $mult, 1) : get_koef($wo_0[$i][$j] * -1 * $mult))
            . "x<sub>" . ($j + 1), "</sub>";
    }
    if ($mult == -1) {
        $need_shtuch = 0;
    }
    if ($need_shtuch == 1) {
        $a_count++;
        echo ($mult == 1 ? " - w<sub>" . ($i + 1) . "</sub> = " :
                " + w<sub>" . ($i + 1) . "</sub> + α<sub>" . ($i + 3) . "</sub> = ") .
            get_koef($wo_0[$i][$lim_last] * -1 * $mult, 1) . "<br>";
    } else
        echo ($mult == 1 ? " - w<sub>" . ($i + 1) . "</sub> = " :
                " + w<sub>" . ($i + 1) . "</sub> = ") . get_koef($wo_0[$i][$lim_last] * -1 * $mult, 1) . "<br>";
}
for ($i = 0; $i < $params["var_count"]; $i++) {
    echo "x<sub>" . ($i + 1) . "</sub> &ge; 0, ";
}
for ($i = 0; $i < count($wo_0); $i++) {
    if (count($wo_0) > 1)
        echo "λ<sub>" . ($i + 1) . "</sub> &ge; 0, ";
    else
        echo "λ &ge; 0, ";
}
for ($i = 0; $i < count($wo_0); $i++) {
    echo "w<sub>" . ($i + 1) . "</sub> &ge; 0, ";
}
for ($i = 0; $i < $params["var_count"]; $i++) {
    if ($a_count > 0)
        echo "v<sub>" . ($i + 1) . "</sub> &ge; 0, ";
    else {
        if ($i < $params["var_count"] - 1) {
            echo "v<sub>" . ($i + 1) . "</sub> &ge; 0, ";
        } else {
            echo "v<sub>" . ($i + 1) . "</sub> &ge; 0";
        }
    }
}
for ($i = 0; $i < $a_count; $i++) {
    if ($i < $a_count - 1)
        echo "α<sub>" . ($i + 1) . "</sub> &ge; 0, ";
    else
        echo "α<sub>" . ($i + 1) . "</sub> &ge; 0";

}

echo "</label>";


//α

echo "<br><br>
<input class=\"butt\" type='button' value='<--' id='calc_4_back'>
<input class=\"butt\" type='button' value='&ndash;&gt;' id='calc_4_next'>
";
echo "</div>";

echo "<div id='calc_5'>";

$res = array(13 / 6, 1 / 6, 0, 0, 0, 7 / 6, 0, 0);
out_header($params, $num_params);
echo "<label>Розв'завши ЗЛП симплекс-методом отримуємо</label><br><br><label>";

$index = 0;
for ($i = 0; $i < $params["var_count"]; $i++) {
    echo "x<sub>" . ($i + 1) . "</sub><sup>*</sup> = " . floor_res($res[$index++]) . ", ";
}
for ($i = 0; $i < count($wo_0); $i++) {
    if (count($wo_0) > 1)
        echo "λ<sub>" . ($i + 1) . "</sub><sup>*</sup> = " . floor_res($res[$index++]) . ", ";
    else
        echo "λ<sup>*</sup> = " . floor_res($res[$index++]) . ", ";
}
for ($i = 0; $i < $params["var_count"]; $i++) {
    echo "v<sub>" . ($i + 1) . "</sub> = " . floor_res($res[$index++]) . ", ";
}
for ($i = 0; $i < count($wo_0); $i++) {
    echo "w<sub>" . ($i + 1) . "</sub> = " . floor_res($res[$index++]) . ", ";
}
for ($i = 0; $i < $a_count; $i++) {
    if ($i < $a_count - 1)
        echo "α<sub>" . ($i + 1) . "</sub> = " . floor_res($res[$index++]) . ", ";
    else
        echo "α<sub>" . ($i + 1) . "</sub> = " . floor_res($res[$index++]) . "";

}
echo "</label><br><br><label>Перевірка виконання умов:</label><br>";
echo "<table>";
if ($params["var_count"] == 2) {
    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label>x<sub>1</sub><sup>*</sup> = x<sub>1</sub><sup>*</sup>v<sub>1</sub><sup>*</sup> = 
            " . floor_res($res[0]) . " * " . floor_res($res[$params["var_count"] + count($wo_0)]) . " = 
            " . floor_res($res[0]) * floor_res($res[$params["var_count"] + count($wo_0)]) . "

        </tr>
        <tr>
            <td><label>∂x<sub>1</sub></label></td>
        </tr>";

    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label>x<sub>2</sub><sup>*</sup> = x<sub>2</sub><sup>*</sup>v<sub>2</sub><sup>*</sup> = 
            " . floor_res($res[1]) . " * " . floor_res($res[$params["var_count"] + count($wo_0) + 1]) . " = 
            " . floor_res($res[1]) * floor_res($res[$params["var_count"] + count($wo_0) + 1]) . "

        </tr>
        <tr>
            <td><label>∂x<sub>2</sub></label></td>
        </tr>";

    echo "
        <tr>
            <td style='border-bottom: 1px white solid'><label>∂L</label></td>
            <td rowspan='2'><label>λ<sup>*</sup> = λ<sup>*</sup>w<sub>1</sub><sup>*</sup> = 
            " . floor_res($res[2]) . " * " . floor_res($res[$params["var_count"] + count($wo_0) + 2]) . " = 
            " . floor_res($res[2]) * floor_res($res[$params["var_count"] + count($wo_0) + 2]) . "

        </tr>
        <tr>
            <td><label>∂λ</label></td>
        </tr>";
}
echo "</table>";
echo "<label>Всі умови виконуються, отже (X<sup>*</sup>, Λ<sup>*</sup>) = (x<sub>1</sub><sup>*</sup> = " .
    floor_res($res[0]) . ", x<sub>2</sub><sup>*</sup> = " .
    floor_res($res[1]) . ", λ<sup>*</sup> = " .
    floor_res($res[2]) . ") є сідловою точкою <br> функції Лагранжа для задачі 
    квадратичного програмування, <br>
    а X<sup>*</sup>(x<sub>1</sub><sup>*</sup> = " .
    floor_res($res[0]) . ", x<sub>2</sub><sup>*</sup> = " .
    floor_res($res[1]) . ") - оптимальним планом задачі, для якого значення функціонала дорівнює:<br>
    F = ";

echo get_koef($params["x<sub>1</sub>"], 1) . " * " . floor_res($res[0]);
echo get_koef($params["x<sub>2</sub>"]) . " * " . floor_res($res[1]);
echo get_koef($params["x<sup>2</sup><sub>1</sub>"]) . " * " . floor_res($res[0] * $res[0]);
echo get_koef($params["x<sup>2</sup><sub>2</sub>"]) . " * " . floor_res($res[1] * $res[1]);
echo get_koef($params["x<sub>1</sub>x<sub>2</sub>"]) . " * " . floor_res($res[0]) . " * " . floor_res($res[1]);
if ($params["const"]) {
    echo get_koef($params["const"]);
}
echo " = <strong><u>";
echo floor_res(
        $params["x<sub>1</sub>"] * $res[0] +
        $params["x<sub>2</sub>"] * $res[1] +
        $params["x<sup>2</sup><sub>1</sub>"] * $res[0] * $res[0] +
        $params["x<sup>2</sup><sub>2</sub>"] * $res[1] * $res[1] +
        $params["x<sub>1</sub>x<sub>2</sub>"] * $res[0] * $res[1] +
        $params["const"]
);

echo "</strong></u></label><br><br>
<input class=\"butt\" type='button' value='<--' id='calc_5_back'>
<!--<input class=\"butt\" type='button' value='&ndash;&gt;' id='calc_5_next'>-->
";
echo "</div>";

?>

<footer>
    <span class="left">Бас Євгеній &copy; 2017</span><span class="right">
    <a href="https://vk.com/id189464921"><img src="image/vk.png" alt="Профіль ВК" title="Профіль ВК"></a></span>
</footer>
<script src="script.js"></script>
</body>
</html>
