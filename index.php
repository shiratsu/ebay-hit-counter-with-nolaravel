<?php

require_once('config.php');

$itemId = '';
$intHitCount = '';

/**
 * eBayのAPIを叩いて、該当のデータを返却する
 *
 * @param [type] $itemId
 * @return void
 */
function getItemPageView($itemId){
    $apiUrl = 'https://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid='.EBAY_APP_ID.'&version=967&ItemID='.$itemId.'&IncludeSelector=Details';


    // curlの処理を始める合図
    $curl = curl_init($apiUrl);

    // リクエストのオプションをセットしていく
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // メソッド指定
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // レスポンスを文字列で受け取る

    // レスポンスを変数に入れる
    $response = curl_exec($curl);

    // curlの処理を終了
    curl_close($curl);


    // $xmlObject = simplexml_load_string($response);
    // $result = json_decode( json_encode( $xmlObject ), TRUE );
    $result = json_decode( $response, TRUE );


    return $result;

}

/**
 * tokenがある場合
 */
if(!empty($_POST["_token"])){
    $itemId = htmlspecialchars($_POST["itemId"]);

    if(preg_match("/^[a-zA-Z0-9]+$/", $itemId)){
        $aryResult = getItemPageView($itemId);
        if(isset($aryResult['Item']['HitCount'])){
            $intHitCount = $aryResult['Item']['HitCount'];
        }
    }else{
        $itemId = '';
        $intHitCount = '';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h3>ebayの対象商品のページビューを取得</h3>
    <p>対象の商品のpageviewを取得します。</p>
    <form method="POST" action="/" accept-charset="UTF-8" url="/">
        <input name="_token" type="hidden" value="GfVt6aYeH85F0rYcT2uTjePlD2Lw4NGwB00ZzI9S">
        商品ID：<input class="form-control" name="itemId" type="text" value="<?php echo $itemId; ?>" >
        <br />
    <input class="btn btn-primary form-control" type="submit" value="get-HitCount">
    </form>
    <?php
    if(!empty($intHitCount) && !empty($itemId)){
    ?>    
        商品ID:<?php echo $itemId; ?>のpageviewは<?php echo $intHitCount; ?>です。
    <?php
    }
    ?>
    <br />
    </body>
</html>
