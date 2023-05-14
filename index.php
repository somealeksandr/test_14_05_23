<?php

require 'classes/Person.php';
require 'classes/Mankind.php';

$mankind = Mankind::getInstance();

try {
    $mankind->loadFromFile('files/people.csv');
    // or use $mankind->loadFromFile('people.csv') if the file has more than 1000 records
} catch (Exception $e) {
    echo $e->getMessage();
}

$personById = $mankind->getPersonById(13);
print_r($personById);

$percentageOfMen = $mankind->getPercentageOfMen();
echo "Percentage of men: " . $percentageOfMen . "%" . PHP_EOL;

foreach ($mankind as $person) {
    echo "Person ID: " . $person->getId() . ", Name: " . $person->getName() . " " . $person->getSurname() . ", Sex: " . $person->getSex() . ", Age in days: " . $person->getAgeInDays() . PHP_EOL;
}
