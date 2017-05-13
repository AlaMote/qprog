<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Квадратичне програмування</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div id="header"><p>Задача з кадратичного програмування</p></div>

</header>

<p>Введіть коефіцієнти</p>
<table id="input_table">
    <?php
    $var_count = intval($_GET["var_count"]);
    $lim_count = intval($_GET["lim_count"]);
    $func = intval($_GET["func"]);

    echo "<tr><td>F<sub>" . $_GET["func"] . "</sub> = </td>";
    for ($i = 0; $i < $var_count; $i++) {
        echo "<td><input type='text' class='coef' id='x" . ($i + 1) . "'> x<sub>" . ($i + 1) . "</sub><sup> </sup></td>";
        if ($i != $var_count - 1) {
            echo "<td> + </td>";
        }
    }
    for ($i = 0; $i < $var_count; $i++) {
        echo "<td><input type='text' class='coef' id='x" . ($i + 1) . "_2'> x<sup>2</sup><sub>" . ($i + 1) . "</sub></td>";
        if ($i != $var_count - 1) {
            echo "<td> + </td>";
        }
    }
    for ($i = 1; $i <= $var_count - 1; $i++) {
        $sec_index = $i + 1;
        for ($j = $var_count - $i; $j > 0; $j--) {
            echo "<td><input type='text' class='coef' id='x" . ($i) . "x'.$sec_index> x<sub>" . ($i) . "</sub>x<sub>" . ($sec_index++) . "</sub><sup> </sup></td>";
            if ($j != 1) {
                echo "<td> + </td>";
            }
        }
        if ($i != $var_count - 1)
            echo "<td> + </td>";
    }

    echo "<td> + <input type='text' class='coef' id='const'><sup> </sup></td></tr></table>";
    echo "<p>Введіть обмеження</p>";
    echo "<table id='lim_table'>";

    for ($i = 0; $i < $lim_count; $i++) {
        echo "<tr>";
        for ($j = 0; $j < $var_count; $j++) {
            echo "<td><input type='text' class='coef' id='lim_".($i+1)."_x".($j+1)."'> x<sub>".($j+1)."</sub></td>";
            if ($j != $var_count - 1) {
                echo "<td> + </td>";
            }
        }
        echo "
            <td><select>
                <option>≤</option>
                <option>=</option>
                <option>≥</option>
            </select></td>";
        echo "<td><input type='text' class='coef' id='lim_".($i+1)."'></td>";
        echo "</tr>";
    }

    echo "</table>";


    function get_sum($n)
    {
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum += $i;
        }
        return $sum;
    }

    ?>


    <footer>
        <span class="left">Бас Євгеній &copy; 2017</span><span class="right">
    <a href="https://vk.com/id189464921"><img src="image/vk.png" alt="Профіль ВК" title="Профіль ВК"></a></span>
    </footer>
    <script src="script.js"></script>
</body>
</html>