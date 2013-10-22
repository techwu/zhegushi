<?php
function smarty_modifier_truncate_utf8($string, $length, $etc = '..')
{
$result = '';

$string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'utf-8');

for($i = 0, $j = 0; $i < strlen($string); $i++)
{
if($j >= $length)
{
for($x = 0, $y = 0; $x < strlen($etc); $x++)
{
if($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
{
$x += $number - 1;
$y++;
}
else
{
$y += 0.5;
}
}

$length -= $y;

break;
}

if($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
{
$i += $number - 1;
$j++;
}
else
{
$j += 0.5;
}
}

for($i = 0; (($i < strlen($string)) && ($length > 0)); $i++)
{
if($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
{
if($length < 1.0)
{
break;
}

$result .= substr($string, $i, $number);

$length -= 1.0;

$i += $number - 1;
}
else
{
$result .= substr($string, $i, 1);

$length -= 0.5;
}
}

$result = htmlentities($result, ENT_QUOTES, 'utf-8');

if($i < strlen($string))
{
$result .= $etc;
}

return $result;
}