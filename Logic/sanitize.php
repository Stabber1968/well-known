<?php
/**
 * Filter character
 * @param $string
 * @return string
 */
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}