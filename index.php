<?php
/**
 * Author: Tomas Pavlatka (tomas.pavlatka@gmail.com)
 * Created: 2010-12-28
 */
 
// Needed classes.
require_once './calendar.class.php';

// Object.
$options = array(
    'date_format' => 'd',
    'day_names' => array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'),
    'show_days_from_different_month' => false       
);
$calendarObj =& new PTX_Calendar($options);

// Variables.
$month = (isset($_GET['month'])) ? (int)$_GET['month'] : 1;
$year = (isset($_GET['year'])) ? (int)$_GET['year'] : 2010;

// build month.
echo '<pre>';
    print_r($calendarObj->buildMonth($month,$year));
echo '</pre>';
