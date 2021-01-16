<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\encuentros_dota2;
use Illuminate\Support\Facades\DB;
use App\Models\detalles_partida_dota2;
use App\Models\info_jugador_dota2;
use App\Models\sala;
use App\Models\picks_dota2;
use App\Models\bans_dota2;
use App\Models\equipos_dota_2;
use App\Models\jugadores_dota_2;

class fixtureDota2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($equipos)
    {
        $ArrayEquipos= explode("&&&", $equipos);
        //tabla de encuentros---7,8,9
        $encuentrosTable = DB::table('encuentros_dota2')
                        ->where("codigo_Sala","=",$ArrayEquipos[2])
                        ->where("equipo_1","=",$ArrayEquipos[0])
                        ->where("equipo_2","=",$ArrayEquipos[1])    
                        ->get();
        //tabla de detalles
        $detallesTable = DB::table('detalles_partida_dota2')
                        ->where("codigo_Encuentro","=",$encuentrosTable[0]->id)
                        ->where("numero_partida","=",$ArrayEquipos[3])  
                        ->get();

        //tabla info de los jugadores
        $infoTable = DB::table('info_jugador_dota2')
                        ->where("codigo_DetalleP","=",$detallesTable[0]->id) 
                        ->get();
        //actualizando equipos a los ganadores
        $equipo1= DB::table('equipos_dota_2')
                        ->where("codigo_Sala","=",$ArrayEquipos[2])
                        ->where("nombre_Equipo","=",$ArrayEquipos[0])  
                        ->get();

        $equipo2= DB::table('equipos_dota_2')
                        ->where("codigo_Sala","=",$ArrayEquipos[2])
                        ->where("nombre_Equipo","=",$ArrayEquipos[1])  
                        ->get();

        $jugadoresE1=DB::table('jugadores_dota_2')
                        ->where("codigo_Equipo","=",$equipo1[0]->id)  
                        ->get();
        $jugadoresE2=DB::table('jugadores_dota_2')
                        ->where("codigo_Equipo","=",$equipo2[0]->id)  
                        ->get();

        //for para la actualizacion del equipo1

        for($ii=0;$ii<5;$ii++){
            DB::table('info_jugador_dota2')
                ->where('id', $infoTable[$ii]->id)
                ->update(['codigo_Jugador' =>$jugadoresE1[$ii]->id ]);
  
        }
        //for para la actualizacion del equipo1

        for($ii=5;$ii<10;$ii++){
            DB::table('info_jugador_dota2')
                ->where('id', $infoTable[$ii]->id)
                ->update(['codigo_Jugador' =>$jugadoresE2[$ii-5]->id ]);
  
        }

         $saladota2 = DB::table('sala_dota_2')
                        ->where("id","=",$encuentrosTable[0]->codigo_Sala)
                        ->get();

        if(auth()->id() == null || auth()->id()!=$saladota2[0]->codigo_Usuario){
            return View('sala/info-partida-dota2-externo')
                    ->with('encuentrosTable',$encuentrosTable)
                    ->with('detallesTable',$detallesTable)
                    ->with('infoTable',$infoTable);
        }else{
            return View('sala/info-partida-dota2')
                    ->with('encuentrosTable',$encuentrosTable)
                    ->with('detallesTable',$detallesTable)
                    ->with('infoTable',$infoTable);
        }


        
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
    public function update(Request $request)
    {
        session_start();
        //jugador 1 team 1
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][0]->id)
                ->update(['personaje' => $request->heroj1t1,'nivel'=>$request->nivelj1t1,
                         'asesinatos' => $request->asesinatosj1t1,'muertes'=>$request->muertesj1t1,
                         'asistencias' => $request->asistenciasj1t1,'slot1'=>$request->img1txtj1,
                         'slot2'=>$request->img2txtj1,'slot3'=>$request->img3txtj1,
                         'slot4'=>$request->img4txtj1,'slot5'=>$request->img5txtj1,
                         'slot6'=>$request->img6txtj1,'slotJunglas'=>$request->img0txtj1]);
        //jugador 1 team 2
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][5]->id)
                ->update(['personaje' => $request->heroj1t2,'nivel'=>$request->nivelj1t2,
                         'asesinatos' => $request->asesinatosj1t2,'muertes'=>$request->muertesj1t2,
                         'asistencias' => $request->asistenciasj1t2,'slot1'=>$request->img1txtj6,
                         'slot2'=>$request->img2txtj6,'slot3'=>$request->img3txtj6,
                         'slot4'=>$request->img4txtj6,'slot5'=>$request->img5txtj6,
                         'slot6'=>$request->img6txtj6,'slotJunglas'=>$request->img0txtj6]);
        //jugador 2 team 1
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][1]->id)
                ->update(['personaje' => $request->heroj2t1,'nivel'=>$request->nivelj2t1,
                         'asesinatos' => $request->asesinatosj2t1,'muertes'=>$request->muertesj2t1,
                         'asistencias' => $request->asistenciasj2t1,'slot1'=>$request->img1txtj2,
                         'slot2'=>$request->img2txtj2,'slot3'=>$request->img3txtj2,
                         'slot4'=>$request->img4txtj2,'slot5'=>$request->img5txtj2,
                         'slot6'=>$request->img6txtj2,'slotJunglas'=>$request->img0txtj2]);
        //jugador 2 team 2
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][6]->id)
                ->update(['personaje' => $request->heroj2t2,'nivel'=>$request->nivelj2t2,
                         'asesinatos' => $request->asesinatosj2t2,'muertes'=>$request->muertesj2t2,
                         'asistencias' => $request->asistenciasj2t2,'slot1'=>$request->img1txtj7,
                         'slot2'=>$request->img2txtj7,'slot3'=>$request->img3txtj7,
                         'slot4'=>$request->img4txtj7,'slot5'=>$request->img5txtj7,
                         'slot6'=>$request->img6txtj7,'slotJunglas'=>$request->img0txtj7]);
        //jugador 3 team 1
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][2]->id)
                ->update(['personaje' => $request->heroj3t1,'nivel'=>$request->nivelj3t1,
                         'asesinatos' => $request->asesinatosj3t1,'muertes'=>$request->muertesj3t1,
                         'asistencias' => $request->asistenciasj3t1,'slot1'=>$request->img1txtj3,
                         'slot2'=>$request->img2txtj3,'slot3'=>$request->img3txtj3,
                         'slot4'=>$request->img4txtj3,'slot5'=>$request->img5txtj3,
                         'slot6'=>$request->img6txtj3,'slotJunglas'=>$request->img0txtj3]);
        //jugador 3 team 2
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][7]->id)
                ->update(['personaje' => $request->heroj3t2,'nivel'=>$request->nivelj3t2,
                         'asesinatos' => $request->asesinatosj3t2,'muertes'=>$request->muertesj3t2,
                         'asistencias' => $request->asistenciasj3t2,'slot1'=>$request->img1txtj8,
                         'slot2'=>$request->img2txtj8,'slot3'=>$request->img3txtj8,
                         'slot4'=>$request->img4txtj8,'slot5'=>$request->img5txtj8,
                         'slot6'=>$request->img6txtj8,'slotJunglas'=>$request->img0txtj8]);
        //jugador 4 team 1
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][3]->id)
                ->update(['personaje' => $request->heroj4t1,'nivel'=>$request->nivelj4t1,
                         'asesinatos' => $request->asesinatosj4t1,'muertes'=>$request->muertesj4t1,
                         'asistencias' => $request->asistenciasj4t1,'slot1'=>$request->img1txtj4,
                         'slot2'=>$request->img2txtj4,'slot3'=>$request->img3txtj4,
                         'slot4'=>$request->img4txtj4,'slot5'=>$request->img5txtj4,
                         'slot6'=>$request->img6txtj4,'slotJunglas'=>$request->img0txtj4]);
        //jugador 4 team 2
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][8]->id)
                ->update(['personaje' => $request->heroj4t2,'nivel'=>$request->nivelj4t2,
                         'asesinatos' => $request->asesinatosj4t2,'muertes'=>$request->muertesj4t2,
                         'asistencias' => $request->asistenciasj4t2,'slot1'=>$request->img1txtj9,
                         'slot2'=>$request->img2txtj9,'slot3'=>$request->img3txtj9,
                         'slot4'=>$request->img4txtj9,'slot5'=>$request->img5txtj9,
                         'slot6'=>$request->img6txtj9,'slotJunglas'=>$request->img0txtj9]);
        //jugador 5 team 1
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][4]->id)
                ->update(['personaje' => $request->heroj5t1,'nivel'=>$request->nivelj5t1,
                         'asesinatos' => $request->asesinatosj5t1,'muertes'=>$request->muertesj5t1,
                         'asistencias' => $request->asistenciasj5t1,'slot1'=>$request->img1txtj5,
                         'slot2'=>$request->img2txtj5,'slot3'=>$request->img3txtj5,
                         'slot4'=>$request->img4txtj5,'slot5'=>$request->img5txtj5,
                         'slot6'=>$request->img6txtj5,'slotJunglas'=>$request->img0txtj5]);
        //jugador 5 team 2
        DB::table('info_jugador_dota2')
                ->where('id', $_SESSION["infoTable"][9]->id)
                ->update(['personaje' => $request->heroj5t2,'nivel'=>$request->nivelj5t2,
                         'asesinatos' => $request->asesinatosj5t2,'muertes'=>$request->muertesj5t2,
                         'asistencias' => $request->asistenciasj5t2,'slot1'=>$request->img1txtj10,
                         'slot2'=>$request->img2txtj10,'slot3'=>$request->img3txtj10,
                         'slot4'=>$request->img4txtj10,'slot5'=>$request->img5txtj10,
                         'slot6'=>$request->img6txtj10,'slotJunglas'=>$request->img0txtj10]);
        
       $ganadorSalas= DB::table('encuentros_dota2')
                        ->where("id","=",$_SESSION["encuentrosTable"][0]->id)   
                        ->get();

        $ganador="";           
        if($request->teamWinner=="1"){
            $ganador=$ganadorSalas[0]->equipo_1;
        }else{
            $ganador=$ganadorSalas[0]->equipo_2;
        }

        //ganador del las partidas 
        DB::table('detalles_partida_dota2')
                ->where('codigo_Encuentro',"=",$_SESSION["encuentrosTable"][0]->id)
                ->where('numero_partida',"=",$_SESSION["detallesTable"][0]->numero_partida)
                ->update(['equipo_Ganador' => $ganador,'eliminaciones_e1'=>$request->killsequipo1
                           ,'eliminaciones_e2'=>$request->killsequipo2 ]);

        //calcular el ganador del encuentro);
        //tabla de todos los encuentos que tengan como ganador al equipo 1
        $equipo1Count = DB::table('detalles_partida_dota2')
                        ->where("codigo_Encuentro","=",$_SESSION["encuentrosTable"][0]->id) 
                        ->where("equipo_Ganador","=",$_SESSION["encuentrosTable"][0]->equipo_1)
                        ->get()->count();  
        //tabla de todos los encuentos que tengan como ganador al equipo 1
        $equipo2Count = DB::table('detalles_partida_dota2')
                        ->where("codigo_Encuentro","=",$_SESSION["encuentrosTable"][0]->id) 
                        ->where("equipo_Ganador","=",$_SESSION["encuentrosTable"][0]->equipo_2)
                        ->get()->count();  
        //tabla de todos los encuentos que tengan como ganador al equipo 1
        $equipoTotal = DB::table('detalles_partida_dota2')
                        ->where("codigo_Encuentro","=",$_SESSION["encuentrosTable"][0]->id) 
                        ->where("equipo_Ganador","=","por verificar")
                        ->get()->count();  

        $ganadorEncuentro="por verificar";
        if($equipo1Count>$equipo2Count){
            if($equipo1Count>$equipoTotal){
                $ganadorEncuentro=$_SESSION["encuentrosTable"][0]->equipo_1;
            }
        }elseif ($equipo2Count>$equipoTotal) {
            $ganadorEncuentro=$_SESSION["encuentrosTable"][0]->equipo_2;
        }



        //cambiando la tabla al ganador
        DB::table('encuentros_dota2')
                        ->where("id","=",$_SESSION["encuentrosTable"][0]->id)   
                        ->update(['equipo_Ganador' => $ganadorEncuentro]);
        //actualizando los demas datos
        $encuentrosTable = DB::table('encuentros_dota2')
                        ->where("codigo_Sala","=",$_SESSION["encuentrosTable"][0]->codigo_Sala)  
                        ->get();
        $salaTable=DB::table('sala_dota_2')
                        ->where("id","=",$_SESSION["encuentrosTable"][0]->codigo_Sala)  
                        ->get();

        switch ($salaTable[0]->numero_Equipos) {
            case '4':
                DB::table('encuentros_dota2')
                        ->where("id","=",$encuentrosTable[2]->id)   
                        ->update(['equipo_1' => $encuentrosTable[0]->equipo_Ganador,
                                  'equipo_2' => $encuentrosTable[1]->equipo_Ganador]);                
                //ganador de la sala
                 DB::table('sala_dota_2')
                        ->where("id","=",$_SESSION["encuentrosTable"][0]->codigo_Sala)   
                        ->update(['equipo_Ganador' => $encuentrosTable[2]->equipo_Ganador]); 

                break;
            case '8':
                    for($i=0;$i<3;$i++){
                         DB::table('encuentros_dota2')
                        ->where("id","=",$encuentrosTable[4+$i]->id)   
                        ->update(['equipo_1' => $encuentrosTable[0+$i+$i]->equipo_Ganador,
                                  'equipo_2' => $encuentrosTable[1+$i+$i]->equipo_Ganador]);                                      
                    }
                //ganador de la sala
                 DB::table('sala_dota_2')
                        ->where("id","=",$_SESSION["encuentrosTable"][0]->codigo_Sala)   
                        ->update(['equipo_Ganador' => $encuentrosTable[6]->equipo_Ganador]);
                break;
            case '16':
                     for($i=0;$i<7;$i++){
                         DB::table('encuentros_dota2')
                        ->where("id","=",$encuentrosTable[8+$i]->id)   
                        ->update(['equipo_1' => $encuentrosTable[0+$i+$i]->equipo_Ganador,
                                  'equipo_2' => $encuentrosTable[1+$i+$i]->equipo_Ganador]);                                      
                    }
                    //ganador de la sala
                 DB::table('sala_dota_2')
                        ->where("id","=",$_SESSION["encuentrosTable"][0]->codigo_Sala)   
                        ->update(['equipo_Ganador' => $encuentrosTable[14]->equipo_Ganador]);
                break;
        }

        echo '<script language="javascript">alert("Guardado!");</script>';
        $url="http://gamingumpires.test".$_SESSION["url"];
        header( "refresh:0.5; url=".$url ); 
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
