<?php

namespace BenTools\SqlDates;

/**
 * Format SQL Time
 * Faire un echo de l'objet renvoie l'objet DateTime PHP5 au format chaîne SQL 00:00:00
 *
 * @author Beno!t POLASZEK - Fev 2013
 */
Class SqlTime extends SqlDates {

    const   FORMAT = 'H:i:s';
    const   NULLVALUE = '00:00:00';

    public function __construct($dateString = 'now', $timeZone = null) {
        $this->dateInit($dateString, $timeZone);
    }
}
    