<?php

namespace BenTools\SqlDates;

/**
 * Format SQL DateTime
 * Faire un echo de l'objet renvoie l'objet DateTime PHP5 au format chaîne SQL 0000-00-00 00:00:00
 *
 * @author Beno!t POLASZEK - Fev 2013
 */
Class SqlDateTime extends SqlDates {

    const   FORMAT = 'Y-m-d H:i:s';
    const   NULLVALUE = '0000-00-00 00:00:00';

    public function __construct($dateString = 'now', $timeZone = null) {
        $this->dateInit($dateString, $timeZone);
    }

}
    