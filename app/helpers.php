<?php
if (! function_exists('vnd')) {
  function vnd($number) {
    return number_format((int)$number, 0, ',', '.').' đ';
  }
}
