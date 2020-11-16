<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageApiController extends Controller
{
    private $blockade_msg = array();

    public function __construct(Message $message, Request $request )
    {
        $this->message = $message;
        $this->request = $request;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = "../storage/app/";
        $blacklist = "https://front-test-pg.herokuapp.com/blacklist?phone=";

        if($request->file('file_message')->isValid())
        {
            $extension = $request->file_message->extension();

            $name = uniqid(date('His'));

            $name_file = "{$name}.{$extension}";

            $upload = $request->file('file_message')->storeAs('messages',$name_file);

            if(!$upload){
                return response()->json(['message' =>'Falha no upload do arquivo.'], 500);
            }

            $messages = file("{$path}{$upload}");
            $l = 0;
            $enviadas = 0;
            foreach($messages as $linha){
                $estrutura[$l] = explode(';',$linha);

                $message_id = trim($estrutura[$l][0]);
                $ddd   = trim($estrutura[$l][1]);
                $phone = trim($estrutura[$l][2]);
                $operador = trim($estrutura[$l][3]);
                $time_shipping = trim($estrutura[$l][4]);
                $message  = trim($estrutura[$l][5]);
                $operator_id = $this->message->validOperator($operador);

                // mensagens com telefone inválido deverão ser bloqueadas(DDD+NUMERO);
                //$blockade_msg[0] = $this->message->validNumberPhone($ddd, $phone);
                $this->getMessage($this->message->validNumberPhone($ddd, $phone));

                // mensagens para o estado de São Paulo deverão ser bloqueadas;
                $this->getMessage($this->message->validBlockSP($ddd));

                // mensagens com agendamento após as 19:59:59 deverão ser bloqueadas;
                $this->getMessage($this->message->validTimer($time_shipping));

                //as mensagens com mais de 140 caracteres deverão ser bloqueadas
                $this->getMessage($this->message->validLength($message));

                // Valida se usuario esta na blacklist
                // utilizando: GuzzleHttp\Client
                // $url = 'https://front-test-pg.herokuapp.com/blacklist?phone=?phone=46950816645';
                $url = $blacklist.$ddd.$phone;
                $this->getMessage($this->message->validBlackList($url));

                var_dump("======== Demonstrando a validacao pelas regras definidas =====");
                var_dump( $ddd.'---'.$phone.'---'.$time_shipping.'--'.$operador);

                foreach($this->blockade_msg as $black)
                {

                    var_dump($black['message']);
                }

                if(count($this->blockade_msg) == 0){
                    
                    $message_enviada[$enviadas] = array($message_id.';'.$operator_id);
                    $enviadas++;
                }

                $this->blockade_msg = array();
            }
        }
        var_dump('===== apenas enviadas =====');

        return response()->json($message_enviada, 200);
               
    }


    public function getMessage($messages)
    {
        $l = count($this->blockade_msg);

        foreach($messages as $msg)
        {
            
            if(isset($msg['message_id'])){

                $this->blockade_msg[$l++] = [
                    "message_id" => "{$msg['message_id']}",
                    "message" => "{$msg['message']}"
                ];
            }
        }

    }
}
