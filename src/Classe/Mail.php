<?php
namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail {

    private $api_key = '61fdac3263310dc9e8f064295ac3ca61';
    private $api_key_secret = '4590a17d2ac7c16d78ec127bb6336cc9';
    
    public function send($to_email, $to_name, $subject, $content){

        $mj=new Client($this->api_key, $this->api_key_secret, true, ['version'=>'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "danyderensy@gmail.com",
                        'Name' => "Dany"
                    ],
                    'To' => [
                        [
                            'Email' => "$to_email",
                            'Name' => "$to_name"
                        ]
                    ],
                    'TemplateID' => 3482340,
                    'TemplateLanguage' => true,
                    'Subject' => "$subject",
                    'Variables' => [
                        'content' => $content,
                        'name'=>$to_name,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }


}