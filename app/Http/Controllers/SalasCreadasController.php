<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\salaswow;

class SalasCreadasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arbitrowow=auth()->id();
        $salaswow = DB::table('salaswow')
            ->where("arbitro","=",$arbitrowow)
            ->get();

            $li ='';
            foreach($salaswow as $lista){
              $li.='
               <li class="list-group-item redisenio-card-border">
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

                      <a href="'.route('RFixture',$lista->id).'"><button class="btn btn-dark texto-card">Ver Torneo</button></a>
                      </div>
                    </div>
                  </div>
              </li><br>
              ';
          }


        $arbitrodota2=auth()->id();
        $salas = DB::table('sala_dota_2')
                ->where("codigo_Usuario","=",$arbitrodota2)
                ->where("estado","=",true)
                ->get();
            $li2 ='';
            foreach($salas as $indivSala){
                $feachaBD=date_create($indivSala->fecha_Creacion);
                date_add($feachaBD,date_interval_create_from_date_string("14 days"));
                $fecha_Actual=date_format($feachaBD,"d-m-Y");
                $li2.='
                   <li class="list-group-item redisenio-card-border">
                    <a href="">
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
                              <p class="card-text text-danger">Vencimiento del torneo:'.$fecha_Actual.'  </p>
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

public function Recientes(){
  $li5 = '';
  $li6 = '';
  $torneoD2 =DB::table('sala_dota_2')->orderby('fecha_Creacion','desc')->limit(10)->get();
  $torneowow =DB::table('salaswow')->orderby('id','desc')->limit(10)->get();
  if(isset($torneoD2)){
    foreach($torneoD2 as $indivSala){
             $feachaBD=date_create($indivSala->fecha_Creacion);
                date_add($feachaBD,date_interval_create_from_date_string("14 days"));
                $fecha_Actual=date_format($feachaBD,"d-m-Y");
            $li5.='
                   <li class="list-group-item redisenio-card-border">
                    <a href="">
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
                              <p class="card-text text-danger">Vencimiento del torneo:'.$fecha_Actual.' </p>
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
          }
          else{
            $li5 = 'No hay Torneos Creados';
          }
    if(isset($torneowow)){
      foreach($torneowow as $indivSala){
            $li6.='
                   <li class="list-group-item redisenio-card-border">
                      <div class="row redisenio-card-center">
                        <div class="col-xl-3 redisenio-img-content">
                          <img class="redisenio-img" src="'.$indivSala->logo.'" alt="Card image">
                        </div>
                        <div class="col-xl-6 align-self-center text-center">
                          <div class="row">
                            <div class="mx-auto">
                              <h4 class="texto-card">'.$indivSala->nombreSala.'</h4>
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
                          <a href="'.route('RFixtureVista',$indivSala->id).'"><button class="btn btn-dark texto-card">Ver Torneo</button></a>
                          </div>
                        </div>
                      </div>
                  </li><br>
                  ';
                }
          }
          else{
            $li6 = 'No hay Torneos Creados';
          }
    return view('/reciente',compact('li5'),compact('li6'));
}


public function search(Request $request){
    if($request){

        $var = $request->get('selectGame');
        $idwow=request("searchText");
        $li4 = '';
        $li3 = '';
        if($var == 'D2'){
          $li4 = 'DOTA 2';
          $query = Request('searchText');
          $torneo =DB::table('sala_dota_2')->where('id','=',$query)->where("estado","=",true)->orWhere('nombre_Torneo', 'LIKE','%'.$query.'%')->get();
          if(isset($torneo)){
            foreach($torneo as $indivSala){
              $feachaBD=date_create($indivSala->fecha_Creacion);
                date_add($feachaBD,date_interval_create_from_date_string("14 days"));
                $fecha_Actual=date_format($feachaBD,"d-m-Y");
            $li3.='
                   <li class="list-group-item redisenio-card-border">
                      <div class="row redisenio-card-center">
                        <div class="col-xl-3 redisenio-img-content">
                          <img class="redisenio-img" src="'.$indivSala->logo.'" alt="Card image">
                        </div>
                        <div class="col-xl-6 align-self-center text-center">
                          <div class="row">
                            <div class="mx-auto">
                              <h4 class="texto-card">'.$indivSala->nombreSala.'</h4>
                            </div>
                          </div>
                          <div class="row">
                            <div class="mx-auto">
                              <p class="card-text text-danger">Vencimiento del torneo:'.$fecha_Actual.' </p>
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
                  </li><br>
                  ';
                }
          }
          else{
            $li3 = 'No existe el Torneo o la ID es incorrecta';
          }
        }
        if($var == 'WOW'){
          $li4 = $var;
          $query = Request('searchText');
          $torneo =DB::table('salaswow')->where('id','=',$query)->orWhere('nombreSala', 'LIKE','%'.$query.'%')->get();
          if(isset($torneo)){
            foreach($torneo as $indivSala){
            $li3.='
                   <li class="list-group-item redisenio-card-border">
                      <div class="row redisenio-card-center">
                        <div class="col-xl-3 redisenio-img-content">
                          <img class="redisenio-img" src="'.$indivSala->logo.'" alt="Card image">
                        </div>
                        <div class="col-xl-6 align-self-center text-center">
                          <div class="row">
                            <div class="mx-auto">
                              <h4 class="texto-card">'.$indivSala->nombreSala.'</h4>
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
                            <a href="'.route('RFixtureVista',$idwow).'"><button class="btn btn-dark texto-card">Ver Torneo</button></a>
                          </div>
                        </div>
                      </div>
                  </li><br>
                  ';
                }
                }
                else{
                  $li3 = 'No existe el Torneo o la ID es incorrecta';
                }
        }
        return view('/buscaSala',compact('li3'),compact('li4'));

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
