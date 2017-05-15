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
    <div id="header"><input type="button" class="butt" value="Назад" id="input_back">
        <label>Задача з квадратичного програмування</label></div>

</header>

<p>Введіть коефіцієнти</p>
<form action="calc.php" method="post">
    <table id="input_table">
        <?php
        $var_count = intval($_GET["var_count"]);
        $lim_count = intval($_GET["lim_count"]);
        $func = $_GET["func"];

        echo "<tr><td>F<sub>$func</sub> = </td>";
        for ($i = 0; $i < $var_count; $i++) {
            echo "<td><input type='text' class='coef' id='x" . ($i + 1) . "' name='x<sub>" . ($i + 1) . "</sub>'> x<sub>" . ($i + 1) . "</sub><sup> </sup></td>";
            if ($i != $var_count ) {
                echo "<td> + </td>";
            }
        }
        for ($i = 0; $i < $var_count; $i++) {
            echo "<td><input type='text' class='coef' id='x" . ($i + 1) . "_2' name='x<sup>2</sup><sub>" . ($i + 1) . "</sub>'> x<sup>2</sup><sub>" . ($i + 1) . "</sub></td>";
            if ($i != $var_count ) {
                echo "<td> + </td>";
            }
        }
        for ($i = 1; $i <= $var_count - 1; $i++) {
            $sec_index = $i + 1;
            for ($j = $var_count - $i; $j > 0; $j--) {
                echo "<td><input type='text' class='coef' id='x" . ($i) . "x" . $sec_index . "' name='x<sub>" . ($i) . "</sub>x<sub>" . ($sec_index) . "</sub>'> x<sub>" . ($i) . "</sub>x<sub>" . ($sec_index++) . "</sub><sup> </sup></td>";
                if ($j != 1) {
                    echo "<td> + </td>";
                }
            }
            if ($i != $var_count - 1)
                echo "<td> + </td>";
        }

        echo "<td> + <input type='text' class='coef' id='const' name='const'><sup> </sup></td></tr></table>";
        echo "<p>Введіть обмеження</p>";
        echo "<table id='lim_table'>";

        for ($i = 0; $i < $lim_count; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $var_count; $j++) {
                echo "<td><input type='text' class='coef' id='lim_" . ($i + 1) . "_x" . ($j + 1) . "' name='lim_" . ($i + 1) . "_x<sub>" . ($j + 1) . "</sub>'> x<sub>" . ($j + 1) . "</sub></td>";
                if ($j != $var_count - 1) {
                    echo "<td> + </td>";
                }
            }
            echo "
            <td><select name='sign_" . ($i + 1) . "'>
                <option>≤</option>
                <option>=</option>
                <option>≥</option>
            </select></td>";
            echo "<td><input type='text' class='coef' id='lim_" . ($i + 1) . "' name='lim_" . ($i + 1) . "'></td>";
            echo "</tr>";
        }

        echo "<tr><td style='padding-top: 10px;' colspan='" . ($var_count * 2 + 1) . "'>
            <input class=\"butt\" type='submit' id='calc' value='Підтвердити'></td></tr>";

        echo "<input type='hidden' name='var_count' value='$var_count'>";
        echo "<input type='hidden' name='lim_count' value='$lim_count'>";
        echo "<input type='hidden' name='func' value='$func'>";
        function get_sum($n)
        {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $i;
            }
            return $sum;
        }

        ?>
    </table>
</form>

<footer>
    <span class="left">Бас Євгеній &copy; 2017</span><span class="right">
            <a href="https://vk.com/id189464921"><img src="image/vk.png" alt="Профіль ВК" title="Профіль ВК"></a></span>
</footer>
<script src="script.js"></script>
</body>
</html>