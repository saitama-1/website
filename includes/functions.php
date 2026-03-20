<?php

/**
 * Làm sạch chuỗi để hiển thị an toàn trong HTML.
 * @param string|null $data Chuỗi đầu vào.
 * @return string Chuỗi đã được làm sạch.
 */
function lamsach($data) {
    if ($data === null) {
        return '';
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Rút gọn một chuỗi văn bản và thêm dấu chấm lửng.
 * @param string|null $text Chuỗi cần rút gọn.
 * @param int $maxLength Độ dài tối đa.
 * @return string Chuỗi đã được rút gọn.
 */
function rutGon($text, $maxLength = 100) {
    if ($text === null) {
        return '';
    }
    if (mb_strlen($text) > $maxLength) {
        $text = mb_substr($text, 0, $maxLength);
        $lastSpace = mb_strrpos($text, ' ');
        if ($lastSpace !== false) {
            $text = mb_substr($text, 0, $lastSpace);
        }
        return $text . '...';
    }
    return $text;
}
