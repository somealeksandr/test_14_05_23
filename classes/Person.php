<?php

class Person
{
    private $id;
    private $name;
    private $surname;
    private $sex;
    private $birthdate;

    public function __construct($id, $name, $surname, $sex, $birthdate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->sex = $sex;
        $this->birthdate = $birthdate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function getAgeInDays()
    {
        $today = new DateTime();
        $birthdate = new DateTime($this->birthdate);
        $interval = $today->diff($birthdate);
        return $interval->days;
    }
}
