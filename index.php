<?php
    require_once('functions.php');
    require 'vendor/autoload.php';
    $app = new Slim\App();

    $app->get('/getProd[/{user}/{pass}/{categoria}]',function($request,$response,$args){
        $response->write(json_encode(getProd($args['user'],$args['pass'],$args['categoria']),JSON_PRETTY_PRINT));
        return $response;
    });

    $app->get('/getDetails[/{user}/{pass}/{isbn}]',function($request,$response,$args){
        $response->write(json_encode(getDetails($args['user'],$args['pass'],$args['isbn']),JSON_PRETTY_PRINT));
        return $response;
    });

    $app->post("/setProd",function($request,$response,$args){
        $reqPost = $request->getParsedBody();
        $user = $reqPost["user"];
        $pass = $reqPost["pass"];
        $json = '{"Autor":"'.$reqPost['autor'].'","Año":"'.$reqPost['anio'].'","Editorial":"'.$reqPost['editorial'].'","ISBN":"'.$reqPost['isbn'].'","Nombre":"'.$reqPost['nombre'].'","Precio":"'.$reqPost['precio'].'"}';
        $response->write(json_encode(setProd($user,$pass,$json)));
        return $response;
    });

    $app->put("/updatePass/{user}/{pass}",function($request,$response,$args){
        $reqPost = $request->getParsedBody();
        $newpass = $reqPost['newpass'];
        $response->write(json_encode(updatePassword($args['user'],$args['pass'],$newpass)));
        return $response;
    });

    $app->put("/updateProd/{user}/{pass}/{isbn}",function($request,$response,$args){
        $reqPost = $request->getParsedBody();
        $json = '{"Autor":"'.$reqPost['autor'].'","Año":"'.$reqPost['anio'].'","Editorial":"'.$reqPost['editorial'].'","ISBN":"'.$args['isbn'].'","Nombre":"'.$reqPost['nombre'].'","Precio":"'.$reqPost['precio'].'"}';
        $response->write(json_encode(updateProd($args['user'],$args['pass'],$json)));
        return $response;
    });

    $app->delete('/deleteProd[/{user}/{pass}/{isbn}]',function($request,$response,$args){
        $response->write(json_encode(deleteProd($args['user'],$args['pass'],$args['isbn']),JSON_PRETTY_PRINT));
        return $response;
    });

    $app->run();


    function getProd($user,$pass,$categoria) {
        
        $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/999.json';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        $resp = array( 
            'code' => '999', 
            'message' => $response, 
            'data' => '', 
            'status' => 'error' 
        );
        $categoria = strtolower($categoria);

        $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasusuarios/'.$user.'.json';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $usuario = curl_exec($ch);
        curl_close($ch);

        if($usuario == null)
        {
            $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/500.json';

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            $resp = array( 
                'code' => '500', 
                'message' => $response, 
                'data' => '', 
                'status' => 'error' 
            );
        }
        else{
            $usuario = json_decode($usuario);
            if($usuario == md5($pass)){
                $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasproductos/'.$categoria.'.json';
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $cat = curl_exec($ch);
                curl_close($ch);
                $cat = json_decode($cat);

                $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/200.json';

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response = json_decode($response);

                if ( $cat != null ) {
                   $resp = array( 
                    'code' => '200', 
                    'message' => $response, 
                    'data' => $cat, 
                    'status' => 'correct' 
                );
                }
                else {
                    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/300.json';

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response = json_decode($response);
                    $resp = array( 
                        'code' => '300', 
                        'message' => $response, 
                        'data' => '', 
                        'status' => 'error' 
                    );
                }
            }
            else{ 
                $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/501.json';

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);
                $resp = array( 
                    'code' => '501', 
                    'message' => $response, 
                    'data' => '', 
                    'status' => 'error' 
                );
            }
        }
        return $resp;
    }

    function getDetails($user,$pass,$isbn) {
        $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/999.json';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        $resp = array( 
            'code' => '999', 
            'message' => $response, 
            'data' => '', 
            'status' => 'error' 
        );
        $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasusuarios/'.$user.'.json';

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $usuario = curl_exec($ch);
        curl_close($ch);
       
        if($usuario == null)
        {
            $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/500.json';

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response);
            $resp = array( 
                'code' => '500', 
                'message' => $response, 
                'data' => '', 
                'status' => 'error' 
            );
        }
        else{
            $usuario = json_decode($usuario);
            if($usuario == md5($pass)){
                $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasdetalles/'.$isbn.'.json';
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $ISBN= curl_exec($ch);
                curl_close($ch);
                $ISBN = json_decode($ISBN);

                $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/201.json';

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response = json_decode($response);

                if ( $ISBN != null ) {
                    //return json_encode($detalles[$isbn], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    $resp = array( 
                        'code' => 201, 
                        'message' => $response , 
                        'data' => $ISBN, 
                        'status' => 'corret',
                        'oferta' => true 
                    );
                }
                else {
                    //error_log($categoria);
                    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/301.json';

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response = json_decode($response);
                    $resp = array( 
                        'code' => 301, 
                        'message' => $response, 
                        'data' => '', 
                        'status' => 'error',
                        'oferta' => false 
                    );
                }
            }else{
                $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasRespuestas/501.json';

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);
                $resp = array( 
                    'code' => '501', 
                    'message' => $response, 
                    'data' => '', 
                    'status' => 'error' 
                );
            }
        }
        return $resp;
    }

    function updatePassword($user,$pass,$newpass) {
        $response = get_response(999);
        $resp = array( 
            'code' => '999', 
            'message' => $response, 
            'data' => '', 
            'status' => 'error' 
        );

        $usuario = get_user($user);
        if($usuario == null)
        {
            $response = get_response(500);
            $resp = array( 
                'code' => '500', 
                'message' => $response,  
                'status' => 'error' 
            );
        }
        else{
            if($usuario == md5($pass)){

                if(preg_match('([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z])', $newpass) && strlen($newpass) > 7)  {
                    
                    $NEWPASS = update_user($user, md5($newpass));
                    $response = get_response(400);
                    $resp = array( 
                        'code' => 400, 
                        'message' => $response , 
                        'status' => 'corret',
                    );
                }
                else {
                    //error_log($categoria);
                    $response = get_response(502);
                    $resp = array( 
                        'code' => 502, 
                        'message' => $response, 
                        'status' => 'error',
                    );
                }
            }else{
                $response = get_response(501);
                $resp = array( 
                    'code' => '501', 
                    'message' => $response, 
                    'status' => 'error' 
                );
            }
        }
        return $resp;
    }

    function setProd($user,$pass,$json){
        $response = get_response(999);
        $resp = array( 
            'code' => '999', 
            'message' => $response, 
            'data' => '', 
            'status' => 'error' 
        );

        $usuario = get_user($user);
        if($usuario == null)
        {
            $response = get_response(500);
            $resp = array( 
                'code' => '500', 
                'message' => $response, 
                'data' => '', 
                'status' => 'error' 
            );
        }
        else{
            if($usuario == md5($pass)){

                if(Json_Estructure($json))
                {
                    $array = json_decode($json, true);
                    if(!json_incompleat($json))
                    {
                        if(getISBN($array['ISBN']) == null)
                        {
                            create_product($array['ISBN'],$json);
                            $response = get_response(202);
                            $resp = array( 
                            'code' => '202', 
                            'message' => $response, 
                            'data' => date("Y-m-d H:i:s"),
                            'status' => 'error' 
                            );
                        }
                        else{
                            $response = get_response(302);
                            $resp = array( 
                            'code' => '302', 
                            'message' => $response, 
                            'data' => '',
                            'status' => 'error' 
                            );
                        }
                    }
                    else{
                        $response = get_response(304);
                        $resp = array( 
                        'code' => '304', 
                        'message' => $response, 
                        'data' => '',
                        'status' => 'error' 
                        );
                    }
                }
                else{
                    $response = get_response(305);
                    $resp = array( 
                    'code' => '305', 
                    'message' => $response, 
                    'data' => '',
                    'status' => 'error' 
                    );

                }
               
            }else{
                $response = get_response(501);
                $resp = array( 
                    'code' => '501', 
                    'message' => $response, 
                    'data' => '',
                    'status' => 'error' 
                );
            }
        }
        return $resp;
    }

    function updateProd($user,$pass,$json){
        $response = get_response(999);
        $resp = array( 
            'code' => '999', 
            'message' => $response, 
            'data' => '', 
            'status' => 'error' 
        );

        $usuario = get_user($user);
        if($usuario == null)
        {
            $response = get_response(500);
            $resp = array( 
                'code' => '500', 
                'message' => $response, 
                'data' => '', 
                'status' => 'error' 
            );
        }
        else{
            if($usuario == md5($pass)){
                $array = json_decode($json, true);
                if(getISBN($array['ISBN']) == null)
                {
                    $response = get_response(303);
                    $resp = array( 
                    'code' => '303', 
                    'message' => $response, 
                    'data' => '',
                    'status' => 'error' 
                    );
                }
                else{
                    if(Json_Estructure($json))
                    {
                        if(!json_incompleat($json))
                        {
                            update_product($array['ISBN'],$json);
                            $response = get_response(203);
                            $resp = array( 
                            'code' => '203', 
                            'message' => $response, 
                            'data' => date("Y-m-d H:i:s"),
                            'status' => 'error' 
                            );
                        }
                        else{
                            $response = get_response(304);
                            $resp = array( 
                            'code' => '304', 
                            'message' => $response, 
                            'data' => '',
                            'status' => 'error' 
                            );
                        }
                    }
                    else{
                        $response = get_response(305);
                        $resp = array( 
                        'code' => '305', 
                        'message' => $response, 
                        'data' => '',
                        'status' => 'error' 
                        );

                    }
                }
               
            }else{
                $response = get_response(501);
                $resp = array( 
                    'code' => '501', 
                    'message' => $response, 
                    'data' => '',
                    'status' => 'error' 
                );
            }
        }
        return $resp;
    }

    function deleteProd($user,$pass,$isbn){
        $response = get_response(999);
        $resp = array( 
            'code' => '999', 
            'message' => $response, 
            'data' => '', 
            'status' => 'error' 
        );

        $usuario = get_user($user);
        if($usuario == null)
        {
            $response = get_response(500);
            $resp = array( 
                'code' => '500', 
                'message' => $response, 
                'data' => '', 
                'status' => 'error' 
            );
        }
        else{
            if($usuario == md5($pass)){
                if(getISBN($isbn) == null)
                {
                    $response = get_response(303);
                    $resp = array( 
                    'code' => '303', 
                    'message' => $response, 
                    'data' => '',
                    'status' => 'error' 
                    );
                }
                else{
                    delete_product($isbn);
                    $response = get_response(204);
                    $resp = array( 
                    'code' => '204', 
                    'message' => $response, 
                    'data' => date("Y-m-d H:i:s"),
                    'status' => 'error' 
                    );
                }
               
            }else{
                $response = get_response(501);
                $resp = array( 
                    'code' => '501', 
                    'message' => $response, 
                    'data' => '',
                    'status' => 'error' 
                );
            }
        }
        return $resp;
    }
?>
