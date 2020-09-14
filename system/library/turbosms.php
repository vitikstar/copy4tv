<?php
class Turbosms {
    private $registry;
    public function __construct($registry) {
        $this->registry = $registry;
    }
    public function __get($name) {
        if ($this->registry->has($name)) {
            return $this->registry->get($name);
        } else {
            return null;
        }
    }
	public function viber($recipients=array(),$text="",$image_url="") {
        $result = "not number";
        $local_parametr = array(
            "sender"=> $this->config->get('viber_newsletter_gateway_sender_name'),
            "text"=> $text
        );
        if(!empty($image_url)){
            $local_parametr['image_url'] = $image_url;
            $local_parametr['caption'] = "В магазин";
            $local_parametr['action'] = HTTPS_CATALOG;
        }
	    if(count($recipients)>0){
            $data = [
                'recipients' => $recipients,
                "viber"=>$local_parametr
            ];
            $dataString = json_encode($data);

            $url = 'https://api.turbosms.ua/message/send.json';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic '. $this->config->get('viber_newsletter_gateway_auth_token'),
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString),
            ]);
            $result = curl_exec($ch);

            curl_close($ch);
        }
	    $result = json_decode($result);
        return $result;
	}
	public function sms($recipients,$text="") {
        $response_result = false;
	    if(strlen($recipients)>1){
	        if(is_array($recipients)){
                $recipients_string = implode(",",$recipients);
                $recipients_string = rtrim($recipients_string,",");
            }elseif (is_string($recipients)){
                $recipients_string = $recipients;
            }


// Все данные возвращаются в кодировке UTF-8
            header('Content-type: text/html; charset=utf-8');

            if(TURBO_SMS_DEBUG) echo '<pre>';
            try {

                // Подключаемся к серверу
                $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');


                // Данные авторизации
                $auth = [
                    'login' => GATEWAY_LOGIN,
                    'password' => GATEWAY_PASSWORD
                ];

                // Авторизируемся на сервере
                $result =   $client->Auth($auth);

                // Результат авторизации
                if(TURBO_SMS_DEBUG)   echo "Результат авторизации " . $result->AuthResult . PHP_EOL;

                // Получаем количество доступных кредитов
                $result = $client->GetCreditBalance();
                if(TURBO_SMS_DEBUG)   echo "Получаем количество доступных кредитов " . $result->GetCreditBalanceResult . PHP_EOL;
                // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
              //  $text = iconv('windows-1251', 'utf-8', $text);

                // Отправляем сообщение на один номер.
                // Подпись отправителя может содержать английские буквы и цифры. Максимальная длина - 11 символов.
                // Номер указывается в полном формате, включая плюс и код страны
                $sms = [
                    'sender' => GATEWAY_SENDER_SMS,
                    'destination' => $recipients_string,
                    'text' => $text
                ];
                $result = $client->SendSMS($sms);

                // Выводим результат отправки.
                if(TURBO_SMS_DEBUG)  print_r($result->SendSMSResult->ResultArray);

               // if(isset($result->SendSMSResult->ResultArray[1])){
                  //  $MessageId = $result->SendSMSResult->ResultArray[1];
                    // Запрашиваем статус конкретного сообщения по ID
                    //$sms = ['MessageId' => $MessageId];
                   // $status = $client->GetMessageStatus($sms);
                //}


            } catch (Exception $e) {
                if(TURBO_SMS_DEBUG) echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
            }
           if(TURBO_SMS_DEBUG) echo '</pre>';
        }
        return $response_result;
	}
	public function status($messages) {

$data = array(
    "messages" => $messages
);

            $dataString = json_encode($data);



            $url = 'https://api.turbosms.ua/message/status.json';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic '. $this->config->get('viber_newsletter_gateway_auth_token'),
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString),
            ]);
            $result = curl_exec($ch);

            curl_close($ch);

        return json_decode($result);
	}
}