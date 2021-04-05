<?php

function get_response($id_response)
{
    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasrespuestas/'.$id_response.'.json';
    $ch =  curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);
    // Se convierte a Object o NULL
    return json_decode($response);
}

function get_user($user) {
    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasusuarios/'.$user.'.json';

    $ch =  curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    // Se convierte a Object o NULL
    return json_decode($response);
}

function update_user($user, $new_pass) {

        $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasusuarios.json';
        $data = '{
            "'.$user.'":"'.$new_pass.'"
        }';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PATCH" );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type : text/plain'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);

        curl_close($ch);

    // Se convierte a Object o NULL
    return json_decode($response);
}

function Json_Estructure($json)
{
    $base = '{"Autor":"Autor","Año":"Año","Editorial":"Editorial","ISBN":"ISBN","Nombre":"Nombre","Precio":"00"}';
    $array = json_decode($json, true); 
    $default = array_keys(json_decode($base,true)); 

    foreach ($default as $key => $value) {
        if(array_key_exists($value,$array)){
            //echo 'Existe'.$value;
        }
        else
        {
            return false;
        }
    }

    return true;
}

function getISBN($isbn) {
    //echo 'ISBN: '. $isbn;
    if(strlen($isbn) > 0)
    {
        $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasdetalles/'.$isbn.'.json';
        $ch =  curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);
        //echo $response;
        // Se convierte a Object o NULL
        return json_decode($response);
    }
    else
    {
        return null;
    }
}

function json_incompleat($json){
    $array = json_decode($json, true); 
    foreach ($array as $key => $value) {
        if($value == null || strlen($value) == 0 )
        {
            return true;
        }
    }
    return false;
}

function create_product($isbn,$json) {
    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasdetalles/'.$isbn.'.json';

    $ch =  curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH" );  // en sustitución de curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    // Se convierte a Object o NULL
    return json_decode($response);
}

function update_product($isbn,$json)
{
    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasdetalles/'.$isbn.'.json';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PATCH" );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type : text/plain'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);

        curl_close($ch);

    // Se convierte a Object o NULL
    return json_decode($response);
}

function delete_product($isbn)
{
    $url = 'https://alumnosws-default-rtdb.firebaseio.com/estasdetalles/'.$isbn.'.json';
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PATCH" );
    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type : text/plain'));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
}

?>