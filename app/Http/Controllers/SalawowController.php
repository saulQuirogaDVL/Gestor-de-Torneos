<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\salaswow;
use App\Models\jugadores_wow;
use App\Models\equipos_wow;
use App\Models\partida_wow;
use App\Models\jugador_equipo_wow;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Facades\DB;

class SalawowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("sala/creacion-sala-wow");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        request()->validate([
            'logo'=>['max:2000','mimes:jpeg,bmp,png','required'],
        ]);

        $equipos_wow_array = array();
        $jugadores_wow_array = array();

        $salaswow=new salaswow;
        $salaswow->nombreSala= request('nombreTorneo');
        $archivo;
        $user= auth()->id();
        if($_FILES['logo']['error']>0){
            echo '<script language="javascript">alert("error al cargar el archivos");</script>';
        }else{
            $permitidos= array("image/gif","image/png","image/jpg");
            $limite_kb=1000000;
            if(in_array($_FILES['logo']['type'],$permitidos) && $_FILES['logo']['size'] <= $limite_kb*1024){

                $url='C:\laragon\www\ProyectoGulag\public\logos_wow_torneos/'.$user.'/';
                $archivo=$url.$_FILES['logo']['name'];

                if(!file_exists($url)){
                    //babu checa que mi carpeta del proyecto diferente asi que si quieres probar coloca tu carpeta en la linea 44
                    mkdir($url);
                }

                if(!file_exists($archivo)){
                    $resultado=@move_uploaded_file($_FILES['logo']['tmp_name'],$archivo);
                    if($resultado){
                         echo '<script language="javascript">alert("guardado");</script>';
                    }else{
                         echo '<script language="javascript">alert("no guadado");</script>';
                    }
                }
            }else{
                 echo '<script language="javascript">alert("tamaño o formato invalido");</script>';
            }
        }
        $salaswow->logo= "../logos_wow_torneos/".$user.'/'.$_FILES['logo']['name'];
        $salaswow->arbitro=$user;
        $salaswow->save();
         for($j=1;$j<=20;$j++){
            $equipo = request('nombreEquipo'.$j);
            if($equipo!=null){
                $nombreEquip = DB::table('equipos_wow')
                        ->where("nombreSEquipo","=",$equipo)
                        ->first();
                if($nombreEquip==null){
                    $equipos=new equipos_wow;
                    $equipos->nombreSEquipo = $equipo;
                    $equipos->save();
                    $equipos_wow_array[] = $equipos->nombreSEquipo;
                }
                else{
                    $equipos_wow_array[] = $nombreEquip->nombreSEquipo;
                }
                for($i=1;$i<=5;$i++){
                    $njugador=request('name'.$j.'_'.$i);
                    $nombreJuga = DB::table('jugadores_wow')
                        ->where("nickname","=",$njugador)
                        ->first();
                    if($nombreJuga==null){
                        $jugador=new jugadores_wow;
                        $jugador->nickname= $njugador;
                        $jugador->save();
                        $jugadores_wow_array[] = $jugador->nickname;
                    }
                    else{
                        $jugadores_wow_array[] = $nombreJuga->nickname;
                    }
                    $equipoCompuesto=new jugador_equipo_wow;
                    $salaID = DB::table('salaswow')
                        ->where("nombreSala","=",request('nombreTorneo'))
                        ->first();
                    $IDSa=$salaID->id;
                    $equipoCompuesto->idsala=$IDSa;
                    $equipoID = DB::table('equipos_wow')
                        ->where("nombreSEquipo","=",$equipo)
                        ->first();
                    $IDEq=$equipoID->id;
                    $equipoCompuesto->idequipo=$IDEq;
                    $jugadorID = DB::table('jugadores_wow')
                        ->where("nickname","=",$njugador)
                        ->first();
                    $IDJu = $jugadorID->id;
                    $equipoCompuesto->idjugador=$IDJu;
                    $equipoCompuesto->save();
                }
            }
            else{
                $j=21;
            }
        }
        $fases = count($equipos_wow_array);
        $fasesDiv = $fases/2;
        if($fases/2==2){
            $fase=2;
        }
        if($fases/2==4){
            $fase=3;
        }
        if($fases/2==8){
            $fase=3;
        }
        $countEqui = 0;
        $arraySorteado=array_rand($equipos_wow_array,$fases);
        $nro=0;
        shuffle($arraySorteado);
        for($a=0;$a<$fasesDiv;$a++){
            $partida=new partida_wow;
            $nSala = request('nombreTorneo');
            $salaid = DB::table('salaswow')
                        ->where("nombreSala","=",$nSala)
                        ->first();
            $partida->idsala = $salaid->id;
            $partida->fase = $fase;
            $equipo1 = $equipos_wow_array[$arraySorteado[$countEqui]];
            $idequipoFirst = DB::table('equipos_wow')
                        ->where("nombreSEquipo","=",$equipo1)
                        ->first();
            $partida->idequipo1 = $idequipoFirst->id;
            $countEqui=$countEqui+1;
            $equipo2 = $equipos_wow_array[$arraySorteado[$countEqui]];
            $idequipoSecond = DB::table('equipos_wow')
                        ->where("nombreSEquipo","=",$equipo2)
                        ->first();
            $partida->idequipo2 = $idequipoSecond->id;
            $partida->nroPartida = $a+1;
            $countEqui=$countEqui+1;
            $partida->save();
            $nro=$a+1;
        }
        for ($i=$fase-1; $i > 0; $i--) {
            for ($j=$i; $j > 0; $j--) {
                $nro=$nro+1;
                $partida1=new partida_wow;
                $nSala = request('nombreTorneo');
                $salaid = DB::table('salaswow')
                            ->where("nombreSala","=",$nSala)
                            ->first();
                $partida1->idsala = $salaid->id;
                $partida1->fase = $i;
                $partida1->idequipo1 = 0;
                $partida1->idequipo2 = 0;
                $partida1->nroPartida = $nro;
                $partida1->save();
            }
        }
        return redirect()->route('Rsalas');
        //return redirect()->route('Rfixturewow')->with('arrayTorneo',$countEqui);
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
