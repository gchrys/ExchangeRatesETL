<?php

namespace App\Model;

class CurrencyDataTransformer extends DataTransformer {
    
    public function transformData($data) {
        
        $eurBasedRates = [];

        foreach ($data as $date => $currencyData) {
            // USD to EUR conversion rate
            $usdToEurRate = $currencyData['EUR'];
            foreach ($currencyData as $currencySymbol => $currencyRate) {
                 // Skip converting EUR to EUR itself
                if ($currencySymbol === 'EUR') {
                    $eurBasedRates[$currencySymbol]=1;
                    continue;
                }

                $eurBasedRates[$currencySymbol] = $currencyRate / $usdToEurRate;
            }
            $data[$date]=$eurBasedRates;
        }

        return $data;
    }
}

?>