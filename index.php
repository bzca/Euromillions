<?php
  $ch = curl_init("https://nunofcguerreiro.com/api-euromillions-json?result=all");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  $todosSorteios = curl_exec($ch);
  curl_close($ch);


  //include_once('dBug.php');

  $decodedTodosSorteios = json_decode($todosSorteios);
  $todosNumeros = array();
  $todasEstrelas = array();
  $todasDatas = array();

  foreach ($decodedTodosSorteios->drawns as $index => $item) {
    $numerosSorteio = explode(' ', $item->numbers);
    $estrelasSorteio = explode(' ', $item->stars);
    //$datasSorteio = explode (' ', $item->date);

    foreach ($numerosSorteio as $num) {
      array_push($todosNumeros, $num);
    }

    foreach ($estrelasSorteio as $estrela) {
      array_push($todasEstrelas, $estrela);
    }

    array_push($todasDatas, $item->date);
  }

  $ocurNumeros = array_count_values($todosNumeros);
  ksort($ocurNumeros);
  $ocurEstrelas = array_count_values($todasEstrelas);
  ksort($ocurEstrelas);

  $decodedSorteioData = array(); 
  $numerosDataSorteio = array();
  $estrelasDataSorteio = array();

  if(isset($_GET['data'])){
    $dataSelecionada = $_GET['data'];
    $ch = curl_init("https://nunofcguerreiro.com/api-euromillions-json?result=".$dataSelecionada);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $sorteioData = curl_exec($ch);
    curl_close($ch);
    $decodedSorteioData = json_decode($sorteioData);
    $numerosDataSorteio = explode(' ', $decodedSorteioData->drawns[0]->numbers);
    $estrelasDataSorteio = explode(' ', $decodedSorteioData->drawns[0]->stars);
  }
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
      <!--[if lt IE 8]>
          <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
      <![endif]-->
      <div class="empty-space-64"></div>

      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <h1>Euromilhões</h1>
            <h4 id="chave-random">( chave gerada )</h4>
            <div class="numeros-container" id="numeros-container">
              <div class="row">
                <div class="div-numero"></div>
                <div class="div-numero"></div>
                <div class="div-numero"></div>
                <div class="div-numero"></div>
                <div class="div-numero"></div>
              </div>
            </div>
            <div class="estrelas-container" id="estrelas-container">
              <div class="row">
                <div class="div-estrela"></div>
                <div class="div-estrela"></div>
              </div>
            </div>
            <div id="btn-container">
              <button class="btn btn-default btn-gerador">Gerar chave</button>
            </div>
            <div class="empty-space-16"></div>
            <div id="radiosDiv" style="width: 100%; text-align:center">
              <label class="radio-inline">
                <input type="radio" name="inlineRadioOptions" id="random" value="random" checked> Aleatória
              </label>
              <label class="radio-inline">
                <input type="radio" name="inlineRadioOptions" id="mostOut" value="mostOut"> Números mais saídos
              </label>
              <label class="radio-inline">
                <input type="radio" name="inlineRadioOptions" id="lessOut" value="lessOut"> Números menos saídos
              </label>
            </div>
          </div>

          <div class="col-xs-12 col-sm-6" id="ult-chave">
              <h4>Último sorteio</h4>
              <?php 
                $ch = curl_init("https://nunofcguerreiro.com/api-euromillions-json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $ultSorteio = curl_exec($ch);
                curl_close($ch);
                $decodedUltSorteio = json_decode($ultSorteio);
                $dataUltSorteio = $decodedUltSorteio->drawns[0]->date;
                $numerosUltSorteio = explode(' ', $decodedUltSorteio->drawns[0]->numbers);
                $estrelasUltSorteio = explode(' ', $decodedUltSorteio->drawns[0]->stars);
              ?>
              <h6>( <?php echo $dataUltSorteio ?> )</h6>
              <div class="numeros-container">
                <div class="row">
                  <?php 
                    foreach ($numerosUltSorteio as $num) {
                      echo '<div class="div-numero ult-sorteio">'.$num.'</div>';
                    } 
                  ?>
                </div>
              </div>
              <div class="estrelas-container">
                <div class="row">
                  <?php 
                    foreach ($estrelasUltSorteio as $estr) {
                      echo '<div class="div-estrela ult-sorteio">'.$estr.'</div>';
                    } 
                  ?>
                </div>
              </div>
              
              <h5>Consultar chave em:</h5>
              <div class="form-group">
                <select id="select-data">
                  <option value="default"></option>
                <?php
                  foreach ($todasDatas as $data) {
                    echo '<option value="'.$data.'">'.$data.'</option>';
                  }
                ?>
                </select>
                <a class="btn btn-default" id="btnConsultar">Consultar</a>
              </div>
              <?php 
                if(!empty($decodedSorteioData) && $_GET['data'] != 'default'){
              ?>
                  <div class="numeros-container">
                    <div class="row">
                      <?php 
                        foreach ($numerosDataSorteio as $num) {
                          echo '<div class="div-numero ult-sorteio">'.$num.'</div>';
                        } 
                      ?>
                    </div>
                  </div>
                  <div class="estrelas-container">
                    <div class="row">
                      <?php 
                        foreach ($estrelasDataSorteio as $estr) {
                          echo '<div class="div-estrela ult-sorteio">'.$estr.'</div>';
                        } 
                      ?>
                    </div>
                  </div>
              <?php
                }
              ?>
          </div>
        </div>
      </div>

      <div class="empty-space-64"></div>

      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-12" id="mais-saidos">
            <h4>Números mais saídos</h4>
            <h6>( Desde: 13-02-2004 )</h6>
            <h5>Números</h5>
            <table class="table table-condensed table-hover tabela-num">
              <?php  
                $inc = 0;
                for ($i=1; $i <6 ; $i++) {
                  echo '<thead class="blue-grey lighten-4">
                          <tr>'; 
                  for ($j=1; $j < 11; $j++) {
                      $num = $j + $inc;
                      echo '<th>'.$num.'</th>';
                  }
                  echo '  </tr>
                        </thead>';

                  for ($z=1; $z < 11 ; $z++) {
                    $num = $z + $inc;
                    echo '<td>'.$ocurNumeros[$num].'</td>';
                  }
                  $inc += 10;
                }
              ?>
            </table>

            <h5>Estrelas</h5>
            <table class="table table-condensed table-hover tabela-estr">
              <?php  
                $inc = 0;
                for ($i=1; $i <3 ; $i++) {
                  echo '<thead class="blue-grey lighten-4">
                          <tr>'; 
                  for ($j=1; $j < 7; $j++) {
                      $num = $j + $inc;
                      echo '<th>'.$num.'</th>';
                  }
                  echo '  </tr>
                        </thead>';

                  for ($z=1; $z < 7 ; $z++) {
                    $num = $z + $inc;
                    echo '<td>'.$ocurEstrelas[$num].'</td>';
                  }
                  $inc += 6;
                }
              ?>
            </table>
          </div>   
        </div>
      </div>

        
      <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>
      <script src="js/vendor/bootstrap.min.js"></script>
      <!-- <script src="js/main.js"></script> -->

      <script>
        function ordenaNumeros(a,b) {
            return a - b;
        }

        function gera(){
          var numeros = [];
          for (var i = 1; i <= 50; i++) {
            numeros.push(i);
          }
          numeros = shuffle(numeros);

          var numeros5 = [];
          for (var i = 0; i < 5; i++) {
            numeros5.push(numeros[i]);
          }
          numeros5.sort(ordenaNumeros);


          var estrelas = [];
          for (var i = 1; i <= 12; i++) {
            estrelas.push(i);
          }
          estrelas = shuffle(estrelas);

          var estrelas2 = [];
          for (var i = 0; i < 2; i++) {
            estrelas2.push(estrelas[i]);
          }
          estrelas2.sort(ordenaNumeros);

          imprime(numeros5, estrelas2);
        }

        function imprime(numeros, estrelas){
          $.each( numeros, function( index, value ){
              $('#numeros-container .row div:nth-of-type('+( index + 1)+')').html("<span>" + value + "</span>");
          });

          $.each( estrelas, function( index, value ){
              $('#estrelas-container .row div:nth-of-type('+( index + 1)+')').html("<span>" + value + "</span>");
          });  
        }

        function shuffle(o) {
          for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
          return o;
        };

        //geraEImprime();

        $('.btn-gerador').click(function(){
          if( $('#random').is(':checked') ){
            gera();
          }else if( $('#mostOut').is(':checked') ){
            MaisSaidos();
          }else if( $('#lessOut').is(':checked') ){
            MenosSaidos();
          }
        });

        $('#select-data').change(function(){
          var dataSorteioSelect = $(this).val();
          var href = '?data=' + dataSorteioSelect;
          $('#btnConsultar').attr('href', href);
        });

        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };

        var search = getUrlParameter('data');
        if (search != null){
            $("#select-data option[value='"+search+"']").attr("selected", "selected");
        }
        
        
        var arrNumeros = $.map(JSON.parse('<?php echo json_encode($ocurNumeros); ?>'), function(el) { return el });
        var arrEstrelas = $.map(JSON.parse('<?php echo json_encode($ocurEstrelas); ?>'), function(el) { return el });

        var NumMaisSaidos = [];
        var EstrelasMaisSaidas = [];
        var helpArr1 = [];
        var helpArr3 = [];

        var NumMenosSaidos = [];
        var EstrelasMenosSaidas = [];
        var helpArr2 = [];
        var helpArr4 = [];

        var arrNumeros2 = [];
        arrNumeros2[0] = null;
        for(var i = 0; i < arrNumeros.length; i++ ){
          arrNumeros2.push(arrNumeros[i]);
        }

        var arrEstrelas2 = [];
        arrEstrelas2[0] = null;
        for(var i = 0; i < arrEstrelas.length; i++){
          arrEstrelas2.push(arrEstrelas[i]);
        }

        arrNumeros.sort(function(a,b) {
          if (a < b) { return 1; }
          else if (a == b) { return 0; }
          else { return -1; }
        });

        arrEstrelas.sort(function(a,b) {
          if (a < b) { return 1; }
          else if (a == b) { return 0; }
          else { return -1; }
        });

        function MaisSaidos(){
          if(helpArr1.length == 0){
            for(var i=0; i<50; i++){
              if(helpArr1.indexOf(arrNumeros2.indexOf(arrNumeros[i])) === -1)
                helpArr1.push(arrNumeros2.indexOf(arrNumeros[i]));
            }
            NumMaisSaidos = helpArr1.slice(0,5);
            NumMaisSaidos.sort(ordenaNumeros);

            for(var i=0; i<12; i++){
              if(helpArr3.indexOf(arrEstrelas2.indexOf(arrEstrelas[i])) === -1)
                helpArr3.push(arrEstrelas2.indexOf(arrEstrelas[i]));
            }

            EstrelasMaisSaidas = helpArr3.slice(0,2);
            EstrelasMaisSaidas.sort(ordenaNumeros);
          }
          
          imprime(NumMaisSaidos, EstrelasMaisSaidas);
        }

        function MenosSaidos(){
          if(helpArr2.length == 0){
            for(var j=49; j>=0; --j){
              if(helpArr2.indexOf(arrNumeros2.indexOf(arrNumeros[j])) === -1)
                helpArr2.push(arrNumeros2.indexOf(arrNumeros[j]));
            }
            NumMenosSaidos= helpArr2.slice(0,5);
            NumMenosSaidos.sort(ordenaNumeros);

            for(var j=11; j>=0; --j){
              if(helpArr4.indexOf(arrEstrelas2.indexOf(arrEstrelas[j])) === -1)
                helpArr4.push(arrEstrelas2.indexOf(arrEstrelas[j]));
            }
            EstrelasMenosSaidas = helpArr4.slice(0,2);
            EstrelasMenosSaidas.sort(ordenaNumeros);
          }

          imprime(NumMenosSaidos, EstrelasMenosSaidas);
        }
      </script>
    </body>
</html>
