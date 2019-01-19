<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}

function ConvertToHashrate($value) {
  # If value is not just a number, return it as is
  if(!is_numeric($value)) { return $value; }
  $units = array('H/s','KH/s','MH/s','GH/s','TH/s','PH/s');
  $pow = floor(($value ? log($value) : 0) / log(1000));
  $pow = min($pow, count($units) -1);
  $value /= pow(1000, $pow);
  //echo "VALUE = $value\n";
  return round($value, 2) . ' ' . $units[$pow];
}

function ConvertToByterate($value) {
    # If value is not just a number, return it as is
    if(!is_numeric($value)) { return $value; }
    $units = array('B','KB','MB','GB','TB','PB');
    $pow = floor(($value ? log($value) : 0) / log(1000));
    $pow = min($pow, count($units) -1);
    $value /= pow(1000, $pow);
    //echo "VALUE = $value\n";
    return round($value, 2) . ' ' . $units[$pow];
  }

function ConvertToWattrate($value) {
    # If value is not just a number, return it as is
    if(!is_numeric($value)) { return $value; }
    $units = array('W','KW','MW','GW','TW','PW');
    $pow = floor(($value ? log($value) : 0) / log(1000));
    $pow = min($pow, count($units) -1);
    $value /= pow(1000, $pow);
    //echo "VALUE = $value\n";
    return round($value, 2) . ' ' . $units[$pow];
  }

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>