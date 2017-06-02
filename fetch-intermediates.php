<?php

// An array to hold the intermediate certificates.
$certs = array();
// The CSV file containing the intermediates.
$csv = file_get_contents('https://ccadb-public.secure.force.com/mozilla/PublicAllIntermediateCertsWithPEMCSV');
// Parse the CSV.
$data = str_getcsv($csv);

// Step through each item in the array.
foreach($data as $item)
{
    // If this item contains a PEM encoded cert.
    $start = strpos($item, '-----BEGIN CERTIFICATE-----');

    if($start !== false)
    {
        // Snip the substring of PEM data out.
        $end = strpos($item, '-----END CERTIFICATE-----') + 25;
        $certs[] = substr($item, $start, $end - $start);
    }
}

$text = implode("\r\n\r\n", $certs)."\r\n\r\n";

// Write to the output file.

file_put_contents('intermediate-bundle.crt', $text);

?>