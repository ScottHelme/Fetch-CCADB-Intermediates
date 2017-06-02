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
    if(strpos($item, '-----BEGIN CERTIFICATE-----') !== false)
    {
        // Snip the substring of PEM data out.
        $start = strpos($item, '-----BEGIN CERTIFICATE-----');
        $end = strpos($item, '-----END CERTIFICATE-----') + 25;
        $certs[] = substr($item, $start, $end - $start);
    }
}

// Create a handle to the output file.
$fh = fopen('intermediate-bundle.crt', 'w');
// Step through and output each intermediate.
foreach($certs as $cert)
{
    fwrite($fh, $cert . "\r\n\r\n");
}
// Close the file handle now we're done.
fclose($fh);

?>