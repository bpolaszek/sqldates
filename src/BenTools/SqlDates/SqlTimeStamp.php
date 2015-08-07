<?php

namespace BenTools\SqlDates;
use DateTimeZone;

/**
 * Format SQL TimeStamp
 * Faire un echo de l'objet renvoie l'objet DateTime PHP5 au format chaîne SQL UNIX_TIMESTAMP
 *
 * @author Beno!t POLASZEK - Fev 2013
 */
Class SqlTimeStamp extends SqlDates {

    const   FORMAT = 'U';
    const   NULLVALUE = 0;

    public function __construct($dateString = 'now', DateTimeZone $timeZone = null) {
        if (is_integer($dateString))
            $this->dateInit(date('Y-m-d H:i:s', $dateString), $timeZone);
        else
            $this->dateInit($dateString, $timeZone);
    }

    /**
     * Contexte de chaîne
     *
     * @return string
     * @author Beno!t POLASZEK - Fev 2013
     */
    public function __toString() {

        if (($this->Format(static::FORMAT) < 0))
            return (string) static::NULLVALUE;

        else
            return (string) $this->format(static::FORMAT);

    }
}
	