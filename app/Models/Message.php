<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;



class Message extends Model
{


    protected $fillable = [
        'idmessage',
        'ddd',
        'cellphone',
        'operator',
        'time_shipping',
        'message'
    ];

    public function rules(){
        return [
            'idmessage' => 'required|string|max:36',
            'ddd' => 'required|string|max:3',
            'cellphone' => 'required|string|max:9',
            'operator' => 'required|string|max:20',
            'time_shipping' => 'required|string|max:8',
            'message' => 'required'
        ];
    }


    public function processFile()
    {
        return 'arquivo_carregado';
    }

    public function validBlockSP($ddd)
    {
        $msg[] = "";
        if(substr($ddd , 0, 1) == '1') 
        {
            $msg[0] = array("message_id" => "0",
                            "message" => "mensagens para o estado de São Paulo deverão ser bloqueadas");
        }

        return $msg;

    }

    public function validNumberPhone($ddd, $phone)
    {
        $msg[] = '';
        $l = 0;
        if(strlen($ddd) < 2)
        {
            $msg[$l] = array("message_id" => "0",
                                "message" => "DDD com 2 digitos");

        }
        if($ddd == '00')
        {
            $msg[$l++] = array("message_id" => "1",
                               "message" => "DDD com 2 digitos zerado");
        }
        
        if(strlen($phone) < 9)
        {
            $msg[$l++] = array("message_id" => "2",
                               "message" => 'número celular deve conter 9 dígitos');            
        }
        if(substr($phone , 0, 1) != '9')
        {
            $msg[$l++] = array("message_id" => "3",
                               "message" => 'numero celular deve começar com 9');            
        }

        if(substr($phone , 1, 1) == '6')
        {
            $msg[$l++] = array("message_id" => "4",
                               "message" => 'o segundo dígito deve ser > 6');            

        }

        return $msg;
    }

    public function validBlackList($url)
    {
        $msg[] = "";

        $client = new Client(); //GuzzleHttp\Client
                
        $result = $client->get($url, []);

        if($result->getBody() != '[]'){
            $resp_balcklist = json_decode($result->getBody(),true);
            foreach($resp_balcklist as $black)
            {
                if($black['active']){
                    $msg[0]= array("message_id" => "4",
                                   "message" => 'mensagens que estão na _blacklist_ deverão ser bloqueadas'); 
                } 
            }   
        }    
        return $msg;
    }

    public function validTimer($time_shipping)
    {
        $msg[] = "";
        if(strtotime($time_shipping) > strtotime('19:59:59')) 
        {
            $msg[0] = array("message_id" => "0",
                            "message" => 'mensagens com agendamento após as 19:59:59 deverão ser bloqueadas');   
        }

        return $msg;
    }

    public function validLength($message)
    {
        $msg[] = "";
        if(strlen(trim($message)) > 140) 
        {
            $msg[0] = array("message_id" => "0",
                            "message" => 'as mensagens com mais de 140 caracteres deverão ser bloqueadas');   
        }

        return $msg;
    }

    public function validOperator($operator)
    {
        $operator_id = 0;
        switch ($operator) {
            case 'VIVO':
                $operator_id = 1;
                break;
            case 'TIM':
                $operator_id = 1;
                break;
            case 'CLARO':
                $operator_id = 2;
                break;
            case 'OI':
                $operator_id = 2;
                break;
            case 'NEXTEL':
                $operator_id = 3;
                break;
            default:
                error();
                break;
            }
        return $operator_id;
    }


}
