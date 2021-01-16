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

class VistaUsuario extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $partidas = DB::table('partida_wow')
                ->where("idsala","=",$id)
                ->get();
        $fixture ='';
        $faseP2=0;
        $count=1;

        $variable = array();
        $countG=0;
        foreach($partidas as $partida){
            $faseP1=$partida->fase;
            $var=[];
            if($faseP1!=$faseP2){
                if($faseP2==0){
                    $fixture.='
                        <ul class="round round-'.$count.'">
                            <li class="spacer">&nbsp;</li>
                        ';
                }
                else{
                    $fixture.='
                        </ul>
                        <ul class="round round-'.$count.'">
                            <li class="spacer">&nbsp;</li>
                        ';
                }
                $count=$count+1;
            }
            $equipo1 = DB::table('equipos_wow')
                ->where("id","=",$partida->idequipo1)
                ->first();
            $equipo2 = DB::table('equipos_wow')
                ->where("id","=",$partida->idequipo2)
                ->first();

                //mostrar puntajes
                $puntajesText = DB::table('partida_wow_mitics')
                        ->where("idpartida","=",$partida->id)->where("ganadorIdEquipo","!=",0)->where("perdedorIdEquipo","!=",0)->get();
                foreach ($puntajesText as $pun) {
                    if($pun->ganadorIdEquipo!=0 && $pun->perdedorIdEquipo!=0){
                        $var[]=$pun->ganadorIdEquipo;
                    }
                }
                if(count($var)==3){
                    $variable[]= $var;
                    $ganador=0;
                    $perdedor=0;
                    $countG=0;
                    if($var[0]==$var[1]){
                        $ganador=$var[0];
                        $perdedor=$var[2];
                        $countG=2;
                    }elseif ($var[0]==$var[2]) {
                        $ganador=$var[0];
                        $perdedor=$var[1];
                        $countG=2;
                    }elseif ($var[1]==$var[2]) {
                        $ganador=$var[1];
                        $perdedor=$var[0];
                        $countG=2;
                    }
                    if($var[1]==$var[2] && $var[0]==$var[2]){
                        $countG=3;
                    }
                    $variable[]=$countG;
                    $equipo1P = DB::table('equipos_wow')
                            ->where("id","=",$ganador)
                            ->first();
                    $equipo2P = DB::table('equipos_wow')
                            ->where("id","=",$perdedor)
                            ->first();

                    //crear puntajes
                    if($equipo1->id==$ganador && $countG==2){
                        $fixture.='
                            <li class="game game-top">'.$equipo1->nombreSEquipo.'<span>2</span></li>';
                        if($equipo1->nombreSEquipo=="N/D" || $equipo2->nombreSEquipo=="N/D"){
                            $fixture.='
                                <li class="game game-spacer">&nbsp;</li>';
                        }
                        else{
                            $fixture.='
                                <a href="'.route('RMyticVista',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

                        }
                        $fixture.='
                            <li class="game game-bottom ">'.$equipo2->nombreSEquipo.'<span>1</span></li>

                            <li class="spacer">&nbsp;</li>
                            ';
                    }
                    if($equipo2->id==$ganador && $countG==2){
                        $fixture.='
                            <li class="game game-top">'.$equipo1->nombreSEquipo.'<span>1</span></li>';
                        if($equipo1->nombreSEquipo=="N/D" || $equipo2->nombreSEquipo=="N/D"){
                            $fixture.='
                                <li class="game game-spacer">&nbsp;</li>';
                        }
                        else{
                            $fixture.='
                                <a href="'.route('RMyticVista',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

                        }
                        $fixture.='
                            <li class="game game-bottom ">'.$equipo2->nombreSEquipo.'<span>2</span></li>

                            <li class="spacer">&nbsp;</li>
                            ';
                    }
                    if($equipo1->id==$ganador && $countG==3){
                        $fixture.='
                            <li class="game game-top">'.$equipo1->nombreSEquipo.'<span>3</span></li>';
                        if($equipo1->nombreSEquipo=="N/D" || $equipo2->nombreSEquipo=="N/D"){
                            $fixture.='
                                <li class="game game-spacer">&nbsp;</li>';
                        }
                        else{
                            $fixture.='
                                <a href="'.route('RMyticVista',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

                        }
                        $fixture.='
                            <li class="game game-bottom ">'.$equipo2->nombreSEquipo.'<span>0</span></li>

                            <li class="spacer">&nbsp;</li>
                            ';
                    }
                    if($equipo2->id==$ganador && $countG==3){
                        $fixture.='
                            <li class="game game-top">'.$equipo1->nombreSEquipo.'<span>0</span></li>';
                        if($equipo1->nombreSEquipo=="N/D" || $equipo2->nombreSEquipo=="N/D"){
                            $fixture.='
                                <li class="game game-spacer">&nbsp;</li>';
                        }
                        else{
                            $fixture.='
                                <a href="'.route('RMyticVista',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

                        }
                        $fixture.='
                            <li class="game game-bottom ">'.$equipo2->nombreSEquipo.'<span>3</span></li>

                            <li class="spacer">&nbsp;</li>
                            ';
                    }
                }
                else{
                         $fixture.='
                            <li class="game game-top">'.$equipo1->nombreSEquipo.'<span>0</span></li>';
                        if($equipo1->nombreSEquipo=="N/D" || $equipo2->nombreSEquipo=="N/D"){
                            $fixture.='
                                <li class="game game-spacer">&nbsp;</li>';
                        }
                        else{
                            $fixture.='
                                <a href="'.route('RMyticVista',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

                        }
                        $fixture.='
                            <li class="game game-bottom ">'.$equipo2->nombreSEquipo.'<span>0</span></li>

                            <li class="spacer">&nbsp;</li>
                            ';
                    }



            $faseP2=$faseP1;
        }

        $fixture.='
            </ul>
            ';
        if($faseP2!=1){
            for($i=($faseP2+1)/2;$i>0;$i--){
                $fixture.='
                    <ul class="round round-'.$count.'">
                    ';
                for($j=0;$j<$i-1;$j++){
                    $fixture.='
                        <li class="spacer">&nbsp;</li>
                        <li class="game game-top"><span></span></li>
                        <li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li>
                        <li class="game game-bottom "><span></span></li>
                        <li class="spacer">&nbsp;</li>
                        ';
                }
                $fixture.='
                    </ul>
                    ';
                $count=$count+1;
            }
        }

        $extras = array();
        $partidaFinal = DB::table('partida_wow')
                        ->where("idsala","=",$id)->where("fase","=",1)->first();
        $comprobar = DB::table('partida_wow_mitics')
                        ->where("idpartida","=",$partidaFinal->id)->exists();
        if($comprobar!=false){
            $miticas = DB::table('partida_wow_mitics')
                        ->where("idpartida","=",$partidaFinal->id)->where("ganadorIdEquipo","!=",0)->where("perdedorIdEquipo","!=",0)->get();

            foreach ($miticas as $encuentro) {
                if($encuentro->ganadorIdEquipo!=0 && $encuentro->perdedorIdEquipo!=0){
                    $Wins[]=$encuentro->ganadorIdEquipo;
                }
            }
            $ganador=0;
            $perdedor=0;

            if($Wins[0]==$Wins[1]){
                $ganador=$Wins[0];
                $perdedor=$Wins[2];
            }elseif ($Wins[0]==$Wins[2]) {
                $ganador=$Wins[0];
                $perdedor=$Wins[1];
            }elseif ($Wins[1]==$Wins[2]) {
                $ganador=$Wins[1];
                $perdedor=$Wins[0];
            }
            $equipo1 = DB::table('equipos_wow')
                    ->where("id","=",$ganador)
                    ->first();
            $equipo2 = DB::table('equipos_wow')
                    ->where("id","=",$perdedor)
                    ->first();
            $ganador="
                <img class='imagenfixture' src='../imagenes/trofeo/oro.jpg'><br>
                <h4>El ganador es: $equipo1->nombreSEquipo</h4>
                <img class='imagenfixture' src='../imagenes/trofeo/plata.jpg'>
                <h4>El segundo lugar es para: $equipo2->nombreSEquipo</h4>
                ";

            $extras[1]=$ganador;
        }

        $partidas = DB::table('salaswow')
                ->where("id","=",$id)
                ->first();

        $infotorneo="<div class='row'>
                        <div class='col-xl-4'><img class='imagenfixture' src='".$partidas->logo."'></div>
                        <div class='col-xl-4'>
                            <h3 class='titulo_fixture'>Numero de sala: ".$id."</h3><br>
                            <h3 class='titulo_fixture'>Torneo ".$partidas->nombreSala."</h3>
                        </div>
                        <div class='col-xl-4'></div>
                    </div>
                    ";
        $extras[0]=$infotorneo;
        return view('sala/vista-fixture',compact('fixture'),compact('extras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($idmytic)
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
            $tarjetas='';
            $tarjetas.='
                <img src="../resources_wow/no.jpg"><br><br><br><br><br>
                <h3 class="letra_blanca">El encuentro  aun no a empezado ;)</h3>
            ';

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
                                <a href="'.route('RMyticDetalleVista',['Partida'=> $idmytic ,'Mitic'=>$Mitic_partida]).'"><img src="'.$img["value"].'" class="img-mityc-redisenio"></a><br>
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
                            <div class="col-xl-4 mx-auto">
                                El encuentro esta en curso
                            </div>
                    ';
                }
            }
            else{
                $puntajes.='
                            <div class="col-xl-4 mx-auto">
                                El encuentro esta en curso
                            </div>
                    ';
            }
        }
        //dd($conteoBoton);
        return view("sala/vista-miticas",compact('tarjetas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($Partida,$Mitic)
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
            $pagina.='
                    <img src="../resources_wow/no.jpg"><br><br><br><br><br>
                    <h3 class="letra_blanca">El encuentro  aun no a empezado ;)</h3>
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
                                            <td><label>'.$participantes[34].'</label></td>
                                          </tr>
                                          <tr>
                                            <td>Fecha de Encuentro:</td>
                                            <td><label>'.$participantes[35].'</label></td>
                                          </tr>
                                          <tr>
                                            <td>Hora de Encuentro:</td>
                                            <td><label>'.$participantes[36].'</label></td>
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
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[1] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[0].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[2]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[2]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[2]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[2]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[3]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }

                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[4] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[1].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[5]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[5]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[5]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[5]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[6]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[7] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[2].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[8]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[8]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[8]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[8]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[9]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[10] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[3].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[11]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[11]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[11]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[11]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[12]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[13] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[4].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[14]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[14]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[14]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[14]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[15]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                </div>
                                <div class="col-xl-6 bordes-jugadores-detalles">
                                    <h3>Equipo: '. $participantes[32] .'</h3>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[16] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[5].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[17]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[17]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[17]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[17]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[18]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[19] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[6].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[20]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[20]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[20]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[20]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[21]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[22] .'</label><br>
                                        <label>Personaje: </label>
                                        <label>'.$personajes[7].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[23]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[23]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[23]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[23]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[24]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[25] .'</label><br>
                                        <label>Personaje de la partida: </label>
                                        <label>'.$personajes[8].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[26]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[26]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[26]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[26]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[27]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>
                                    </div>
                                    <div class="bordes-jugadores">
                                        <label>Jugador: '. $participantes[28] .'</label><br>
                                        <label>Personaje de la partida: </label>
                                        <label>'.$personajes[9].'</label><br>
                                        <label>Especialización: </label>';
                                            if($participantes[29]=="Tanke"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
                                                        ';
                                            }
                                            if($participantes[29]=="Heler"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
                                                        ';
                                            }
                                            if($participantes[29]=="DPS"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
                                                        ';
                                            }
                                            if($participantes[29]=="Ninguno"){
                                                $pagina.='
                                                        <img class="img_afijo" src="../resources_wow/batle.png">
                                                        ';
                                            }

                                        $pagina.='
                                        <br>
                                        <label>Clase: </label>';
                                            $var=count($claseJu);
                                            for ($i=1; $i <= $var-1 ; $i++) {
                                                if($claseJu[$i]==$participantes[30]){
                                                    $imgEsp = Http::GET(
                                                        'https://us.api.blizzard.com/data/wow/media/playable-class/'.$i.'?namespace=static-us&locale=es_MX&access_token='.$tokkenWOW
                                                    );
                                                        $imgP1 = json_decode( $imgEsp, true );
                                                        $imgP2 =$imgP1["assets"];
                                                        $imgP3 =$imgP2[0];
                                                        $pagina.='
                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
                                                         ';
                                                }
                                            }
                                        $pagina.='
                                        <br>

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
                                        <td><label>'. $participantes[37] .'</label></td>
                                        <td>';
                                            if($participantes[39]=="SI"){
                                                $pagina.='
                                                <label>SI</label>';
                                            }
                                            if($participantes[39]=="NO"){
                                                $pagina.='
                                                <label>NO</label>';
                                            }
                                            if($participantes[39]=="Ninguno"){
                                                $pagina.='
                                                <label>Ninguno</label>';
                                            }

                                        $pagina.='
                                        </select></td>
                                        <td><label>'. $participantes[38] .'</label></td>
                                      </tr>
                                      <tr>
                                        <td>'. $participantes[32] .'</td>
                                        <td><label>'. $participantes[40] .'</label></td>
                                        <td>';
                                            if($participantes[42]=="SI"){
                                                $pagina.='
                                                <label>SI</label>';
                                            }
                                            if($participantes[42]=="NO"){
                                                $pagina.='
                                                <label>NO</label>';
                                            }
                                            if($participantes[42]=="Ninguno"){
                                                $pagina.='
                                                <label>Ninguno</label>';
                                            }

                                        $pagina.='
                                        </select></td>
                                        <td><label>'. $participantes[41] .'</label></td>
                                      </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row bordes-jugadores-detalles">
                                <div class="col-xl-12">
                                    <label>Resumen de la partida:</label><br>
                                    <label>'. $participantes[45] .'</label>
                                </div>
                            </div><br>
                        </div>
                    ';
        }
        return view("sala/mitic-detalles",compact("pagina"),compact("participantes"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
