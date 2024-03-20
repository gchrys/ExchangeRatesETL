<?php

namespace App\Model;

use PDO;

class DatabaseDataLoader extends DataLoader {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function loadIntoDatabase($data)
    {
        // Prepare SQL statement for insertion
        $stmt = $this->pdo->prepare("INSERT INTO currency_data (currency_date, currency_symbol, currency_rate) VALUES (:date, :symbol, :rate) ON CONFLICT (currency_symbol, currency_date) DO UPDATE SET currency_rate = EXCLUDED.currency_rate");

        // Loop through each date and its corresponding currency data
        foreach ($data as $date => $currencyData) {
            foreach ($currencyData as $currencySymbol => $currencyRate) {
                $stmt->bindValue(':date', $date);
                $stmt->bindValue(':symbol', $currencySymbol);
                $stmt->bindValue(':rate', $currencyRate);
                $stmt->execute();
            }
        }
    }

}

?>