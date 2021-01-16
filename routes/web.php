<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalawowController;
use App\Http\Controllers\SalasCreadasController;
use App\Http\Controllers\FixtureWowController;
use App\Http\Controllers\MyticSelectionController;
use App\Http\Controllers\fixtureDota2Controller;
use App\Http\Controllers\salaController;
use App\Http\Controllers\VistaUsuario;
use App\Http\Controllers\PDFwow;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('sala/menu-usuario');
})->name('dashboard');

//si desea ir al dashboard utilize este link
/*
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/



Route::view('/home','home')->name('home');
Route::view('/prueba','prueba')->name('prueba');
Route::view('/menu-usuario','sala/menu-usuario')->name('menu-usuario');
Route::view('/seleccion-juego','sala/seleccion-juego')->name('seleccion-juego');
Route::view('sala/seleccion-juego','sala/seleccion-juego')->name('seleccion-juego-sala');
Route::view('/salas-creadas','sala/salas-creadas')->name('salas-creadas');
Route::view('sala/salas-creadas','sala/salas-creadas')->name('salas-creadas-sala');


Route::get('/creacion-sala-dota2',[salaController::class,'cookie'])->name('creacion-sala-dota2');

Route::view('/creacion-sala-dota2-show','sala/creacion-sala-dota2');
//Busca Los recientes
Route::get('/reciente',[SalasCreadasController::class, 'Recientes'])->name('Partidasrecientes');

//recibe los datos  desde creacion-sala-dota2
Route::post('/sala/addModelSala','App\Http\Controllers\salaController@index')->name('add-Model-Sala');
//recibe los datos  desde el controlador salaController@index
Route::view('/sala/creacion-equipos-sala-dota2','sala/creacion-equipos-sala-dota2')->name('creacion-equipos-sala-dota2');

//recibe los datos desde creacion-equipos-sala-dota2
Route::post('/sala/addModelEquipos','App\Http\Controllers\equiposdota2Controller@index')->name('add-Model-Equipos');
//recibe los datos desde el controlador equiposdota2Controller@index
Route::view('/sala/revicion-sala-dota2','sala/revicion-sala-dota2')->name('revicion-sala-dota2');

//Recibe la key para buscar el torneo
Route::post('/buscar-sala',[SalasCreadasController::class, 'search'])->name('buscaTorneo');



//recibe los datos desde revision-sala-dota2
Route::post('/sala/crearTorneo','App\Http\Controllers\salaController@create')->name('createSalaDota2');
//recibe los datos desde el controlador equiposdota2Controller@index
Route::view('/sala/revicion-sala-dota2','sala/revicion-sala-dota2')->name('revicion-sala-dota2');

//recibe el codigo de la sala y presenta los detalles de la partida desde salas-creadas
Route::post('/sala/detalles-partida-dota2/{id}','App\Http\Controllers\detallesController@index')->name('detalles-sala-dota2');
Route::view('/sala/detalles-show-dota2','sala/detalles-partida-dota2')->name('detalles-show-dota2');


//recibe a los 2 equipos y con eso recupera el codigo del encunetro, el cual utiliza para hallar el detalle del encuentro
//y de esta manera obtener la info de la partida de los jugadores
Route::get('/sala/fixture/{equipos}', [fixtureDota2Controller::class, 'index'])->name('fixturedota2');
Route::view('/sala/info-partida-dota2','sala/info-partida-dota2')->name('info-show-dota2');

//informacion de la partida pera para un usuario no logeado - externo
Route::view('/sala/info-partida-dota2-externo','sala/info-partida-dota2-externo')->name('info-show-dota2-ex');

//recibe los datos actualizados de los jugadores
Route::get('/sala/fixture/guardar/info', [fixtureDota2Controller::class, 'update'])->name('fixturedota2Update');
//Route::view('/sala/info-partida-dota2','sala/info-partida-dota2')->name('info-show-dota2');

//recibe el codigo de la sala e imprime todos los datos de la partida en un pdf
Route::view('/sala/Generate-details-dota2','/sala/Generate-details-dota2')->name('generatedota2');


//salas world of warcraft
Route::get('/torneo-wow', [SalawowController::class, 'index'])->name('RCrearTorneo');
Route::post('/torneo-wow-registrado', [SalawowController::class, 'create'])->name('RAñadirTorneo');

Route::get('/salas-creadas', [SalasCreadasController::class, 'index'])->name('Rsalas');


Route::get('/fixture-wow-{idsala}', [FixtureWowController::class, 'index'])->name('RFixture');

Route::get('/crear-siguiente-partida-wow-{idpartida}', [FixtureWowController::class, 'create'])->name('RFixtureAgregar');


Route::get('/mytic-seleccion-wow-{idmytic}', [MyticSelectionController::class, 'index'])->name('RMytic');

Route::get('/mytic-detalle-wow-{Partida}-{Mitic}', [MyticSelectionController::class, 'create'])->name('RMyticDetalle');

Route::post('/encuentro-partida-guardada', [MyticSelectionController::class, 'update'])->name('RGuardarDetalles');


Route::get('/vista-fixture-{variable}', [VistaUsuario::class, 'index'])->name('RFixtureVista');

Route::get('/vista--miticas{variable2}', [VistaUsuario::class, 'create'])->name('RMyticVista');

Route::get('/vista-encuentro-{Partida}-{Mitic}', [VistaUsuario::class, 'store'])->name('RMyticDetalleVista');


Route::get('/PDF-WOW-MITICS-{idsala}', [PDFwow::class, 'convertirPDF'])->name('Rpdf');










