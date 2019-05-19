<?php

namespace App\Models;

use PDO;

class Conference
{
    public $conferenceID;
    public $name;
    public $url;
    public $contactName;
    public $contactEmail;

    public function __construct(int $conferenceID, string $name)
    {
        $this->conferenceID = $conferenceID;
        $this->name = $name;
    }

    private function loadConferences(string $whereClause, array $whereParams, PDO $db) : array
    {
        $conferences = [];
        $query = '
            SELECT ConferenceID, Name, URL, ContactName, ContactEmail
            FROM Conferences
            ' . $whereClause . '
            ORDER BY Name';
        $stmt = $db->prepare($query);
        $stmt->execute($whereParams);
        $data = $stmt->fetchAll();
        $output = [];
        foreach ($data as $row) {
            $conference = new Conference($row['ConferenceID'], $row['Name']);
            $conference->url = $row['URL'];
            $conference->contactName = $row['ContactName'];
            $conference->contactEmail = $row['ContactEmail'];
            $output[] = $conference;
        }
        return $output;
    }

    public function loadAllConferences(PDO $db) : array
    {
        return Conference::loadConferences('', [], $db);
    }

    public function loadNonWebsiteConferences(PDO $db) : array
    {
        return Conference::loadConferences('WHERE Name NOT LIKE "%Website%"', [], $db);
    }
}