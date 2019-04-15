
<?php

ini_set('error_reporting', 1);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

include "kernel.php";
require_once 'login_rait.php';

        $zach = $_GET['zach'];
        $group = $_GET['group'];
		$semestr=$_GET['semestr'];
        $database = new database($group,$zach,$semestr);

            if(!isset($zach)&&!isset($group)){
                $response['status']= 'Bad request';
                $response['error']= 1;
                $response['detail_error']= 'A zach or group does not exist!';
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }

            if($zach){
                $response = $database->getDataZach();
                    if($response['zach'] == false){
                    $response['status']= 'Bad request';
                    $response['error']= 2;
                    $response['detail_error']= 'No isset your zach!';
                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                    }

                $response['status'] = 'OK';
                $response['zach'] = $zach;

                if($_GET['mobile']=="1"){
                    $json = json_decode($response['data'],true);
                if($json["position"]<20&&$json["position"]>10){
        $title_text = "<br>У тебя есть к чему стремиться, ты на ".$json["position"]." месте $smile";

    }elseif($json["position"]<=10&&$json["position"]>5){
        $title_text = "<br>Так держать, ты на ".$json["position"]." месте $smile";

    }elseif($json["position"]<=5){
        $title_text = "<br>$good_fr, ты на ".$json["position"]." месте $smile";

    }else{
        $title_text = "<br>$negod_fr ты ниже 20 места $plohSmile";
    }
	

    $sob_text="Ваша группа: ".$json["group"]."$title_text<br>";
    $count_predmet = count($json["predmet"]);

        for($i=0;$i<$count_predmet;$i++){

        $sob_text="$sob_text<br>".$json["predmet"][$i]." ".ex($json["control"][$i]).": ".$json["ball"][$i]." бал.";
        }
            echo( "".$sob_text);

                }else{
                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                }
            }


            if($group){
                $response = $database->getDataGroup();
                    if($response['group'] == false){
                    $response['status']= 'Bad request';
                    $response['error']= 3;
                    $response['detail_error']= 'No isset your group!';
                    die(json_encode($response,JSON_UNESCAPED_UNICODE));
                    }

                $response['status'] = 'OK';
                echo json_encode($response,JSON_UNESCAPED_UNICODE);
            }


?>
