<?php
if (!function_exists('num2str'))
{
    function num2str($num) {
        $nul = 'нуль';
        $ten = array(
            array('', 'одна', 'двi', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять'),
            array('', 'одна', 'дві', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять'),
        );
        $a20 = array('десять', 'одинадцять', 'дванадцять', 'тринадцять', 'чотирнадцять', 'п\'ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев\'ятнадцять');
        $tens = array(2 => 'двадцять', 'тридцять', 'сорок', 'п\'ятьдесят', 'шістьдесят', 'сімдесят', 'вісімдесят', 'дев\'яносто');
        $hundred = array('', 'сто', 'двісті', 'триста', 'чотириста', 'п\'ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев\'ятсот');
        $unit = array(
            array('копійка', 'копійки', 'копійок', 1),
            array('гривня', 'гривні', 'гривень', 0),
            array('тисяча', 'тисячі', 'тисяч', 1),
            array('мільйон', 'мільйони', 'мільйонів', 0),
            array('мільярд', 'мільярди', 'мільярдів', 0),
        );
        //
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub) > 0)
        {
            foreach (str_split($rub, 3) as $uk => $v)
            {
                if (!intval($v))
                    continue;
                $uk = sizeof($unit) - $uk - 1;
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                $out[] = $hundred[$i1];
                if ($i2 > 1)
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
                else
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
                if ($uk > 1)
                    $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            }
        }
        else
        $out[] = $nul;
        $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);
        $out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]);
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20)
            return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5)
            return $f2;
        if ($n == 1)
            return $f1;
        return $f5;
    }

}
?>
