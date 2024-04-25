<?php
function getActorsBornOnDate($birthdate)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/actors/v2/get-born-today?today={$birthdate}&first=20",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
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

    if (isset($data['data']['bornToday']['edges'])) 
    {
        $actorsIds = array_map(
            function ($edge) 
            {
                return $edge['node']['id'];
            }, 
            $data['data']['bornToday']['edges']
        );

        echo json_encode(["actors" => $actorsIds]);
    } else {
        echo json_encode(["actors" => []]);
    }
}

function getActorName($actorId)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/actors/v2/get-bio?nconst={$actorId}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
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

    if (isset($data['data']['name']['nameText']['text'])) {
        echo json_encode(["name" => $data['data']['name']['nameText']['text']]);
    }else{
        echo json_encode(["name" => null]);
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'getActorsBornOnDate') {
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : date('m-d');

    getActorsBornOnDate($birthdate);
}else if(isset($_POST['action']) && $_POST['action'] == 'getActorName'){
    $actorId = isset($_POST['actorId']) ? $_POST['actorId'] : 'nm0001297';

    getActorName($actorId);
} else {
    echo json_encode(["error" => "Invalid action or missing birthdate."]);
}
?>