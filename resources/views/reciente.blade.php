@extends('layouts/head-login-sala')


@section('Cuerpo')
<!DOCTYPE html>
<html>

<body>
  <section id="hero"></section>
  <div class="row">
    <div class="col-xl-6">
      <div class="card text-center">
        <div class="card-header">
          World Of Warcraft Mythic +
      </div>
      <div class="card-body">
          <h5 class="card-title">Panel de Control de Torneos Activos</h5>
        <div class="card">
          <ul class="list-group list-group-flush">
            <?php
                echo $li6;
            ?>
          </ul>
        </div>
      </div>
      <div class="card-footer text-muted">
          © Copyright GamingUmpires. All Rights Reserved
      </div>
    </div>
    </div>
    <div class="col-xl-6">
      <div class="card text-center">
        <div class="card-header">
          DOTA 2
      </div>
      <div class="card-body">
          <h5 class="card-title">Panel de Control de Torneos Activos</h5>
        <div class="card">
          <ul class="list-group list-group-flush">
            <?php
                echo $li5;
            ?>
          </ul>
        </div>
      </div>
      <div class="card-footer text-muted">
          © Copyright GamingUmpires. All Rights Reserved
      </div>
    </div>
    </div>
  </div>

</body>
</html>
@endsection