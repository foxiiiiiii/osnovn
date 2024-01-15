<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $api_key = '6asii6bxru7jxgsitjunu3quewkp7sfnygqm6iro';

    $list_ids = '1130';

    $fields = array();

    $tags = '';


    $double_optin = 0;
    $overwrite = 0;


    $url = "https://api.unisender.com/ru/api/subscribe?format=json&api_key=$api_key&list_ids=$list_ids&fields[email]=$email&tags=$tags&double_optin=$double_optin&overwrite=$overwrite";


    $response = file_get_contents($url);


    $result = json_decode($response, true);

    if (isset($result['result']['person_id'])) {
        echo "Спасибо! Ваш email успешно добавлен в список подписчиков.";
    } else {
        echo "Извините, произошла ошибка при добавлении вашего email в список подписчиков: " . $result['error'];
    }
} else {
    echo "Недопустимый запрос";
}

?>