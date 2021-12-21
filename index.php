<?php
$pers = file_get_contents("people.json");
$pers = json_decode($pers, true);
$pers_rand = array_rand($pers);

if (isset($_POST["question"]) && isset($_POST["person"])) {

    $question = $_POST["question"];

    $en_name = $_POST["person"];
    $aya_example[0]  = urlencode("آیا");
    $aya_example[1]  = urlencode("ایا");
    $aya_example[2]  = strlen($aya_example[0]);
    $is_question["aya"] = str_split(urlencode($question), $aya_example[2]);
    $is_question["why"] = str_split(urlencode($question), 3);
    $is_question["is"] = str_split(urlencode($question), 2);

    $is_sign["en"] = substr($question, -1);
    $is_sign["fa"] = substr(urlencode($question), -6);
    $is_sign["exm_fa"] = urlencode("؟");

    if ($is_question["aya"][0] === $aya_example[0] || $is_question["aya"][0] === $aya_example[1] || strtolower($is_question["why"][0]) === "why" || strtolower($is_question["is"][0]) === "is") {
        if ($is_sign["fa"] === $is_sign["exm_fa"] || $is_sign["en"] === "?") {
            $n_hash = $en_name . $question;
            $hash_num = intval(hash("crc32b", $n_hash), 30);
            $i = 0;
            $handle = fopen("messages.txt", "r");
            while (($line = fgets($handle)) !== false) {
                $ans[$i] = $line;
                $i++;
            }
            fclose($handle);
            $arr_len = count($ans);
            $show_ans = $hash_num % $arr_len;
            $msg = $ans[$show_ans];
        } else {
            $msg = "سوال درستی پرسیده نشده!";
        }
    } else {
        $msg = "سوال درستی پرسیده نشده!";
    }

    $fa_name = $pers[$en_name];
} else {
    $question = null;
    $pers_rand = array_rand($pers);
    $en_name = $pers_rand;
    $fa_name = $pers[$pers_rand];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>

<body>
    <p id="copyright">پروژه مشاوره بزرگان - محمد مهدی کریمی</p>
    <div id="wrapper">
        <?php
        if (isset($question)) {
            echo "<div id=\"title\">\n<span id=\"label\">پرسش:</span>\n<span id=\"question\">$question</span>\n</div>\n";
        }
        ?>
        <div id="container">
            <div id="message">
                <p><?php
                    if (isset($msg)) {
                        echo $msg;
                    } else {
                        echo "سوال خود را بپرس! سوال با آیا شروع و با علامت سوال تمام شود.";
                    }
                    ?></p>
            </div>
            <div id="person">
                <div id="person">
                    <img src="images/people/<?php echo "$en_name.jpg" ?>" />
                    <p id="person-name"><?php echo $fa_name ?></p>
                </div>
            </div>
        </div>
        <div id="new-q">
            <form method="post" accept-charset="UTF-8">
                سوال
                <input type="text" name="question" <?php if(isset($question)){echo "value=\"" . $question . "\"";}; ?> maxlength="150" placeholder="..." />
                را از
                <select name="person">
                    <?php
                    if (isset($question)) {
                        foreach ($pers as $key => $value) {
                            if ($key == $en_name) {
                                echo "<option value=\"" . $key . "\" selected >" . $value . "</option>\n";
                            } else {
                                echo "<option value=\"" . $key . "\" >" . $value . "</option>\n";
                            }
                        }
                    } else {
                        foreach ($pers as $key => $value) {
                            if ($key == $pers_rand) {
                                echo "<option value=\"" . $key . "\" selected >" . $value . "</option>\n";
                            } else {
                                echo "<option value=\"" . $key . "\" >" . $value . "</option>\n";
                            }
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="بپرس" />
            </form>
        </div>
    </div>
</body>

</html>