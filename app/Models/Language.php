<?php

namespace App\Models;

use PDO;

class Language
{
    public $languageID;
    public $name;
    public $isDefault;
    public $altName;

    public function __construct(int $languageID, string $name)
    {
        $this->languageID = $languageID;
        $this->name = $name;
        $this->isDefault = false;
    }

    private function loadLanguages(string $whereClause, array $whereParams, PDO $db) : array
    {
        $query = '
            SELECT LanguageID, Name, IsDefault, AltName
            FROM Languages
            ' . $whereClause . '
            ORDER BY Name';
        $stmt = $db->prepare($query);
        $stmt->execute($whereParams);
        $data = $stmt->fetchAll();
        $output = [];
        foreach ($data as $row) {
            $language = new Language($row['LanguageID'], $row['Name']);
            $language->isDefault = $row['IsDefault'];
            $language->altName = $row['AltName'];
            $output[] = $language;
        }
        return $output;
    }

    public function loadAllLanguages(PDO $db) : array
    {
        return Language::loadLanguages('', [], $db);
    }

    public function loadLanguageWithID(int $languageID, PDO $db) : ?Language
    {
        $data = Language::loadLanguages(' WHERE LanguageID = ? ', [$languageID], $db);
        return count($data) > 0 ? $data[0] : null;
    }

    public function findLanguageWithID(int $languageID, array $languages) : ?Language
    {
        foreach ($languages as $language) {
            if ($language->languageID === $languageID) {
                return $language;
            }
        }
        return null;
    }
}