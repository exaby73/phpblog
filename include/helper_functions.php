<?php

function format_date($date)
{
    // Parse date into human readable format
    $date_data = explode("-", $date);
    $date = "{$date_data[2]} ";

    switch ($date_data[1]):
        case "01":
            $date .= "Jan, ";
            break;
        case "02":
            $date .= "Feb, ";
            break;
        case "03":
            $date .= "Mar, ";
            break;
        case "04":
            $date .= "Apr, ";
            break;
        case "05":
            $date .= "May, ";
            break;
        case "06":
            $date .= "Jun, ";
            break;
        case "07":
            $date .= "Jul, ";
            break;
        case "08":
            $date .= "Aug, ";
            break;
        case "09":
            $date .= "Sep, ";
            break;
        case "10":
            $date .= "Oct, ";
            break;
        case "11":
            $date .= "Nov, ";
            break;
        case "12":
            $date .= "Dec, ";
            break;
    endswitch;

    $date .= $date_data['0'];

    return $date;
}
