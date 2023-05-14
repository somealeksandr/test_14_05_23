<?php

class Mankind implements ArrayAccess, IteratorAggregate
{
    private static $instance;
    private array $people;

    private function __construct()
    {
        $this->people = [];
    }

    public static function getInstance(): Mankind
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function loadFromFile($filename)
    {
        $handle = fopen($filename, 'r');
        if ($handle === false) {
            throw new Exception("Failed to open the file: $filename");
        }

        while (($line = fgets($handle)) !== false) {
            $personData = explode(';', trim($line));
            if (count($personData) === 5) {
                list($id, $name, $surname, $sex, $birthdate) = $personData;
                $this->people[$id] = new Person($id, $name, $surname, $sex, $birthdate);
            }
        }

        fclose($handle);
    }

    public function loadPeopleFromFileChunked($filePath, $chunkSize = 1000) {
        $handle = fopen($filePath, 'r');
        $counter = 0;
        $chunk = [];

        if ($handle !== false) {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $id = $data[0];
                $name = $data[1];
                $surname = $data[2];
                $sex = $data[3];
                $birthdate = DateTime::createFromFormat('d.m.Y', $data[4])->format('Y-m-d');

                $person = new Person($id, $name, $surname, $sex, $birthdate);
                $chunk[$id] = $person;
                $counter++;

                if ($counter === $chunkSize) {
                    $this->processChunk($chunk);
                    $chunk = [];
                    $counter = 0;
                }
            }

            // Process the remaining chunk if there are any records left
            if (!empty($chunk)) {
                $this->processChunk($chunk);
            }

            fclose($handle);
        }
    }

    private function processChunk($chunk) {
        foreach ($chunk as $id => $person) {
            $this->people[$id] = $person;
        }
    }

    public function getPersonById($id)
    {
        return $this->people[$id] ?? null;
    }

    public function getPercentageOfMen()
    {
        $menCount = 0;
        $totalPeople = count($this->people);

        foreach ($this->people as $person) {
            if ($person->getSex() === 'M') {
                $menCount++;
            }
        }

        return $totalPeople > 0 ? ($menCount / $totalPeople) * 100 : 0;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->people[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->people[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        //
    }

    public function offsetUnset($offset)
    {
        //
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->people);
    }
}
