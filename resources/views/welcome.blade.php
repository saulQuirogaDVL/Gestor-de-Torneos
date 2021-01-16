@extends('layouts/head')


@section('Cuerpo')
<!DOCTYPE html>
<html>

<body>
  <section id="hero">
    <div class="hero-container">
      <h1>Bienvenido a Gaming Umpires</h1>
      <h2>Plataforma para crear y administrar tus torneos de Videojuegos Online!!</h2>
<h2>Código de Torneo</h2>
      <form action="{{ route('buscaTorneo')}}" method="post" class="form-inline">
        @csrf
                <input type="text" placeholder="Buscar" name="searchText" class="form-control mr-sm-2" required maxlength="15" minlength="1" pattern="[A-Za-z0-9]+">
                <select class="form-control" id="selectGame" name="selectGame">
                    <option class="dropdown-item" value="D2">Dota 2</option>
                    <option class="dropdown-item" value="WOW">WOW</option>
                </select>
                <button type="submit" class="btn btn-dark">Buscar</button>
          </form>
    </div>
  </section>
  <main id="main">
    <section id="about">
      <div class="container">
        <div class="row about-container">

          <div class="col-lg-6 content order-lg-1 order-2">
            <h2 class="title">Acerca de Nosotros</h2>
            <p>
              Gaming Umpire es una plataforma dedicada a aquellos afinicionados a los juegos Dota 2 y WoW que les hustaría tener un lugar donde poder crear y gestionar sus propios torneos Online.
              Pudiendo así crear salas, reglas, formación de fase de grupos, registrar las partidas y
              además de mostrar los ganadores y sus premios si así se configuró.
            </p>



            <div class="icon-box wow fadeInUp" data-wow-delay="0.2s">
              <div class="icon"><i class="fa fa-photo"></i></div>
              <h4 class="title"><a href="">Interfaz Amigable</a></h4>
              <p class="description">
                Como árbitro de tu torneo podrás ver que la página es muy intuitiva con relación a los Videojuegos mostrando imágenes que ayuden a mostrar las acciones de una partida.
              </p>
            </div>

            <div class="icon-box wow fadeInUp" data-wow-delay="0.4s">
              <div class="icon"><i class="fa fa-bar-chart"></i></div>
              <h4 class="title"><a href="">Sistema de creación de Fase de Grupos</a></h4>
              <p class="description">Podrás ver cuán fácil es introducir a los participantes y que
              el sistema haga automáticamente un fixture con detalles de encuentro y avances del torneo.</p>
            </div>

          </div>

          <div class="col-lg-6 background order-lg-2 order-1 ">

          </div>
        </div>

      </div>
    </section>

    <section id="services">
      <div class="container wow fadeIn">
        <div class="section-header">
          <h3 class="section-title">Servicios</h3>
          <p class="section-description">Gaming Umpires permite a sus usuarios diferentes servicios a traves de los cuales
          podra crear y gestionar torneos de distintos videojuegos</p>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-gamepad"></i></a></div>
              <h4 class="title"><a href="">Creacion de Salas</a></h4>
              <p class="description">Permite la creacion de salas, se podra elejir las reglas, tipo de elminacion, gestion de equipos y los modos de juegos de acuerdo al videojuego seleccionado</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-cog"></i></a></div>
              <h4 class="title"><a href="">Gestion de Partidas</a></h4>
              <p class="description">Podra editar los detalles de la partida y colocar la informacion de manera en la cual
              la informacion respecto a los juegos sera visualmente comoda para los interesados</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-trophy"></i></a></div>
              <h4 class="title"><a href="">Desarrollo del Torneo</a></h4>
              <p class="description">Se podra observar el desarrollo de la partida a traves de un fixture
              que se autogenera y actualiza de acuerdo a los detalles de las partidas</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-photo"></i></a></div>
              <h4 class="title"><a href="">Presentacion de las partidas</a></h4>
              <p class="description">En los detalles de cada partida se presentara de manera comoda y agradable a la vista
              los resultados finales </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-download"></i></a></div>
              <h4 class="title"><a href="">Torneos Descargables</a></h4>
              <p class="description">Podra descargar todo el progreso del torneo en un archivos .xlsx o PDF
              para visualizar el torneo sin conexion a internet</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-usd"></i></a></div>
              <h4 class="title"><a href="">Gratuito</a></h4>
              <p class="description">Para el uso de los servicios no se requiere una tarjeta de credito o algun costo adicional
              podra disfrutar de todos los servicios de la pagina totalmente gratis</p>
            </div>
          </div>
        </div>

      </div>
    </section>

    <section id="team">
      <div class="container wow fadeInUp">
        <div class="section-header">
          <h3 class="section-title">Equipo</h3>
          <p class="section-description">Estudiantes del sexto Semestre de la Universidad Privada
          del Valle</p>
        </div>
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="member">
              <div class="pic"><img src="imagenes/yo.jpg" alt=""></div>
              <h4>Juan Manuel Condori Peralta</h4>
              <div class="social">
                <a href=""><i class="fa fa-facebook"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="member">
              <div class="pic"><img src="imagenes/andy.jpeg" alt=""></div>
              <h4>Andy Henry Alejandro Cores Andrade</h4><br>

  <a href="{{ Route("Rpdf",18)}}"><button>pdf</button></a>
              <div class="social">
                <a href=""><i class="fa fa-facebook"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="member">
              <div class="pic"><img src="imagenes/unknow.jpg" alt=""></div>
              <h4>Saul Imanol Quiroga Castrillo</h4>
              <div class="social">
                <a href=""><i class="fa fa-facebook"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer id="footer">
    <div class="footer-top">
      <div class="container">

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>GamingUmpires</strong>. All Rights Reserved
      </div>
      <div class="credits">
      </div>
    </div>
  </footer>
</body>
</html>
@endsection