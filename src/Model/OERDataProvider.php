<?php

namespace App\Model;
use Exception;
use DateTimeImmutable;

class OERDataProvider extends DataProvider{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function fetchData($startDate = null, $endDate = null)
    {
        //check if the command is called without parameters
        if ($startDate === null) {
            // If no start date is provided, default to today's date
            $startDate = (new DateTimeImmutable())->format('Y-m-d');
        }
        // If no end date is provided, default to start date
        if ($endDate === null) {
            $endDate = $startDate;
        }

        if ($startDate > $endDate) {
            throw new Exception("End date cannot be before start date.");
        }

        $currentDate = new DateTimeImmutable($startDate);
        $endDateObj = new DateTimeImmutable($endDate);

        while ($currentDate <= $endDateObj) {
            $formattedDate = $currentDate->format('Y-m-d');
            // Fetch data for each day and append it to the result
            $data[$formattedDate] = $this->fetchDataForDate($formattedDate);
            $currentDate = $currentDate->modify('+1 day');
        }

        return $data;
    }

    protected function fetchDataForDate($date)
    {


        $url = "https://openexchangerates.org/api/historical/{$date}.json?app_id={$this->apiKey}";
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //must change in production
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if ($response === false) {
            // Handle cURL error
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: $error");
        }

        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL session
        curl_close($ch);

        if ($httpCode !== 200) {
            // Handle non-200 HTTP status code
            $data = json_decode($response, true);
            $message = isset($data['message']) ? $data['message'] : 'No error message available';
            $description = isset($data['description']) ? $data['description'] : 'No description available';
            throw new Exception("HTTP request failed with status code $httpCode: $message - $description");
        }

        // Decode JSON response
        $data = json_decode($response, true);

        if ($data === null) {
            // Handle JSON decoding error
            throw new Exception("Failed to decode JSON response");
        }

        return $data['rates'];
    }
}

?>