<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;
use App\Models\partida_wow_mitics;
use App\Models\partida_wow;
use App\Models\jugadores_personaje;
use App\Models\jugador_equipo_wow;
use Illuminate\Support\Facades\DB;
use App\Models\jugadores_wow;
use App\Models\equipos_wow;
Use \Carbon\Carbon;

class MyticSelectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($idmytic)
    {
        $params = array(
                'grant_type' => 'client_credentials'
            );
        $tokenUri = 'https://us.battle.net/oauth/token';

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_USERPWD, 'bb023700565c406fb01a3655af82b655' . ":" . '5hJAT58WiOkXRUbZYs7iAxsZJWgFzR56' );
        curl_setopt( $ch, CURLOPT_URL, $tokenUri );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec( $ch );
        curl_close( $ch );

        $accessTokenResponse = json_decode( $response, true );

        if ( isset( $accessTokenResponse['access_token'] ) ) { // we have access token
            $status = 'ok';
            $message = 'New access token save and ready for use.';

        } else { // no access token
            $status = 'fail';
            $message = 'Something went wrong trying to get an access token.';
        }

        $accessToken =  array(
            'status' => $status,
            'message' => $message,
            'raw_response' => $accessTokenResponse
        );
        $tokkenWOW=$accessTokenResponse['access_token'];
        $expansion=499;//shadowlands

        $miticas = DB::table('partida_wow_mitics')
                        ->where("idpartida","=",$idmytic)->exists();
        $Mitic_partida="";
        if($miticas == false){

            $request = Http::GET(
                'https://us.api.blizzard.com/data/wow/journal-expansion/'.$expansion.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
            );
            $dungeons = json_decode( $request, true );
            $tarjetas='';
            $fases = count($dungeons["dungeons"]);
            $arraySorteado=array_rand($dungeons["dungeons"],$fases);
            shuffle($arraySorteado);
            $dung1=$dungeons["dungeons"];
            for($i=0;$i<3;$i++){
                $dung2 = $dung1[$arraySorteado[$i]];
                $requestIMG = Http::GET(
                    'https://us.api.blizzard.com/data/wow/media/journal-instance/'.$dung2["id"].'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                );
                $tarjetas.='
                        <div class="col-xl-4">
                            <h5>'.$dung2["name"].'</h5>
                        ';
                $wow = json_decode( $requestIMG , true );

                //informacion de la mitica
                $description = Http::GET(
                    'https://us.api.blizzard.com/data/wow/journal-instance/'.$dung2["id"].'?namespace=static-us&locale=es_mx&access_token='.$tokkenWOW
                );
                $des = json_decode( $description , true );
                foreach ($wow["assets"] as $img) {
                    $Mitic_partida =$dung2["id"];
                    $tarjetas.='
                                <a href="'.route('RMyticDetalle',['Partida'=> $idmytic ,'Mitic'=>$Mitic_partida]).'"><img src="'.$img["value"].'" class="img-mityc-redisenio"></a><br>
                                <label class="img-mityc-redisenio">'.$des["description"].'</label>
                            </div>
                            ';

                     //<a href="'.route('RMytic',$partida->id).'">
                }
                $date = Carbon::now();
                $miticDetalle = new partida_wow_mitics;
                $miticDetalle->idpartida = $idmytic;
                $miticDetalle->mitica = $dung2["id"];
                $miticDetalle->fechaEncuentro = $date->toDateString();
                $miticDetalle->horaEncuentro = "00:00";
                $miticDetalle->tiempoMiticaIdEquipo1 = "00:00";
                $miticDetalle->numeroMuertesEquipo1 = 0;
                $miticDetalle->terminado1 = "N/D";
                $miticDetalle->tiempoMiticaIdEquipo2 = "00:00";
                $miticDetalle->numeroMuertesEquipo2 = 0;
                $miticDetalle->terminado2 = "N/D";
                $miticDetalle->ganadorIdEquipo = 0;
                $miticDetalle->perdedorIdEquipo = 0;
                $miticDetalle->empate = 0;
                $miticDetalle->detalles = "";
                $miticDetalle->save();
            }
        }
        else{
            $miticas = DB::table('partida_wow_mitics')
                        ->where("idpartida","=",$idmytic)->get();
            $tarjetas='';
            foreach ($miticas as $mitica) {
                $requestIMG = Http::GET(
                    'https://us.api.blizzard.com/data/wow/media/journal-instance/'.$mitica->mitica.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                );
                $wow = json_decode( $requestIMG , true );
                //informacion de la mitica
                $description = Http::GET(
                    'https://us.api.blizzard.com/data/wow/journal-instance/'.$mitica->mitica.'?namespace=static-us&locale=es_mx&access_token='.$tokkenWOW
                );
                $des = json_decode( $description , true );

                $tarjetas.='
                        <div class="col-xl-4">
                            <h5 class="text_miticas">'.$des["name"].'</h5>
                        ';

                foreach ($wow["assets"] as $img) {
                    $Mitic_partida =$mitica->mitica;
                    $tarjetas.='
                                <a href="'.route('RMyticDetalle',['Partida'=> $idmytic ,'Mitic'=>$Mitic_partida]).'"><img src="'.$img["value"].'" class="img-mityc-redisenio"></a><br>
                                <label class="img-mityc-redisenio text_miticas">'.$des["description"].'</label>
                            </div>
                            ';
                }
            }
        }

        $partida_miticas = DB::table('partida_wow_mitics')
                             ->where("idpartida","=",$idmytic)->get();
        $puntajes='
                    <div class="row">
                    ';
        $conteoBoton=0;
        foreach ($partida_miticas as $objeto) {
            $equipo_miticas1 = DB::table('equipos_wow')
                                ->where("id","=",$objeto->ganadorIdEquipo)->first();
            $equipo_miticas2 = DB::table('equipos_wow')
                                ->where("id","=",$objeto->perdedorIdEquipo)->first();
            if($equipo_miticas1->nombreSEquipo!="N/D"){
                if($equipo_miticas2->nombreSEquipo!="N/D"){
                    $conteoBoton++;
                    $puntajes.='
                            <div class="col-xl-4 mx-auto text_miticas">
                                El Gandor del Encuentro es: '.$equipo_miticas1->nombreSEquipo.'
                            </div>
                    ';
                }
                else{
                    $puntajes.='
                            <div class="col-xl-4 mx-auto text_miticas">
                                El encuentro esta en curso
                            </div>
                    ';
                }
            }
            else{
                $puntajes.='
                            <div class="col-xl-4 mx-auto text_miticas">
                                El encuentro esta en curso
                            </div>
                    ';
            }
        }
        $puntajes.='
                    </div>
                    ';
        if($conteoBoton==3){
            $idsalan = DB::table('partida_wow')
                        ->where("id","=",$idmytic)->first();
            $puntajes.='
                    <div class="row">
                        <div class="col-xl-4"></div>
                        <div class="col-xl-4 align-self-center text-center">
                            <a href="'.route('RFixtureAgregar',$idmytic).'"><button class="btn btn-dark">Concluir Partida</button></a>
                        </div>
                        <div class="col-xl-4"></div>
                    </div>
                    ';
        }
        //dd($conteoBoton);
        return view("sala/mitic-wow-seleccion",compact('tarjetas'),compact('puntajes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($Partida,$Mitic)
    {
        $params = array(
            'grant_type' => 'client_credentials'
        );
        $tokenUri = 'https://us.battle.net/oauth/token';

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_USERPWD, 'bb023700565c406fb01a3655af82b655' . ":" . '5hJAT58WiOkXRUbZYs7iAxsZJWgFzR56' );
        curl_setopt( $ch, CURLOPT_URL, $tokenUri );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec( $ch );
        curl_close( $ch );

        $accessTokenResponse = json_decode( $response, true );

        if ( isset( $accessTokenResponse['access_token'] ) ) { // we have access token
            $status = 'ok';
            $message = 'New access token save and ready for use.';

        } else { // no access token
            $status = 'fail';
            $message = 'Something went wrong trying to get an access token.';
        }

        $accessToken =  array(
            'status' => $status,
            'message' => $message,
            'raw_response' => $accessTokenResponse
        );
        $tokkenWOW=$accessTokenResponse['access_token'];
        $expansion=499;//shadowlands


        $miticas = DB::table('jugadores_personaje')
                        ->where("idpartida","=",$Partida)->where("mitica","=",$Mitic)->exists();
        $pagina="";
        if($miticas == false){
            $request = Http::GET(
                'https://us.api.blizzard.com/data/wow/playable-class/index?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
            );
            $clases = json_decode( $request, true );
            $claseJu =  array("clases");
            foreach ($clases["classes"] as $clase) {
                $claseJu[] = $clase["name"];
            }

            //jugadores
            $partida = DB::table('partida_wow')
                            ->where("id","=",$Partida)
                            ->first();
            $sala = $partida->idsala;
            $equipo1 = $partida->idequipo1;
            $equipo2 = $partida->idequipo2;
            $Jequipo1 = DB::table('jugador_equipo_wow')
                            ->where("idsala","=",$sala)->where("idequipo","=",$equipo1)->get();
            $Jequipo2 = DB::table('jugador_equipo_wow')
                            ->where("idsala","=",$sala)->where("idequipo","=",$equipo2)->get();
            $participantes =  array("jugador");
            foreach ($Jequipo1 as $jugador1) {
                $jugadores1 = DB::table('jugadores_wow')
                            ->where("id","=",$jugador1->idjugador)->first();
                $participantes[] = $jugadores1->nickname;
            }
            foreach ($Jequipo2 as $jugador2) {
                $jugadores2 = DB::table('jugadores_wow')
                            ->where("id","=",$jugador2->idjugador)->first();
                $participantes[] = $jugadores2->nickname;
            }
            //equipos
            $nEquipo1 = DB::table('equipos_wow')
                            ->where("id","=",$partida->idequipo1)
                            ->first();
            $participantes[] = $nEquipo1->nombreSEquipo;
            $nEquipo2 = DB::table('equipos_wow')
                            ->where("id","=",$partida->idequipo2)
                            ->first();
            $participantes[] = $nEquipo2->nombreSEquipo;
            $identidficador = array('Partida'=>$Partida,'Mitica'=>$Mitic);
            $participantes[]=$identidficador;

            $pagina.='
                        <div class="container alto-content">
                            <div class="row bordes-jugadores-detalles">
                                <div class="row col-xl-12">
                                    <div class="col-xl-12">
                                        <h2>Detalles de la Partida</h2>
                                    </div>
                                </div>
                                <div class="row col-xl-12">
                                    <div class="col-xl-6">
                                        <table style="width:100%">
                                          <tr>
                                            <td>Nivel Mitica:</td>
                                            <td><input type="number" name="nivel_mitica" class="tamanio_caja_nivel_mitica" min="10" max="40"></td>
                                          </tr>
                                          <tr>
                                            <td>Fecha de Encuentro:</td>
                                            <td><input type="date" name="fecha"></td>
                                          </tr>
                                          <tr>
                                            <td>Hora de Encuentro:</td>
                                            <td><input type="time" name="hora"></td>
                                          </tr>
                                        </table>
                                    </div>
                                    <div class="col-xl-6">
                                        <label>Afijos de la semana:</label><br>';

            $afijosRequest = Http::GET('https://raider.io/api/v1/mythic-plus/affixes?region=us');
            $afijosDecode = json_decode( $afijosRequest, true );
            foreach ($afijosDecode["affix_details"] as $afijos) {
                $requestAfijo = Http::GET(
                'https://us.api.blizzard.com/data/wow/media/keystone-affix/'.$afijos["id"].'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                );
                $media_afijo = json_decode( $requestAfijo, true );
                $imgAfijoRequest=$media_afijo["assets"];
                $imgAfijoREQ=$imgAfijoRequest[0];
                $imgAfijo=$imgAfijoREQ["value"];
                $pagina.='
                    <img src="'.$imgAfijo.'" class="img_afijo">
                ';
            }
            $pagina.='
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 bordes-jugadores-detalles">
                                    <h3>Equipo: '. $participantes[11] .'</h3>
                                    <input type="hidden" name="equipoNAME1" value="'. $participantes[11] .'">
                                    <div class="bordes-jugadores">
                                        <label>Jugador 1: '. $participantes[1] .'</label><br>
                                        <input type="hidden" name="jugadorNAME1" value="'. $participantes[1] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador1"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion1" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-1" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 2: '. $participantes[2] .'</label><br>
                                        <input type="hidden" name="jugadorNAME2" value="'. $participantes[2] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador2"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion2" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-2" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 3: '. $participantes[3] .'</label><br>
                                        <input type="hidden" name="jugadorNAME3" value="'. $participantes[3] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador3"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion3" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-3" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 4: '. $participantes[4] .'</label><br>
                                        <input type="hidden" name="jugadorNAME4" value="'. $participantes[4] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador4"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion4" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-4" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 5: '. $participantes[5] .'</label><br>
                                        <input type="hidden" name="jugadorNAME5" value="'. $participantes[5] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador5"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion5" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-5" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                </div>
                                <div class="col-xl-6 bordes-jugadores-detalles">
                                    <h3>Equipo: '. $participantes[12] .'</h3>
                                    <input type="hidden" name="equipoNAME2" value="'. $participantes[12] .'">
                                    <div class="bordes-jugadores">
                                        <label>Jugador 1: '. $participantes[6] .'</label><br>
                                        <input type="hidden" name="jugadorNAME6" value="'. $participantes[6] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador6"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion6" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-6" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 2: '. $participantes[7] .'</label><br>
                                        <input type="hidden" name="jugadorNAME7" value="'. $participantes[7] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador7"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion7" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-7" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 3: '. $participantes[8] .'</label><br>
                                        <input type="hidden" name="jugadorNAME8" value="'. $participantes[8] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador8"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion8" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-8" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 4: '. $participantes[9] .'</label><br>
                                        <input type="hidden" name="jugadorNAME9" value="'. $participantes[9] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador9"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion9" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-9" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 5: '. $participantes[10] .'</label><br>
                                        <input type="hidden" name="jugadorNAME10" value="'. $participantes[10] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador10"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion10" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>
                                            <option name="option1">Tanke</option>
                                            <option name="option2">DPS</option>
                                            <option name="Option3">Heler</option>
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-10" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                            }
                                        $pagina.='
                                        </select><br>

                                    </div>
                                </div>
                            </div>
                            <div class="row bordes-jugadores-detalles">
                                <div class="col-xl-12">
                                    <table style="width:100%">
                                      <tr>
                                        <td>Detalles de Partida:</td>
                                        <td>Tiempo</td>
                                        <td>Terminado</td>
                                        <td>Muertes</td>
                                      </tr>
                                      <tr>
                                        <td>'. $participantes[11] .'</td>
                                        <td><input type="time" name="timedTerminado1" class="tamanio_caja_numero"></td>
                                        <td><select selected="true" name="terminado1" size="number_of_options">
                                            <option name="default1" selected="true">Ninguno</option>
                                            <option name="option1">SI</option>
                                            <option name="option2">NO</option>
                                        </select></td>
                                        <td><input type="number" name="muertes1" class="tamanio_caja_numero"></td>
                                      </tr>
                                      <tr>
                                        <td>'. $participantes[12] .'</td>
                                        <td><input type="time" name="timedTerminado2" class="tamanio_caja_numero"></td>
                                        <td><select selected="true" name="terminado2" size="number_of_options">
                                            <option name="default1" selected="true">Ninguno</option>
                                            <option name="option1">SI</option>
                                            <option name="option2">NO</option>
                                        </select></td>
                                        <td><input type="number" name="muertes2" class="tamanio_caja_numero"></td>
                                      </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row bordes-jugadores-detalles">
                                <div class="col-xl-12">
                                    <label>Resumen de la partida:</label><br>
                                    <textarea name="detalles" rows="4" cols="50" class="textarea-design"></textarea>
                                </div>
                            </div><br>
                            <button class="btn btn-danger" type="submit">Guardar Detalles de Partida</button><br>
                        </div>
                    ';
        }
        else{
            $personajes=array();
            $request = Http::GET(
                'https://us.api.blizzard.com/data/wow/playable-class/index?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
            );
            $clases = json_decode( $request, true );
            $claseJu =  array("clases");
            foreach ($clases["classes"] as $clase) {
                $claseJu[] = $clase["name"];
            }

            //jugadores
            $partida = DB::table('partida_wow')
                            ->where("id","=",$Partida)
                            ->first();
            $sala = $partida->idsala;
            $equipo1 = $partida->idequipo1;
            $equipo2 = $partida->idequipo2;
            $Jequipo1 = DB::table('jugador_equipo_wow')
                            ->where("idsala","=",$sala)->where("idequipo","=",$equipo1)->get();
            $Jequipo2 = DB::table('jugador_equipo_wow')
                            ->where("idsala","=",$sala)->where("idequipo","=",$equipo2)->get();
            $participantes =  array("jugador");
            foreach ($Jequipo1 as $jugador1) {
                $jugadores1 = DB::table('jugadores_wow')
                            ->where("id","=",$jugador1->idjugador)->first();
                $participantes[] = $jugadores1->nickname;
                $jugadol1 = DB::table('jugadores_personaje')
                            ->where("idpartida","=",$Partida)->where("idjugador","=",$jugador1->idjugador)->where("mitica","=",$Mitic)->get();
                //dd($jugadol1);
                $participantes[] = $jugadol1[0]->especializacion;
                $participantes[] = $jugadol1[0]->clase;
                $personajes[] = $jugadol1[0]->nombre;
            }
            foreach ($Jequipo2 as $jugador2) {
                $jugadores2 = DB::table('jugadores_wow')
                            ->where("id","=",$jugador2->idjugador)->first();
                $participantes[] = $jugadores2->nickname;
                $jugadol2 = DB::table('jugadores_personaje')
                            ->where("idpartida","=",$Partida)->where("idjugador","=",$jugador2->idjugador)->where("mitica","=",$Mitic)->get();
                $participantes[] = $jugadol2[0]->especializacion;
                $participantes[] = $jugadol2[0]->clase;
                $personajes[] = $jugadol2[0]->nombre;
            }
            //equipos
            $nEquipo1 = DB::table('equipos_wow')
                            ->where("id","=",$partida->idequipo1)
                            ->first();
            $participantes[] = $nEquipo1->nombreSEquipo;
            $nEquipo2 = DB::table('equipos_wow')
                            ->where("id","=",$partida->idequipo2)
                            ->first();
            $participantes[] = $nEquipo2->nombreSEquipo;
            $identidficador = array('Partida'=>$Partida,'Mitica'=>$Mitic);
            $participantes[]=$identidficador;

            $miticActual = DB::table('partida_wow_mitics')
                                ->where("idpartida","=",$Partida)->where("mitica","=",$Mitic)->first();
            $participantes[]= $miticActual->empate;
            $participantes[]= $miticActual->fechaEncuentro;
            $participantes[]= $miticActual->horaEncuentro;
            $participantes[]= $miticActual->tiempoMiticaIdEquipo1;
            $participantes[]= $miticActual->numeroMuertesEquipo1;
            $participantes[]= $miticActual->terminado1;
            $participantes[]= $miticActual->tiempoMiticaIdEquipo2;
            $participantes[]= $miticActual->numeroMuertesEquipo2;
            $participantes[]= $miticActual->terminado2;
            $participantes[]= $miticActual->ganadorIdEquipo;
            $participantes[]= $miticActual->perdedorIdEquipo;
            $participantes[]= $miticActual->detalles;
            $pagina.='
                        <div class="container alto-content">
                            <div class="row bordes-jugadores-detalles">
                                <div class="row col-xl-12">
                                    <div class="col-xl-12">
                                        <h2>Detalles de la Partida</h2>
                                    </div>
                                </div>
                                <div class="row col-xl-12">
                                    <div class="col-xl-6">
                                        <table style="width:100%">
                                          <tr>
                                            <td>Nivel Mitica:</td>
                                            <td><input type="number" name="nivel_mitica" class="tamanio_caja_nivel_mitica" min="10" max="40" value="'.$participantes[34].'"></td>
                                          </tr>
                                          <tr>
                                            <td>Fecha de Encuentro:</td>
                                            <td><input type="date" name="fecha" value="'.$participantes[35].'"></td>
                                          </tr>
                                          <tr>
                                            <td>Hora de Encuentro:</td>
                                            <td><input type="time" name="hora" value="'.$participantes[36].'"></td>
                                          </tr>
                                        </table>
                                    </div>
                                    <div class="col-xl-6">
                                        <label>Afijos de la semana:</label><br>';

            $afijosRequest = Http::GET('https://raider.io/api/v1/mythic-plus/affixes?region=us');
            $afijosDecode = json_decode( $afijosRequest, true );
            foreach ($afijosDecode["affix_details"] as $afijos) {
                $requestAfijo = Http::GET(
                'https://us.api.blizzard.com/data/wow/media/keystone-affix/'.$afijos["id"].'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                );
                $media_afijo = json_decode( $requestAfijo, true );
                $imgAfijoRequest=$media_afijo["assets"];
                $imgAfijoREQ=$imgAfijoRequest[0];
                $imgAfijo=$imgAfijoREQ["value"];
                $pagina.='
                    <img src="'.$imgAfijo.'" class="img_afijo">
                ';
            }
            $pagina.='
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 bordes-jugadores-detalles">
                                    <h3>Equipo: '. $participantes[31] .'</h3>
                                    <input type="hidden" name="equipoNAME1" value="'. $participantes[31] .'">
                                    <div class="bordes-jugadores">
                                        <label>Jugador 1: '. $participantes[1] .'</label><br>
                                        <input type="hidden" name="jugadorNAME1" value="'. $participantes[1] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador1" value="'.$personajes[0].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion1" size="number_of_options">
                                            <option name="default1" disabled="disabled">Ninguno</option>';
                                            if($participantes[2]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[2]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[2]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-1" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[3]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }

                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 2: '. $participantes[4] .'</label><br>
                                        <input type="hidden" name="jugadorNAME2" value="'. $participantes[4] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador2" value="'.$personajes[1].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion2" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[5]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[5]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[5]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-2" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[6]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 3: '. $participantes[7] .'</label><br>
                                        <input type="hidden" name="jugadorNAME3" value="'. $participantes[7] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador3" value="'.$personajes[2].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion3" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[8]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[8]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[8]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-3" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[9]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 4: '. $participantes[10] .'</label><br>
                                        <input type="hidden" name="jugadorNAME4" value="'. $participantes[10] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador4" value="'.$personajes[3].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion4" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[11]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[11]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[11]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-4" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[12]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 5: '. $participantes[13] .'</label><br>
                                        <input type="hidden" name="jugadorNAME5" value="'. $participantes[13] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador5" value="'.$personajes[4].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion5" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[14]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[14]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[14]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-5" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[15]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                </div>
                                <div class="col-xl-6 bordes-jugadores-detalles">
                                    <h3>Equipo: '. $participantes[32] .'</h3>
                                    <input type="hidden" name="equipoNAME2" value="'. $participantes[32] .'">
                                    <div class="bordes-jugadores">
                                        <label>Jugador 1: '. $participantes[16] .'</label><br>
                                        <input type="hidden" name="jugadorNAME6" value="'. $participantes[16] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador6" value="'.$personajes[5].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion6" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[17]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[17]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[17]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-6" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[18]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 2: '. $participantes[19] .'</label><br>
                                        <input type="hidden" name="jugadorNAME7" value="'. $participantes[19] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador7" value="'.$personajes[6].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion7" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[20]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[20]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[20]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-7" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[21]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 3: '. $participantes[22] .'</label><br>
                                        <input type="hidden" name="jugadorNAME8" value="'. $participantes[22] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador8" value="'.$personajes[7].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion8" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[23]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[23]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[23]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-8" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[24]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                }
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 4: '. $participantes[25] .'</label><br>
                                        <input type="hidden" name="jugadorNAME9" value="'. $participantes[25] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador9" value="'.$personajes[8].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion9" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[26]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[26]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[26]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-9" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[27]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                };
                                            }
                                        $pagina.='
                                        </select><br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador 5: '. $participantes[28] .'</label><br>
                                        <input type="hidden" name="jugadorNAME10" value="'. $participantes[28] .'">
                                        <label>Personaje de la partida: </label>
                                        <input type="text" placeholder="Nombre Personaje" name="jugador10" value="'.$personajes[9].'"><br>
                                        <label>Especialización: </label>
                                        <select name="Especializacion10" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            if($participantes[29]=="Tanke"){
                                                $pagina.='
                                                <option name="option1" selected="true">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            if($participantes[29]=="Heler"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3" selected="true">Heler</option>';
                                            }
                                            if($participantes[29]=="DPS"){
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2" selected="true">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">Tanke</option>
                                                <option name="option2">DPS</option>
                                                <option name="Option3">Heler</option>';
                                            }

                                        $pagina.='
                                        </select><br>
                                        <label>Clase: </label>
                                        <select name="clases-10" size="number_of_options">
                                            <option name="default1" selected="true" disabled="disabled">Ninguno</option>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[30]){
                                                    $pagina.='<option name="option'.$i.'" selected="true">'.$claseJu[$i].'</option>';
                                                }
                                                else{
                                                    $pagina.='<option name="option'.$i.'">'.$claseJu[$i].'</option>';
                                                };
                                            }
                                        $pagina.='
                                        </select><br>

                                    </div>
                                </div>
                            </div>
                            <div class="row bordes-jugadores-detalles">
                                <div class="col-xl-12">
                                    <table style="width:100%">
                                      <tr>
                                        <td>Detalles de Partida:</td>
                                        <td>Tiempo</td>
                                        <td>Terminado</td>
                                        <td>Muertes</td>
                                      </tr>
                                      <tr>
                                        <td>'. $participantes[31] .'</td>
                                        <td><input type="time" name="timedTerminado1" class="tamanio_caja_numero" value="'. $participantes[37] .'"></td>
                                        <td><select selected="true" name="terminado1" size="number_of_options">
                                            <option name="default1" selected="true">Ninguno</option>';
                                            if($participantes[39]=="SI"){
                                                $pagina.='
                                                <option name="option1" selected="true">SI</option>
                                                <option name="option2">NO</option>';
                                            }
                                            if($participantes[39]=="NO"){
                                                $pagina.='
                                                <option name="option1">SI</option>
                                                <option name="option2" selected="true">NO</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">SI</option>
                                                <option name="option2">NO</option>';
                                            }

                                        $pagina.='
                                        </select></td>
                                        <td><input type="number" name="muertes1" class="tamanio_caja_numero" value="'. $participantes[38] .'"></td>
                                      </tr>
                                      <tr>
                                        <td>'. $participantes[32] .'</td>
                                        <td><input type="time" name="timedTerminado2" class="tamanio_caja_numero" value="'. $participantes[40] .'"></td>
                                        <td><select selected="true" name="terminado2" size="number_of_options">
                                            <option name="default1" selected="true">Ninguno</option>';
                                            if($participantes[42]=="SI"){
                                                $pagina.='
                                                <option name="option1" selected="true">SI</option>
                                                <option name="option2">NO</option>';
                                            }
                                            if($participantes[42]=="NO"){
                                                $pagina.='
                                                <option name="option1">SI</option>
                                                <option name="option2" selected="true">NO</option>';
                                            }
                                            else{
                                                $pagina.='
                                                <option name="option1">SI</option>
                                                <option name="option2">NO</option>';
                                            }

                                        $pagina.='
                                        </select></td>
                                        <td><input type="number" name="muertes2" class="tamanio_caja_numero" value="'. $participantes[41] .'"></td>
                                      </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row bordes-jugadores-detalles">
                                <div class="col-xl-12">
                                    <label>Resumen de la partida:</label><br>
                                    <textarea name="detalles" rows="4" cols="50" class="textarea-design">'. $participantes[45] .'</textarea>
                                </div>
                            </div><br>
                            <button class="btn btn-danger" type="submit">Guardar Detalles de Partida</button><br>
                        </div>
                    ';
        }
        return view("sala/mitic-detalles",compact("pagina"),compact("participantes"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $var=$request->query;
        $var2="";
        $partidaMitic = array();
        foreach ($var as $key) {
            $partidaMitic[]=$key;
        }

                $arrayName = array();
        for ($i=1; $i <= 10 ; $i++) {
            $personajesClases = new jugadores_personaje;
            $jugadoraso = DB::table('jugadores_wow')
                        ->where("nickname","=",request('jugadorNAME'.$i))->first();
            $personajeExistente = DB::table('jugadores_personaje')
                            ->where("idjugador","=",$jugadoraso->id)->where("mitica","=",$partidaMitic[1])->exists();
            $personajeRegistro = DB::table('jugadores_personaje')
                            ->where("idjugador","=",$jugadoraso->id)->where("mitica","=",$partidaMitic[1])->first();
            if($personajeExistente == true){
                $personajesClases->idpartida= $partidaMitic[0];
                $personajesClases->idjugador= $jugadoraso->id;
                $personajesClases->nombre=request('jugador'.$i);
                $personajesClases->mitica= $partidaMitic[1];
                $personajesClases->especializacion=request('Especializacion'.$i);
                $personajesClases->clase=request('clases-'.$i);

                $arrayName[]=1;

                DB::table('jugadores_personaje')->where("idjugador","=",$jugadoraso->id)->where("mitica","=",$partidaMitic[1])
                        ->update(['idpartida' => $personajesClases->idpartida,'idjugador' => $personajesClases->idjugador
                        ,'nombre' => $personajesClases->nombre,'mitica' => $personajesClases->mitica
                        ,'especializacion' => $personajesClases->especializacion,'clase' => $personajesClases->clase]);
            }
            else{
                $personajesClases->idpartida= $partidaMitic[0];
                $jugadoraso = DB::table('jugadores_wow')
                                    ->where("nickname","=",request('jugadorNAME'.$i))->first();
                $personajesClases->idjugador= $jugadoraso->id;
                $personajesClases->nombre=request('jugador'.$i);
                $personajesClases->mitica= $partidaMitic[1];
                $personajesClases->especializacion=request('Especializacion'.$i);
                $personajesClases->clase=request('clases-'.$i);
                $personajesClases->save();
                $arrayName[]=2;
            }
                $arrayName[]=$personajesClases;
        }

        //borrar desde aca
        $actualizacionMitic = DB::table('partida_wow_mitics')
                                ->where("idpartida","=",$partidaMitic[0])->where("mitica","=",$partidaMitic[1])->first();
        $actualizacionMitic->fechaEncuentro=request('fecha');
        $actualizacionMitic->horaEncuentro=request('hora');
        $actualizacionMitic->tiempoMiticaIdEquipo1=request('timedTerminado1');
        $actualizacionMitic->numeroMuertesEquipo1=request('muertes1');
        $actualizacionMitic->terminado1=request('terminado1');
        $actualizacionMitic->tiempoMiticaIdEquipo2=request('timedTerminado2');
        $actualizacionMitic->numeroMuertesEquipo2=request('muertes2');
        $actualizacionMitic->terminado2=request('terminado2');
        $actualizacionMitic->empate=request('nivel_mitica');

        $a=request('timedTerminado1');
        $b=request('timedTerminado2');
        $equipol = DB::table('equipos_wow')
                    ->where("nombreSEquipo","=",request('equipoNAME1'))->first();
        $equipo2 = DB::table('equipos_wow')
                    ->where("nombreSEquipo","=",request('equipoNAME2'))->first();
        if($a>$b){
            $actualizacionMitic->ganadorIdEquipo=$equipo2->id;
            $actualizacionMitic->perdedorIdEquipo=$equipol->id;
        }
        else{
            if($b>$a){
                $actualizacionMitic->ganadorIdEquipo=$equipol->id;
                $actualizacionMitic->perdedorIdEquipo=$equipo2->id;
            }
        }
        $actualizacionMitic->detalles=request('detalles');

        DB::table('partida_wow_mitics')->where("idpartida","=",$partidaMitic[0])->where("mitica","=",$partidaMitic[1])
            ->update(['fechaEncuentro' => $actualizacionMitic->fechaEncuentro,'horaEncuentro' => $actualizacionMitic->horaEncuentro
            ,'tiempoMiticaIdEquipo1' => $actualizacionMitic->tiempoMiticaIdEquipo1,'numeroMuertesEquipo1' => $actualizacionMitic->numeroMuertesEquipo1
            ,'terminado1'=>$actualizacionMitic->terminado1,'tiempoMiticaIdEquipo2'=>$actualizacionMitic->tiempoMiticaIdEquipo2
            ,'numeroMuertesEquipo2'=>$actualizacionMitic->numeroMuertesEquipo2,'terminado2'=>$actualizacionMitic->terminado2
            ,'ganadorIdEquipo'=>$actualizacionMitic->ganadorIdEquipo,'perdedorIdEquipo'=>$actualizacionMitic->perdedorIdEquipo
            ,'detalles'=>$actualizacionMitic->detalles,'empate'=>$actualizacionMitic->empate]);
        return redirect()->route('RMytic',$partidaMitic[0]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
