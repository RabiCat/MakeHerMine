<?php
define('CLIENT_ID', '6262e3ec0c7369b8399c');
define('CLIENT_SECRET', '5eee18fbd40d9459fc9e0817947d4c7a2f008e7d');

if (empty($_GET['code']) && empty($_GET['access_token'])) {
    // Authrize URLの構築
    $params = array(
                    'client_id' => CLIENT_ID,
                    'scope' => 'repo',  // 必須ではない。下記説明参照。
                    );
    $authorizeUrl = 'https://github.com/login/oauth/authorize?'
                 . http_build_query($params);

    header('Location: ' . $authorizeUrl);
}
/*else if($_GET['access_token']){
  setcookie('access_token',$_GET['access_token']);
  http_redirect('home.html2');
}*/
else {
    // アクセストークン取得
    $accessTokenUrl = 'https://github.com/login/oauth/access_token';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $accessTokenUrl);
    curl_setopt($curl, CURLOPT_POST, 1);
    $params = array(
                    'client_id' => CLIENT_ID,
                    'client_secret' => CLIENT_SECRET,
                    'code' => $_GET['code'],
                    );
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $res = curl_exec($curl);
    curl_close($curl);

    parse_str($res, $output);

    setcookie('github_access_token', $output['access_token']);
    header("Location: http://rabicat.github.com/MakeHerMine/home.html?access_token=".$output['access_token'] );
    //http_redirect('home.html2');
}

