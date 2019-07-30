<?php

class Event {

    // Tests whether the given ISO8601 string has a time-of-day or not
    const ALL_DAY_REGEX = '/^\d{4}-\d\d-\d\d$/'; // matches strings like "2013-12-29"

    public $color;
    public $title;
    public $allDay; // a boolean
    public $start; // a DateTime
    public $end; // a DateTime, or null
    public $properties = array(); // an array of other misc properties


    // Constructs an Event object from the given array of key=>values.
    // You can optionally force the timezone of the parsed dates.
    public function __construct($array, $timezone=null) {

        $this->title = $array['subject'];

        if ($array['workstate_id'] == 3) {
            $this->color = "#ff9f89";
        } elseif ($array['workstate_id'] == 6) {
            $this->color = "#257e4a";
        }

        if (isset($array['allDay'])) {
            $this->allDay = (bool)$array['allDay'];
        }
        else {
            $this->allDay = preg_match(self::ALL_DAY_REGEX, $array['start_date']) && (!isset($array['end_date']) || preg_match(self::ALL_DAY_REGEX, $array['end_date']));
        }

        if ($this->allDay) {
            $timezone = null;
        }

        // Parse dates
        $this->start = parseDateTime($array['start_date'], $timezone);
        $this->end = isset($array['end_date']) ? parseDateTime($array['end_date'], $timezone) : null;

        // Record misc properties
        foreach ($array as $name => $value) {
            if (!in_array($name, array('subject', 'allDay', 'start_date', 'end_date'))) {
                $this->properties[$name] = $value;
            }
        }
    }


    public function isWithinDayRange($rangeStart, $rangeEnd) {

        $eventStart = stripTime($this->start);
        $eventEnd = isset($this->end) ? stripTime($this->end) : null;

        if (!$eventEnd) {
            return $eventStart < $rangeEnd && $eventStart >= $rangeStart;
        }
        else {
            return $eventStart < $rangeEnd && $eventEnd > $rangeStart;
        }
    }

    public function toArray() {
        $array = $this->properties;
        $array['title'] = $this->title;
        $array['color'] = $this->color;

        if ($this->allDay) {
            $format = 'Y-m-d'; // output like "2013-12-29"
        }
        else {
            $format = 'c'; // full ISO8601 output, like "2013-12-29T09:00:00+08:00"
        }

        $array['start'] = $this->start->format($format);
        if (isset($this->end)) {
            $array['end'] = $this->end->format($format);
        }
        return $array;
    }

}
