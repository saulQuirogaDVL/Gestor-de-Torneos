<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sala;
use App\Models\equipos_dota_2;
use App\Models\jugadores_dota_2;
use App\Models\encuentros_dota2;
use App\Models\detalles_partida_dota2;
use App\Models\info_jugador_dota2;
use App\Models\picks_dota2;
use App\Models\bans_dota2;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
session_start();


class salaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            //validamos los datos
            request()->validate([
            'nombreTorneo'=>'required',
            'logo'=>['max:2000','mimes:jpeg,bmp,png','required'],
            'tipoEliminacion'=>'required',
            'modoJuego'=>'required',
            'numeroEquipos'=>'required'
         ]);

        //codigo del usuario
        $codigoUS=auth()->id();
        //para la imagen

         $archivo;

         if($_FILES['logo']['error']>0){
            echo '<script language="javascript">alert("error al cargar el archivos");</script>';
         }else{
            $permitidos= array("image/gif","image/png","image/jpg");
            $limite_kb=10000;
            if(in_array($_FILES['logo']['type'],$permitidos) && $_FILES['logo']['size'] <= $limite_kb*1024){
                $url='C:\laragon\www\GamingUmpires\public\logo_pictures/'.$codigoUS.'/';
                $archivo=$url.$_FILES['logo']['name'];

                if(!file_exists($url)){
                    mkdir($url);
                }

                if(!file_exists($archivo)){
                    $resultado=@move_uploaded_file($_FILES['logo']['tmp_name'],$archivo);
                    if($resultado){
                         echo '<script language="javascript">alert("Imagen Guardada Con Exito!!");</script>';
                    }else{
                         echo '<script language="javascript">alert("no guadado");</script>';
                    }
                }
            }else{
                 echo '<script language="javascript">alert("tamaño o formato invalido");</script>';
            }
         }

         //creamos nuestro objeto sala y
         $sala=new sala;
         $sala->codigo_Usuario=$codigoUS;
         $sala->nombre_Torneo= request('nombreTorneo');
         $sala->logo= "../logo_pictures/".$codigoUS.'/'.$_FILES['logo']['name'];
         $sala->tipo_Eliminacion= request('tipoEliminacion');
         $sala->modo_Juego= request('modoJuego');
         $sala->numero_Equipos= request('numeroEquipos');
         //el equipo ganador no existe aun
         $sala->equipo_ganador= "por concluir";
          unset($_SESSION["creacionD2"]);
        return View('sala/creacion-equipos-sala-dota2')->with('sala',$sala);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        //almacenando la sala en la BD
        $sala=new sala;
        $sala->codigo_Usuario=$request->codigo_Usuario;
        $sala->nombre_Torneo= $request->nombre_Torneo;
        $sala->logo= $request->logo;
        $sala->tipo_Eliminacion=$request->tipo_Eliminacion;
        $sala->modo_Juego= $request->modo_Juego;
        $sala->numero_Equipos= $request->numero_Equipos;
        $sala->equipo_Ganador= $request->equipo_ganador;
        $now=Carbon::now();
        $sala->fecha_Creacion=$now->format('y-m-d');
        $sala->estado=true;
        $sala->save();

        //almacenando los equipos en la BD
        //recibiendo la ultima tabla creada anteriormente
         $salas = DB::table('sala_dota_2')
                        ->where("codigo_Usuario","=",$request->codigo_Usuario)
                        ->latest('id')
                        ->first();

        //guardando el id de la ultima tabla
         $cont=true;
         $codigoSala;
         foreach ($salas as $pr) {
             if($cont){
                $codigoSala=$pr;
                $cont=false;
             }
         }

         //insertando los equipos en la BD
        //insertando los jugadores en la BD
        $listaEquipos = explode("," , $_POST['arrayEquipos']);
        $listaJugadores = explode("," , $_POST['arrayJugadores']);
        $contArray=0;
        for ($i=1; $i <= $sala['numero_Equipos'] ; $i++){
                $equipo=new equipos_dota_2;
                $equipo->codigo_Sala=$codigoSala;
                $equipo->nombre_Equipo=$listaEquipos[$i-1];
                $equipo->save();
                for ($j=1; $j <=5 ; $j++) {
                        $jugador=new jugadores_dota_2;
                        //recibiendo la ultima tabla creada anteriormente
                        $equipos = DB::table('equipos_dota_2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                        //guardando el id de la ultima tabla de equipos
                        $cont=true;
                        $codigoEquipo;
                         foreach ($equipos as $pr) {
                             if($cont){
                              $codigoEquipo=$pr;
                             $cont=false;
                            }
                        }
                        $jugador->codigo_Equipo=$codigoEquipo;
                        $jugador->nickname=$listaJugadores[$contArray];
                        $jugador->save();
                        $contArray++;
                }
         }

        //creando los encuentros en la BD
        $cantidadE= $request->numero_Equipos;
        $eliminacion=1;
        $picksban=false;
        //convirtiendo el tipo de eliminacion a numero para el for
        if ($request->tipo_Eliminacion=='BO3') {
            $eliminacion=3;
        }elseif ($request->tipo_Eliminacion=='BO5') {
            $eliminacion=5;
        }
        //convirtiendo el modo de juego a bool
        if ($request->modo_Juego=='ALL PICK') {
            $picksban=true;
        }


        switch ($cantidadE) {
             case 4:
                 //creando los primeros encuentros
                 for ($i=1; $i <= 4 ; $i+=2) {
                      $equipon1='cuartosFinal'.$i;
                      $sumae2=$i+1;
                      $equipon2='cuartosFinal'.$sumae2;

                      $encuentros=new encuentros_dota2;
                      $encuentros->codigo_Sala=$codigoSala;
                      $encuentros->equipo_1=$request->$equipon1;
                      $encuentros->equipo_2=$request->$equipon2;
                      $encuentros->equipo_Ganador='por verificar';
                      $encuentros->save();
                      for ($j=1; $j <= $eliminacion; $j++) {
                          $detalles=new detalles_partida_dota2;

                          $encuentroLast = DB::table('encuentros_dota2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                         //guardando el id de la ultima tabla de equipos
                           $cont=true;
                           $codigoEnc;
                            foreach ($encuentroLast as $pr) {
                                if($cont){
                                     $codigoEnc=$pr;
                                         $cont=false;
                                        }
                                    }
                          $detalles->codigo_Encuentro=$codigoEnc;
                          $detalles->eliminaciones_e1=0;
                          $detalles->eliminaciones_e2=0;
                          $detalles->numero_partida=$j;
                          $detalles->equipo_Ganador='por verificar';
                          $detalles->save();
                          $contJugador=0;
                            for ($m=0; $m <10 ; $m++) {
                                $DetalleJ=new info_jugador_dota2;
                                $detalleLast = DB::table('detalles_partida_dota2')
                                            ->where("codigo_Encuentro","=",$codigoEnc)
                                            ->latest('id')
                                            ->first();
                                 //guardando el id de la ultima tabla de detalles
                                   $cont=true;
                                   $codigoDet;
                                    foreach ($detalleLast as $pr) {
                                        if($cont){
                                             $codigoDet=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //buscando el id de cada jugador
                                 $equipoPerteneciente=$request->$equipon1;;
                                 if ($m>4){
                                      $equipoPerteneciente=$request->$equipon2;
                                      if ($m==5) {
                                          $contJugador=0;
                                      }
                                }

                                $equipoDatos = DB::table('equipos_dota_2')
                                            ->where("codigo_Sala","=",$codigoSala)
                                            ->where("nombre_Equipo","=",$equipoPerteneciente)
                                            ->latest('id')
                                            ->first();
                                //obteniendo el id del equipo
                                $cont=true;
                                $idEquipoToJugadores;
                                    foreach ($equipoDatos as $pr) {
                                        if($cont){
                                             $idEquipoToJugadores=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //codigo del equipo para listar los jugadores
                                   $jugadoresInfo = DB::table('jugadores_dota_2')
                                            ->where("codigo_Equipo","=",$idEquipoToJugadores)
                                            ->get();
                                //adicion de la info de cada jugador
                                $DetalleJ->codigo_DetalleP=$codigoDet;
                                $DetalleJ->codigo_Jugador=$jugadoresInfo[$contJugador]->id;
                                $DetalleJ->personaje=0;
                                $DetalleJ->nivel=0;
                                $DetalleJ->asesinatos=0;
                                $DetalleJ->muertes=0;
                                $DetalleJ->asistencias=0;
                                $DetalleJ->slot1=0;
                                $DetalleJ->slot2=0;
                                $DetalleJ->slot3=0;
                                $DetalleJ->slot4=0;
                                $DetalleJ->slot5=0;
                                $DetalleJ->slot6=0;
                                $DetalleJ->slotJunglas=0;
                                $DetalleJ->save();
                                $contJugador++;
                            }
                      }
                 }
                 //los demas encuentros
                 $encuentros=new encuentros_dota2;
                 $encuentros->codigo_Sala=$codigoSala;
                 $encuentros->equipo_1=' ';
                 $encuentros->equipo_2=' ';
                 $encuentros->equipo_Ganador='por verificar';
                 $encuentros->save();

                 for ($j=1; $j <= $eliminacion; $j++) {
                          $detalles=new detalles_partida_dota2;

                          $encuentroLast = DB::table('encuentros_dota2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                         //guardando el id de la ultima tabla de equipos
                           $cont=true;
                           $codigoEnc;
                            foreach ($encuentroLast as $pr) {
                                if($cont){
                                     $codigoEnc=$pr;
                                         $cont=false;
                                        }
                                    }
                          $detalles->codigo_Encuentro=$codigoEnc;
                          $detalles->eliminaciones_e1=0;
                          $detalles->eliminaciones_e2=0;
                          $detalles->numero_partida=$j;
                          $detalles->equipo_Ganador='por verificar';
                          $detalles->save();
                            for ($m=0; $m <10 ; $m++) {
                                $DetalleJ=new info_jugador_dota2;
                                $detalleLast = DB::table('detalles_partida_dota2')
                                            ->where("codigo_Encuentro","=",$codigoEnc)
                                            ->latest('id')
                                            ->first();
                                 //guardando el id de la ultima tabla de detalles
                                   $cont=true;
                                   $codigoDet;
                                    foreach ($detalleLast as $pr) {
                                        if($cont){
                                             $codigoDet=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //adicion de la info de cada jugador
                                $DetalleJ->codigo_DetalleP=$codigoDet;
                                $DetalleJ->codigo_Jugador=1;
                                $DetalleJ->personaje=0;
                                $DetalleJ->nivel=0;
                                $DetalleJ->asesinatos=0;
                                $DetalleJ->muertes=0;
                                $DetalleJ->asistencias=0;
                                $DetalleJ->slot1=0;
                                $DetalleJ->slot2=0;
                                $DetalleJ->slot3=0;
                                $DetalleJ->slot4=0;
                                $DetalleJ->slot5=0;
                                $DetalleJ->slot6=0;
                                $DetalleJ->slotJunglas=0;
                                $DetalleJ->save();
                            }
                      }

                 break;
            case 8:

                 //creando los primeros encuentros
                 for ($i=1; $i <= 8 ; $i+=2) {
                      $equipon1='octavosFinal'.$i;
                      $sumae2=$i+1;
                      $equipon2='octavosFinal'.$sumae2;

                      $encuentros=new encuentros_dota2;
                      $encuentros->codigo_Sala=$codigoSala;
                      $encuentros->equipo_1=$request->$equipon1;
                      $encuentros->equipo_2=$request->$equipon2;
                      $encuentros->equipo_Ganador='por verificar';
                      $encuentros->save();
                      for ($k=1; $k <= $eliminacion; $k++) {
                          $detalles=new detalles_partida_dota2;

                          $encuentroLast = DB::table('encuentros_dota2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                         //guardando el id de la ultima tabla de equipos
                           $cont=true;
                           $codigoEnc;
                            foreach ($encuentroLast as $pr) {
                                if($cont){
                                     $codigoEnc=$pr;
                                         $cont=false;
                                        }
                                    }
                          $detalles->codigo_Encuentro=$codigoEnc;
                          $detalles->eliminaciones_e1=0;
                          $detalles->eliminaciones_e2=0;
                          $detalles->numero_partida=$k;
                          $detalles->equipo_Ganador='por verificar';
                          $detalles->save();
                          for ($m=0; $m <10 ; $m++) {
                                $DetalleJ=new info_jugador_dota2;
                                $detalleLast = DB::table('detalles_partida_dota2')
                                            ->where("codigo_Encuentro","=",$codigoEnc)
                                            ->latest('id')
                                            ->first();
                                 //guardando el id de la ultima tabla de detalles
                                   $cont=true;
                                   $codigoDet;
                                    foreach ($detalleLast as $pr) {
                                        if($cont){
                                             $codigoDet=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //adicion de la info de cada jugador
                                $DetalleJ->codigo_DetalleP=$codigoDet;
                                $DetalleJ->codigo_Jugador=1;
                                $DetalleJ->personaje=0;
                                $DetalleJ->nivel=0;
                                $DetalleJ->asesinatos=0;
                                $DetalleJ->muertes=0;
                                $DetalleJ->asistencias=0;
                                $DetalleJ->slot1=0;
                                $DetalleJ->slot2=0;
                                $DetalleJ->slot3=0;
                                $DetalleJ->slot4=0;
                                $DetalleJ->slot5=0;
                                $DetalleJ->slot6=0;
                                $DetalleJ->slotJunglas=0;
                                $DetalleJ->save();
                            }
                      }
                 }
                 //los demas encuentros
                 for ($j=0; $j < 3 ; $j++) {
                     $encuentros=new encuentros_dota2;
                     $encuentros->codigo_Sala=$codigoSala;
                     $encuentros->equipo_1=' ';
                     $encuentros->equipo_2=' ';
                     $encuentros->equipo_Ganador='por verificar';
                     $encuentros->save();
                     for ($s=1; $s <= $eliminacion; $s++) {
                          $detalles=new detalles_partida_dota2;

                          $encuentroLast = DB::table('encuentros_dota2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                         //guardando el id de la ultima tabla de equipos
                           $cont=true;
                           $codigoEnc;
                            foreach ($encuentroLast as $pr) {
                                if($cont){
                                     $codigoEnc=$pr;
                                         $cont=false;
                                        }
                                    }
                          $detalles->codigo_Encuentro=$codigoEnc;
                          $detalles->eliminaciones_e1=0;
                          $detalles->eliminaciones_e2=0;
                          $detalles->numero_partida=$s;
                          $detalles->equipo_Ganador='por verificar';
                          $detalles->save();
                          for ($m=0; $m <10 ; $m++) {
                                $DetalleJ=new info_jugador_dota2;
                                $detalleLast = DB::table('detalles_partida_dota2')
                                            ->where("codigo_Encuentro","=",$codigoEnc)
                                            ->latest('id')
                                            ->first();
                                 //guardando el id de la ultima tabla de detalles
                                   $cont=true;
                                   $codigoDet;
                                    foreach ($detalleLast as $pr) {
                                        if($cont){
                                             $codigoDet=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //adicion de la info de cada jugador
                                $DetalleJ->codigo_DetalleP=$codigoDet;
                                $DetalleJ->codigo_Jugador=1;
                                $DetalleJ->personaje=0;
                                $DetalleJ->nivel=0;
                                $DetalleJ->asesinatos=0;
                                $DetalleJ->muertes=0;
                                $DetalleJ->asistencias=0;
                                $DetalleJ->slot1=0;
                                $DetalleJ->slot2=0;
                                $DetalleJ->slot3=0;
                                $DetalleJ->slot4=0;
                                $DetalleJ->slot5=0;
                                $DetalleJ->slot6=0;
                                $DetalleJ->slotJunglas=0;
                                $DetalleJ->save();
                            }
                      }
                 }
                break;
            case 16:

                 //creando los primeros encuentros
                 for ($i=1; $i <= 16 ; $i+=2) {
                      $equipon1='clasificacion'.$i;
                      $sumae2=$i+1;
                      $equipon2='clasificacion'.$sumae2;

                      $encuentros=new encuentros_dota2;
                      $encuentros->codigo_Sala=$codigoSala;
                      $encuentros->equipo_1=$request->$equipon1;
                      $encuentros->equipo_2=$request->$equipon2;
                      $encuentros->equipo_Ganador='por verificar';
                      $encuentros->save();
                      for ($k=1; $k<= $eliminacion; $k++) {
                          $detalles=new detalles_partida_dota2;

                          $encuentroLast = DB::table('encuentros_dota2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                         //guardando el id de la ultima tabla de equipos
                           $cont=true;
                           $codigoEnc;
                            foreach ($encuentroLast as $pr) {
                                if($cont){
                                     $codigoEnc=$pr;
                                         $cont=false;
                                        }
                                    }
                          $detalles->codigo_Encuentro=$codigoEnc;
                          $detalles->eliminaciones_e1=0;
                          $detalles->eliminaciones_e2=0;
                          $detalles->numero_partida=$k;
                          $detalles->equipo_Ganador='por verificar';
                          $detalles->save();
                           $contJugador=0;
                            for ($m=0; $m <10 ; $m++) {
                                $DetalleJ=new info_jugador_dota2;
                                $detalleLast = DB::table('detalles_partida_dota2')
                                            ->where("codigo_Encuentro","=",$codigoEnc)
                                            ->latest('id')
                                            ->first();
                                 //guardando el id de la ultima tabla de detalles
                                   $cont=true;
                                   $codigoDet;
                                    foreach ($detalleLast as $pr) {
                                        if($cont){
                                             $codigoDet=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //buscando el id de cada jugador
                                 $equipoPerteneciente=$request->$equipon1;;
                                 if ($m>4){
                                      $equipoPerteneciente=$request->$equipon2;
                                      if ($m==5) {
                                          $contJugador=0;
                                      }
                                }

                                $equipoDatos = DB::table('equipos_dota_2')
                                            ->where("codigo_Sala","=",$codigoSala)
                                            ->where("nombre_Equipo","=",$equipoPerteneciente)
                                            ->latest('id')
                                            ->first();
                                //obteniendo el id del equipo
                                $cont=true;
                                $idEquipoToJugadores;
                                    foreach ($equipoDatos as $pr) {
                                        if($cont){
                                             $idEquipoToJugadores=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //codigo del equipo para listar los jugadores
                                   $jugadoresInfo = DB::table('jugadores_dota_2')
                                            ->where("codigo_Equipo","=",$idEquipoToJugadores)
                                            ->get();
                                //adicion de la info de cada jugador
                                $DetalleJ->codigo_DetalleP=$codigoDet;
                                $DetalleJ->codigo_Jugador=$jugadoresInfo[$contJugador]->id;
                                $DetalleJ->personaje=0;
                                $DetalleJ->nivel=0;
                                $DetalleJ->asesinatos=0;
                                $DetalleJ->muertes=0;
                                $DetalleJ->asistencias=0;
                                $DetalleJ->slot1=0;
                                $DetalleJ->slot2=0;
                                $DetalleJ->slot3=0;
                                $DetalleJ->slot4=0;
                                $DetalleJ->slot5=0;
                                $DetalleJ->slot6=0;
                                $DetalleJ->slotJunglas=0;
                                $DetalleJ->save();
                                $contJugador++;
                            }
                      }
                 }
                 //los demas encuentros
                 for ($j=0; $j < 7 ; $j++) {
                     $encuentros=new encuentros_dota2;
                     $encuentros->codigo_Sala=$codigoSala;
                     $encuentros->equipo_1=' ';
                     $encuentros->equipo_2=' ';
                     $encuentros->equipo_Ganador='por verificar';
                     $encuentros->save();
                     for ($s=1; $s <= $eliminacion; $s++) {
                          $detalles=new detalles_partida_dota2;

                          $encuentroLast = DB::table('encuentros_dota2')
                                    ->where("codigo_Sala","=",$codigoSala)
                                    ->latest('id')
                                    ->first();
                         //guardando el id de la ultima tabla de equipos
                           $cont=true;
                           $codigoEnc;
                            foreach ($encuentroLast as $pr) {
                                if($cont){
                                     $codigoEnc=$pr;
                                         $cont=false;
                                        }
                                    }
                          $detalles->codigo_Encuentro=$codigoEnc;
                          $detalles->eliminaciones_e1=0;
                          $detalles->eliminaciones_e2=0;
                          $detalles->numero_partida=$s;
                          $detalles->equipo_Ganador='por verificar';
                          $detalles->save();
                          for ($m=0; $m <10 ; $m++) {
                                $DetalleJ=new info_jugador_dota2;
                                $detalleLast = DB::table('detalles_partida_dota2')
                                            ->where("codigo_Encuentro","=",$codigoEnc)
                                            ->latest('id')
                                            ->first();
                                 //guardando el id de la ultima tabla de detalles
                                   $cont=true;
                                   $codigoDet;
                                    foreach ($detalleLast as $pr) {
                                        if($cont){
                                             $codigoDet=$pr;
                                                 $cont=false;
                                                }
                                            }
                                //adicion de la info de cada jugador
                                $DetalleJ->codigo_DetalleP=$codigoDet;
                                $DetalleJ->codigo_Jugador=1;
                                $DetalleJ->personaje=0;
                                $DetalleJ->nivel=0;
                                $DetalleJ->asesinatos=0;
                                $DetalleJ->muertes=0;
                                $DetalleJ->asistencias=0;
                                $DetalleJ->slot1=0;
                                $DetalleJ->slot2=0;
                                $DetalleJ->slot3=0;
                                $DetalleJ->slot4=0;
                                $DetalleJ->slot5=0;
                                $DetalleJ->slot6=0;
                                $DetalleJ->slotJunglas=0;
                                $DetalleJ->save();
                            }
                      }
                 }
                break;
         }
        $arbitrowow=auth()->id();
        $salaswow = DB::table('salaswow')
            ->where("arbitro","=",$arbitrowow)
            ->get();

            $li ='';
            foreach($salaswow as $lista){
              $li.='
               <li class="list-group-item redisenio-card-border">
                <a href="'.route('RFixture',$lista->id).'">
                  <div class="row redisenio-card-center">
                    <div class="col-xl-3 redisenio-img-content">
                      <img class="redisenio-img" src="'.$lista->logo.'" alt="Card image">
                    </div>
                    <div class="col-xl-6 align-self-center text-center">
                      <div class="row">
                        <div class="mx-auto">
                          <h4 class="texto-card">'.$lista->nombreSala.'</h4>
                        </div>
                      </div>
                      <div class="row">
                        <div class="mx-auto">
                          <p class="card-text text-danger">Vencimiento del torneo: </p>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-3 align-self-center text-center">
                      <div class="mx-auto">
                        <button class="btn btn-dark texto-card">Borrar Torneo</button>
                      </div>
                    </div>
                  </div>
                </a>
              </li><br>
              ';
          }

          //para arreglar su codigo dle bajito
        $arbitrodota2=auth()->id();
        $salas = DB::table('sala_dota_2')
                ->where("codigo_Usuario","=",$arbitrodota2)
                ->get();
            $li2 ='';
            foreach($salas as $indivSala){
                $li2.='
                   <li class="list-group-item redisenio-card-border">
                    <a href="seleccion-juego">
                      <div class="row redisenio-card-center">
                        <div class="col-xl-3 redisenio-img-content">
                          <img class="redisenio-img" src="'.$indivSala->logo.'" alt="Card image">
                        </div>
                        <div class="col-xl-6 align-self-center text-center">
                          <div class="row">
                            <div class="mx-auto">
                              <h4 class="texto-card">'.$indivSala->nombre_Torneo.'</h4>
                            </div>
                          </div>
                          <div class="row">
                            <div class="mx-auto">
                              <p class="card-text text-danger">Vencimiento del torneo: </p>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-3 align-self-center text-center">
                          <div class="mx-auto">
                          <form method="post" action="'.url("/sala/detalles-partida-dota2/".$indivSala->id).'">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-dark texto-card">Ver Detalles</button></form>
                          </div>
                        </div>
                      </div>
                    </a>
                  </li><br>
                  ';
            }


        return view('sala/salas-creadas',compact('li'),compact('li2'));
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


    public function cookie(){
       
         if(auth()->id()==null){
            return view('welcome');
         }else{
            return View('sala/creacion-sala-dota2');
         }
         

    }


}
