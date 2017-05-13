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

<form>
    <div id="block">
        <table>
            <tr>
                <td>
                    <label for="var_count">Кількість змінних</label>
                </td>
                <td>
                    <input autofocus type="number" name="var_count" id="var_count" min="1">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="lim_count">Кількість обмежень</label>
                </td>
                <td>
                    <input type="number" name="lim_counte" id="lim_count" min="1">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="func">Функція</label>
                </td>
                <td>
                    <select name="func" id="func">
                        <option>max</option>
                        <option>min</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="button_td">
                    <input type="button" id="calc" value="Підтвердити">
                </td>
            </tr>
        </table>
    </div>

</form>
<footer>
    <span class="left">Бас Євгеній &copy; 2017</span><span class="right">
    <a href="https://vk.com/id189464921"><img src="image/vk.png" alt="Профіль ВК"  title="Профіль ВК"></a></span>
</footer>
<script src="script.js"></script>
</body>
</html>