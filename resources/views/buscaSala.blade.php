@extends('layouts/head-login-sala')


@section('Cuerpo')
<!DOCTYPE html>
<html>

<body>
  <section id="hero"></section>
  <div class="row justify-content-center">
    <div class="col-xl-6">
      <div class="card text-center">
        <div class="card-header">
         	<?php
                echo $li4;
            ?>
      </div>
      <div class="card-body">
          <h5 class="card-title">Panel de Control de Torneos Activos</h5>
        <div class="card">
          <ul class="list-group list-group-flush">
            <?php
                echo $li3;
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