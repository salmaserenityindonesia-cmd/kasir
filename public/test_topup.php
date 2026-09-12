<?php
$ch = curl_init('http://localhost/kasir/public/topup/save');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['customer_id' => 1, 'jumlah_topup' => 500]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Requested-With: XMLHttpRequest'
]);
curl_setopt($ch, CURLOPT_HEADER, true);
$res = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP CODE: " . $httpcode . "\n";
echo "RESPONSE:\n" . $res;
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}
curl_close($ch);
