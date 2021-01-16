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

class PDFwow extends Controller
{
    public function convertirPDF($idsala)
    {
    	//tokken
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

    	//idsala
    	$pdf="";
    	$sala = DB::table('salaswow')
                ->where("id","=",$idsala)
                ->first();
        $pdf.="<div class='row'>
                    <div class='col-xl-6'><img class='imagenfixture2' src='".$sala->logo."'></div>
                    <div class='col-xl-6'>
                        <h3 class='titulo_fixture'>Numero de sala: ".$idsala."</h3><br>
                        <h3 class='titulo_fixture'>Torneo ".$sala->nombreSala."</h3>
                    </div>
                </div>
                ";
        $nrofase = DB::table('partida_wow')
	                ->where("idsala","=",$idsala)
	                ->first();
	    $nrofase = DB::table('partida_wow')
	                ->where("idsala","=",$idsala)
	                ->first();
        for ($i=$nrofase->fase; $i >= 1; $i--) {
        	$pdf.="<div class='caja_pdf padding_cards'>
        				<h5 class='letra_blanca'>Numero de fase ".$i."</h5>";
        	$partidas = DB::table('partida_wow')
	                    ->where("idsala","=",$idsala)->where("fase","=",$i)
	                    ->get();
            $encuentro=1;
            foreach ($partidas as $miticas) {
            			$objeto1=$miticas;
            	$miticaDetalles = DB::table('partida_wow_mitics')
				                  ->where("idpartida","=",$miticas->id)
				                  ->get();
	                foreach ($miticaDetalles as $mitica) {
	                	//magia
					    $Partida=$objeto1->id;
					    $Mitic=$mitica->mitica;
					    $miticas = DB::table('jugadores_personaje')
				                        ->where("idpartida","=",$Partida)->where("mitica","=",$Mitic)->exists();
				        if($miticas == false){
				            $pdf.='
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
				                $objeto2=$jugadol1[0];
				                $participantes[] = $jugadol1[0]->especializacion;
				                $participantes[] =$jugadol1[0]->clase;
				                $personajes[] = $jugadol1[0]->nombre;
				            }
				            foreach ($Jequipo2 as $jugador2) {
				                $jugadores2 = DB::table('jugadores_wow')
				                            ->where("id","=",$jugador2->idjugador)->first();
				                $participantes[] = $jugadores2->nickname;
				                $jugadol2 = DB::table('jugadores_personaje')
				                            ->where("idpartida","=",$Partida)->where("idjugador","=",$jugador2->idjugador)->where("mitica","=",$Mitic)->get();
				                $objeto3=$jugadol2;
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
				            $pdf.='
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
				                $pdf.='
				                    <img src="'.$imgAfijo.'" class="img_afijo">
				                ';
				            }
				            $pdf.='
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
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[2]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[2]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[2]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }

				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[4] .'</label><br>
				                                        <label>Personaje: </label>
				                                        <label>'.$personajes[1].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[5]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[5]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[5]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[5]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[7] .'</label><br>
				                                        <label>Personaje: </label>
				                                        <label>'.$personajes[2].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[8]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[8]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[8]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[8]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[10] .'</label><br>
				                                        <label>Personaje: </label>
				                                        <label>'.$personajes[3].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[11]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[11]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[11]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[11]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[13] .'</label><br>
				                                        <label>Personaje: </label>
				                                        <label>'.$personajes[4].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[14]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[14]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[14]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[14]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
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
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[17]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[17]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[17]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[19] .'</label><br>
				                                        <label>Personaje: </label>
				                                        <label>'.$personajes[6].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[20]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[20]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[20]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[20]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[22] .'</label><br>
				                                        <label>Personaje: </label>
				                                        <label>'.$personajes[7].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[23]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[23]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[23]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[23]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[25] .'</label><br>
				                                        <label>Personaje de la partida: </label>
				                                        <label>'.$personajes[8].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[26]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[26]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[26]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[26]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
				                                        <br>
				                                    </div>
				                                    <div class="bordes-jugadores">
				                                        <label>Jugador: '. $participantes[28] .'</label><br>
				                                        <label>Personaje de la partida: </label>
				                                        <label>'.$personajes[9].'</label><br>
				                                        <label>Especialización: </label>';
				                                            if($participantes[29]=="Tanke"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/tank.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[29]=="Heler"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/heal.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[29]=="DPS"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/dps.jpg">
				                                                        ';
				                                            }
				                                            if($participantes[29]=="Ninguno"){
				                                                $pdf.='
				                                                        <img class="img_afijo" src="../resources_wow/batle.png">
				                                                        ';
				                                            }

				                                        $pdf.='
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
				                                                        $pdf.='
				                                                            <img class="img_afijo" src="'.$imgP3["value"].'">
				                                                         ';
				                                                }
				                                            }
				                                        $pdf.='
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
				                                                $pdf.='
				                                                <label>SI</label>';
				                                            }
				                                            if($participantes[39]=="NO"){
				                                                $pdf.='
				                                                <label>NO</label>';
				                                            }
				                                            if($participantes[39]=="Ninguno"){
				                                                $pdf.='
				                                                <label>Ninguno</label>';
				                                            }

				                                        $pdf.='
				                                        </select></td>
				                                        <td><label>'. $participantes[38] .'</label></td>
				                                      </tr>
				                                      <tr>
				                                        <td>'. $participantes[32] .'</td>
				                                        <td><label>'. $participantes[40] .'</label></td>
				                                        <td>';
				                                            if($participantes[42]=="SI"){
				                                                $pdf.='
				                                                <label>SI</label>';
				                                            }
				                                            if($participantes[42]=="NO"){
				                                                $pdf.='
				                                                <label>NO</label>';
				                                            }
				                                            if($participantes[42]=="Ninguno"){
				                                                $pdf.='
				                                                <label>Ninguno</label>';
				                                            }

				                                        $pdf.='
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
	                }
	                $encuentro=$encuentro+1;
            }
            $pdf.="</div>";
        }



        return view("/sala/pdf-torneo-wow",compact('pdf'));
    }
}
