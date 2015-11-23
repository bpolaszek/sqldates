<?php

namespace BenTools\SqlDates;
use DateTime;
use DateTimeZone;

/**
 * Classe abstraite SqlDates et ses filles
 * Conversion d'objets DateTime PHP5 au format SQL
 * Il n'est pas possible d'initialiser un objet DateTime PHP5 avec une date SQL nulle (typiquement : '0000-00-00 00:00:00')
 * Cette classe permet donc de considérer que si c'est le cas, l'objet pourra quand même retourner '0000-00-00 00:00:00' lors d'un echo,
 * mais qu'aucune opération d'ajout/retrait de date ou heure ne sera possible.
 *
 * @author Beno!t POLASZEK - Fev 2013
 */
abstract class SqlDates extends DateTime {

    const   FORMAT = ''; // meant to be overridden
    const   NULLVALUE = ''; // meant to be overridden

    protected $dateString;
    protected $timeZone;

    /**
     * Initialisation : la classe fille n'héritera de la classe DateTime PHP5
     * que si la valeur passée au constructeur n'est pas nulle (nulle au sens == static::NULLVALUE)
     *
     * @param string $dateString - Une chaîne formatée pour la classe DateTime
     * @param mixed $timeZone - Une chaîne formatée pour la classe DateTimeZone, ou un objet DateTimeZone (facultatif)
     * @author Beno!t POLASZEK - Fev 2013
     */
    protected function dateInit($dateString = 'now', $timeZone = null) {

        if ($timeZone instanceof DateTimeZone)
            $this->timeZone = $timeZone;

        elseif (is_string($timeZone))
            $this->timeZone = new DateTimeZone($timeZone);

        else
            $this->timeZone = new DateTimeZone('Europe/Paris');

        $this->dateString = $dateString;
        if (!is_null(($dateString)) && $dateString != static::NULLVALUE)
            parent::__construct($dateString, $this->timeZone);

    }

    /**
     * Surcharge de la fonction "Modify" de la classe parente DateTime
     * Si la date originale était 0000-00-00 00:00:00 et qu'on la change en une vraie date,
     * l'objet se régénère afin d'autoriser les opérations (+1 day, - 3 months, etc)
     *
     * Et inversement : si la date originale était correcte et qu'on la change en valeur nulle,
     * l'objet se regénère pour empêcher les opérations
     *
     * @param string $dateString - Une chaîne formatée pour la classe DateTime
     * @return DateTime object
     * @author Beno!t POLASZEK - Fev 2013
     */
    public function modify($dateString) {

        # Si une date nulle est passée, on retourne une nouvelle instance (aucune modif relative)
        if ($dateString == static::NULLVALUE || is_null($this->dateString)) :
            static::__construct($dateString, $this->timeZone);

        # Si une chaîne valide est passée, et que l'instance courante était nulle, on retourne une nouvelle instance
        # Et on permettra les modifs relatives et absolues
        elseif ($this->dateString == static::NULLVALUE && static::DateValidate($dateString)) :
            $this->dateString = $dateString;
            static::__construct($dateString, $this->timeZone);

        # Comportement normal de la méthode
        else :
            parent::modify(static::StringToDate($dateString));

        endif;

        return $this;

    }

    /**
     * Contexte de chaîne : si l'objet DateTime est correct, on renvoie l'objet dans le format demandé (Y-m-d H:i:s par exemple)
     * Sinon, on renvoie la valeur nulle dans le format demandé (0000-00-00 00:00:00 par exemple)
     *
     * @return string
     * @author Beno!t POLASZEK - Fev 2013
     */
    public function __toString() {

        if ($this->dateString == static::NULLVALUE) :
            return (string) $this->dateString;

        else :
            $this->dateString = (string) $this->format(static::FORMAT);
            return (string) $this->dateString;
        endif;

    }

    /**
     * Clonage on-the-fly
     */
    public function copy($dateString = null) {

        $newDate = clone $this;

        if ($dateString)
            $newDate->modify($dateString);

        return $newDate;
    }

    /**
     * Retourne une nouvelle instance de l'objet
     *
     * @author Beno!t POLASZEK - Avr 2013
     * @return SqlDates
     */
    public static function NewInstance() {
        $class = new \ReflectionClass(get_called_class());
        return $class->newInstanceArgs(func_get_args());
    }

    /**
     * Vérification de capacité d'instanciation de la classe parente DateTime
     *
     * @param string $dateString - Une chaîne formatée pour la classe DateTime
     * @return bool true / false
     * @author Beno!t POLASZEK - Fev 2013
     */
    public static function DateValidate($dateString) {
        return (bool) (DateTime::CreateFromFormat(static::FORMAT, static::StringToDate($dateString)) && $dateString != static::NULLVALUE);
    }

    /**
     * Date du jour
     *
     * @param string $dateString - Une chaîne formatée pour la classe DateTime
     * @return string
     * @author Beno!t POLASZEK - Fev 2013
     */
    public static function StringToDate($dateString) {

        if (in_array(strtoupper($dateString), ['CURRENT_DATE', 'CURRENT_TIMESTAMP', 'NOW', 'NOW()']))
            return date(static::FORMAT);

        else
            return $dateString;

    }

    /**
     * Method override:
     * Returns static instead of self
     * @inheritDoc
     * @return static
     */
    public static function createFromFormat($format, $time, DateTimeZone $timezone = null) {
        if (!is_null($timezone) && $dateTime = parent::createFromFormat($format, $time, $timezone))
            return new static($dateTime->format(static::FORMAT), $dateTime->getTimezone());
        elseif (is_null($timezone) && $dateTime = parent::createFromFormat($format, $time))
            return new static($dateTime->format(static::FORMAT), $dateTime->getTimezone());
        else
            return false;
    }

}

    