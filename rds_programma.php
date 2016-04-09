<?php

class Show {
    public $name;
    public $presentator;
    public $start;
    public $end;
}
    
function get_current_show($data, $now = null) {
    $document = simplexml_load_string($data);
    
    if ($now !== null) {
        $now = strtotime($now);
    } else {
        $now = time();
    }
    $day = date('N', $now);
    
     // echo date('c', $now).PHP_EOL;
    
    $showsToday = $document->xpath('//item/day[text()="'.$day.'"]/..');
    
    foreach($showsToday as $show) {
        $startTime = strtotime($show->start, $now);
        $endTime = strtotime($show->end, $now);
        
        if ($endTime < $startTime) {
            $endTime = strtotime('tomorrow ' . $show->end, $now);
        }
        
        // echo date('c', $startTime).' - '. date('c', $endTime).': ' . $show->name.PHP_EOL;
        
        if ($startTime > $now) {
            continue;
        }
        
        if ($endTime <= $now) {
            continue;
        }
        
        
        $output = new Show();
        $output->name = (string)$show->name;
        $output->presentator = (string)$show->presentator;
        $output->start = (string)$show->start;
        $output->end = (string)$show->end;
        return $output;
    }
    
    return null;
}

$xml = file_get_contents('http://www.zuidwesttv.nl/teksttv/fmprogrammering.xml');

$show = get_current_show($xml);

if($show === null) {
    echo 'GEEN PROGRAMMA';
} else {
    if(!empty($show->presentator)) {
        echo $show->presentator .' met '. $show->name .' (' .$show->start .' tot '.$show->end.')';
    } else {
        echo $show->name .' (' .$show->start .' tot '.$show->end.')';
    }
    
}

// newline
echo PHP_EOL;
