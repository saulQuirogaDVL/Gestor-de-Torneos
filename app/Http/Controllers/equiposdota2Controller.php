<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sala;
use App\Models\equipos_dota_2;
use App\Models\jugadores_dota_2;
use Illuminate\Support\Facades\DB;

class equiposdota2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {

        //recibimos la sala y la creamos
        $sala=new sala;
        $sala->codigo_Usuario=$request->codigo_Usuario;
        $sala->nombre_Torneo= $request->nombre_Torneo;
        $sala->logo= $request->logo;
        $sala->tipo_Eliminacion=$request->tipo_Eliminacion; 
        $sala->modo_Juego= $request->modo_Juego;
        $sala->numero_Equipos= $request->numero_Equipos;
        $sala->equipo_ganador= $request->equipo_ganador;

        //lista de equipos para su futura adicion
        $listaEquipos = array($sala['numero_Equipos']);
     
        $countCodigo_Sala = DB::table('sala_dota_2')
                        ->select(DB::raw('count(*) as id'))
                        ->first();

         for ($i=1; $i <= $sala['numero_Equipos'] ; $i++){
                $equipo=new equipos_dota_2;
                //el codigo de la sala esta por verse numero por defecto
                $equipo->codigo_Sala=($countCodigo_Sala->id)+1;
                $equipo->nombre_Equipo=request('equipo'.($i));
                $listaEquipos[$i]=$equipo;
         }


        //lista de jugadores para su futura adicion
        $listaJugadores = array(($sala['numero_Equipos']*5));
     
         $contArray=0;
         for ($i=1; $i <=$sala['numero_Equipos'] ; $i++){
               for ($j=1; $j <=5 ; $j++) { 
                     $jugador=new jugadores_dota_2;
                     //codigo equipo por defecto hasta que se cambie mas adelante
                     $jugador->codigo_Equipo=$i;
                     $jugador->nickname=request('player'.($i).($j));
                     $listaJugadores[$contArray]=$jugador;
                     $contArray++;
               }

         }
         return View('sala/revicion-sala-dota2')
                    ->with('sala',$sala)
                    ->with('listaJugadores',$listaJugadores)
                    ->with('listaEquipos',$listaEquipos);
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
