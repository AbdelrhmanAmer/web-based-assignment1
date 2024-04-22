<?php
function getActorsBornOnDate($birthdate)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://imdb8.p.rapidapi.com/actors/v2/get-born-today?today={$birthdate}&first=20",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: imdb8.p.rapidapi.com",
            "X-RapidAPI-Key: ac3c1a3f91mshbf3ff9b8cc6ac23p1c4561jsn7cb375d7dbbe"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return [];
    } else {
        $data = json_decode($response, true);
        if (isset($data['data']['bornToday']['edges'])) {
            return $data['data']['bornToday']['edges'];
        } else {
            return [];
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'getActorsBornOnDate') {
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : date('m-d');

    $actors = getActorsBornOnDate($birthdate);

    echo json_encode($actors); // Output JSON-encoded result to AJAX
} else {
    echo "Invalid action or missing birthdate.";
}
