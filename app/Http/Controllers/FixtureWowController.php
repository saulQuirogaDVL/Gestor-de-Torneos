<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\partida_wow;
use Illuminate\Support\Facades\DB;

class FixtureWowController extends Controller
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
                                <a href="'.route('RMytic',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

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
                                <a href="'.route('RMytic',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

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
                                <a href="'.route('RMytic',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

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
                                <a href="'.route('RMytic',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

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
                                <a href="'.route('RMytic',$partida->id).'"><li class="game game-spacer">&nbsp;</li><li class="game game-spacer">&nbsp;</li></a></a>';

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
        return view('sala/sala-fixture-wow',compact('fixture'),compact('extras'));
    }

    /**
    <div class='row'>
                        <div class='col-xl-2'></div>
                    </div>
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($idpartida)
    {
        //ID_SALA
        $idsalan = DB::table('partida_wow')
                        ->where("id","=",$idpartida)->first();
        $Wins = array();
        $partidas = DB::table('partida_wow_mitics')
                        ->where("idpartida","=",$idpartida)->get();
        foreach ($partidas as $partida) {
            if($partida->ganadorIdEquipo!=0 && $partida->perdedorIdEquipo!=0){
                $Wins[]=$partida->ganadorIdEquipo;
            }
        }
        $ganador=0;
        $perdedor=0;

        if($Wins[0]==$Wins[1]){
            $ganador=$Wins[0];
        }elseif ($Wins[0]==$Wins[2]) {
            $ganador=$Wins[0];
        }elseif ($Wins[1]==$Wins[2]) {
            $ganador=$Wins[1];
        }
        $count=0;
        $fixture = DB::table('partida_wow')
                        ->where("idsala","=",$idsalan->idsala)->get();
        $fas=$fixture[0]->fase;
        for ($i=0; $i<($fas+1)/2 ; $i++) {
            $vacio = DB::table('partida_wow')
                        ->where("idsala","=",$idsalan->idsala)->where("fase","=",$fas-1)->get();
            if($fixture[$count]->idequipo1==$ganador || $fixture[$count]->idequipo2==$ganador){
                DB::table('partida_wow')->where("idsala","=",$idsalan->idsala)->where("nroPartida","=",$vacio[$i]->nroPartida)
                        ->update(['idequipo1' => $ganador]);
            }
            if($fixture[$count+1]->idequipo1==$ganador || $fixture[$count+1]->idequipo2==$ganador){
                DB::table('partida_wow')->where("idsala","=",$idsalan->idsala)->where("nroPartida","=",$vacio[$i]->nroPartida)
                        ->update(['idequipo2' => $ganador]);
            }
            $fas--;
            $count=$count+2;
        }
        //if($fixture[0]->idequipo1==$ganador)
        /*

        $faseado = DB::table('partida_wow')
                        ->where("idsala","=",$idsalan->idsala)->first();
        for ($i=$faseado->fase; $i > 0; $i--) {
            for ($j=$i; $j > 0; $j--) {
                # code...
            }
        }
        dd($faseado->fase);*/

        return redirect()->route('RFixture',$idsalan->idsala);

        //return view('sala/sala-fixture-wow',compact('fixture'));
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
