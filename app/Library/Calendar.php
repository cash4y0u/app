<?php
namespace App\Library;

use Illuminate\Support\Carbon;

class Calendar
{
    private $ical;

    function __construct()
    {
        $this->ical .= "BEGIN:VCALENDAR\n";
        $this->ical .= "VERSION:2.0\n";
        $this->ical .= "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";
        $this->ical .= "X-PUBLISHED-TTL:PT1H\n";
        $this->ical .= "X-WR-RELCALID:PT1H\n";
        $this->ical .= "REFRESH-INTERVAL;VALUE=DURATION:PT1H\n";
        $this->ical .= "X-WR-TIMEZONE:America/Sao_Paulo\n";
        $this->ical .= "CALSCALE:GREGORIAN\n";
    }

    public function name($string)
    {
        $this->ical .= "X-WR-CALNAME:{$string}\n";
    }
    public function description($string)
    {
        $this->ical .= "X-WR-CALDESC:{$string}\n";
    }
    public function color($string)
    {
        $this->ical .= "X-APPLE-CALENDAR-COLOR:{$string}\n";
    }
    public function event($event)
    {

        if (is_array($event)) {

            $event = json_decode(json_encode($event));
            $this->ical .= "BEGIN:VEVENT\n";
            $this->ical .= "DTSTAMP:" . gmdate('Ymd') . 'T' . gmdate('His') . "Z\n";
            if (isset($event->start)) {
                $this->ical .= "DTSTART:" . Carbon::parse($event->start)->format('Ymd') . "\n";
            }
            if (isset($event->end)) {
                $this->ical .= "DTEND:" . Carbon::parse($event->end)->format('Ymd') . "\n";
            }
            if (isset($event->title)) {
                $this->ical .= "SUMMARY:{$event->title}\n";
            }
            if (isset($event->url)) {
                $this->ical .= "URL:{$event->url}\n";
            }

            $this->ical .= "BEGIN:VALARM\n";
            $this->ical .= "ATTACH;VALUE=URI:Chord\n";
            $this->ical .= "ACTION:AUDIO\n";
            if ($event->id) {
                $this->ical .= "UID:{$event->id}@cash4you.app\n";
                $this->ical .= "X-WR-ALARMUID:{$event->id}\n";
            }
            if (isset($event->trigger)) {
                $this->ical .= "TRIGGER:{$event->trigger}\n";
            }
            $this->ical .= "END:VALARM\n";
            $this->ical .= "END:VEVENT\n";
        }
    }


    function __destruct()
    {
        //dd($this->ical);
        $this->ical .= "END:VCALENDAR\n";
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename=Cash4You.ics');
        echo $this->ical;
        exit;
    }
}
