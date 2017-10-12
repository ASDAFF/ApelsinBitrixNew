<?php
class APLSContactsDateTimeTable
{
    private $timeTable = array();
    private $dateTimeStrings = array();
    private $html;

    public function __construct($timeTable)
    {
        foreach ($timeTable as $row) {
            $this->timeTable[] = $row;
        }
        $this->generateDateTimeStrings();
    }

    public function test() {
        echo "<pre>";
        var_dump($this->dateTimeStrings);
        echo "</pre>";
    }

    public function get(){
        echo $this->html;
    }

    public function getHTML(){
        return $this->html;
    }

    private function generateDateTimeStrings() {
        $this->dateTimeStrings = array();
        $amountDays = count($this->timeTable);
        if($amountDays > 1) {
            $tmpTimeTable['Time'] = $this->timeTable[0]['DayTime'];
            $tmpTimeTable['Start'] = $this->timeTable[0]['DayName'];
            $tmpTimeTable['End'] = $this->timeTable[0]['DayName'];
            for ($i=1; $i < $amountDays; $i++) {
                if($i == $amountDays-1 && $tmpTimeTable['Time'] != $this->timeTable[$i]['DayTime']) {
                    $this->addDateTimeString($tmpTimeTable['Start'], $this->timeTable[$i - 1]['DayName'], $this->timeTable[$i - 1]['DayTime'], $this->timeTable[$i - 1]['Weekend']);
                    $this->addDateTimeString($this->timeTable[$i]['DayName'], $this->timeTable[$i]['DayName'], $this->timeTable[$i]['DayTime'], $this->timeTable[$i]['Weekend']);
                } elseif($i == $amountDays-1 && $this->timeTable[$i]['Weekend']) {
                    if($tmpTimeTable['Start'] == $this->timeTable[$i]['DayName']) {
                        $this->addDateTimeString($tmpTimeTable['Start'],$this->timeTable[$i]['DayName'],$this->timeTable[$i]['DayTime'], $this->timeTable[$i]['Weekend']);
                    } else {
                        $this->addDateTimeString($tmpTimeTable['Start'], $this->timeTable[$i - 1]['DayName'], $this->timeTable[$i - 1]['DayTime'], $this->timeTable[$i - 1]['Weekend']);
                        $this->addDateTimeString($this->timeTable[$i]['DayName'], $this->timeTable[$i]['DayName'], $this->timeTable[$i]['DayTime'], $this->timeTable[$i]['Weekend']);
                    }
                } elseif($i == $amountDays-1) {
                    $this->addDateTimeString($tmpTimeTable['Start'],$this->timeTable[$i]['DayName'],$this->timeTable[$i]['DayTime'], $this->timeTable[$i]['Weekend']);
                } elseif($this->timeTable[$i]['Weekend']) {
                    $this->addDateTimeString($tmpTimeTable['Start'],$this->timeTable[$i-1]['DayName'],$this->timeTable[$i-1]['DayTime'], $this->timeTable[$i - 1]['Weekend']);
                    $tmpTimeTable['Start'] = $this->timeTable[$i]['DayName'];
                    $tmpTimeTable['Time'] = $this->timeTable[$i]['DayTime'];
                } elseif ($tmpTimeTable['Time'] != $this->timeTable[$i]['DayTime']) {
                    $this->addDateTimeString($tmpTimeTable['Start'],$this->timeTable[$i-1]['DayName'],$this->timeTable[$i-1]['DayTime'], $this->timeTable[$i - 1]['Weekend']);
                    $tmpTimeTable['Start'] = $this->timeTable[$i]['DayName'];
                    $tmpTimeTable['Time'] = $this->timeTable[$i]['DayTime'];
                }
            }
        }
        $this->generateHTML();
    }

    private function addDateTimeString($start,$end,$time,$weekend) {
        if($start != $end) {
            $day = $start." - ".$end;
        } else {
            $day = $start;
        }
        if($weekend) {
            $weekend = "weekend";
        } else {
            $weekend = "weekdays";
        }
        $this->dateTimeStrings[] = array("day" => $day, "time" => $time, "weekend" => $weekend);
    }

    private function generateHTML() {
        $this->html = '<table class="DateTimeTable">';
        foreach ($this->dateTimeStrings as $row) {
            $this->html .= '<tr class="'.$row["weekend"].'">';
            $this->html .= '<td>'.$row["day"].'</td>';
            $this->html .= '<td>'.$row["time"].'</td>';
            $this->html .= '</tr>';
        }
        $this->html .= '</table>';
    }
}