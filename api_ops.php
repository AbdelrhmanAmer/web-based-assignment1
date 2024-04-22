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
        echo json_encode(["error" => "cURL Error: " . $err]);
        return;
    }

    $data = json_decode($response, true);

    if (isset($data['data']['bornToday']['edges'])) {
        $actorsIds = array_map(function($edge) {
            return $edge['node']['id'];
        }, $data['data']['bornToday']['edges']);

        echo json_encode(["actors" => $actorsIds]);
    } else {
        echo json_encode(["actors" => []]);
    }
}

function getActorName($actor_id)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://imdb8.p.rapidapi.com/actors/v2/get-bio?nconst={$actor_id}",
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
        return null;
    }

    $data = json_decode($response, true);

    if (isset($data['data']['name']['nameText']['text'])) {
        return $data['data']['name']['nameText']['text'];
    }

    return null;
}

if (isset($_POST['action']) && $_POST['action'] == 'getActorsBornOnDate') {
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : date('m-d');

    getActorsBornOnDate($birthdate);
} else {
    echo json_encode(["error" => "Invalid action or missing birthdate."]);
}
?>
