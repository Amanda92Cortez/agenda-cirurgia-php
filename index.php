<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"/>

    <title>Agenda de Cirurgia</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  </head>
  <body>
    <header class="container">
      <nav class="navbar bglight">
        <div class="container-fluid">
          <img src="./img/logo-h10j.png" alt="Logo do Hospital 10 de Julho">
          <h2>Agenda de Cirurgia</h2>
          <form method="POST" class="d-flex" role="search" onsubmit="return false">
            <input class="form-control me-2" type="date" id="dataFiltro" onchange="carregarDados()" />
          </form>
        </div>
      </nav>
    </header>

    <section >
      <table class="table" id="tabelaDados" border="1">
        <!-- Os dados da tabela serÃ£o inseridos aqui -->
      </table>
    </section>


    <!-- RodapÃ© -->
  <section class="footer">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <ul class="nav col-md-4 justify-content-end">
        <li class="nav-item"><a href="https://www.hospital10dejulho.com.br/" class="nav-link px-2 text-body-secondary texto-verde" target="_blank">Hospital 10 de Julho</a></li>
        <li class="nav-item"><a href="https://www.hospital10dejulho.com.br/centro-cirurgico/" class="nav-link px-2 text-body-secondary texto-verde" target="_blank">Centro Cirurgico</a></li>
      </ul>
      
      <p class="col-md-4 mb-0 text-body-secondary texto-menor">"Desenvolvido pelo setor T.I. Hospital"</p>
    </footer>
  </section>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>

    <script>

	const today = new Date();
      	const data = today.toISOString();
	const data_atual = data.substring(0, 10);
	var dataFiltro = document.getElementById("dataFiltro").value;

	if(dataFiltro.length == 0){
		document.getElementById("dataFiltro").value = data_atual;
		carregarDados(data_atual);
	}

      function carregarDados(data_atual) {
	if(data_atual === undefined){
		dataFiltro = document.getElementById("dataFiltro").value;
	}else{
		dataFiltro = data_atual;
	}
	
        // Faz uma requisiÃ§Ã£o Ajax para o script PHP com o filtro de data
        $.ajax({
          url: "db_config.php",
          type: "GET",
          data: { data_filtro: dataFiltro },
          dataType: "json",
          success: function (data) {
            // Limpa a tabela
            $("#tabelaDados").html("");

            // Adiciona os cabeÃ§alhos da tabela
            $("#tabelaDados").append(
              '<tr class="' + 'border border-black p-2 mb-2 text-center">' +
                '<th scope="col">Horário</th>' +
                '<th scope="col">Hemo</th>' + 
                '<th scope="col">Sala 01</th>' + 
                '<th scope="col">Sala 02</th>' + 
                '<th scope="col">Sala 03</th>' + 
                '<th scope="col">Sala 04</th>' + 
                '<th scope="col">Sala 05</th>' + 
              '</tr>'
            );

            
            
            // Adiciona os dados Ã  tabela
            $.each(data, function (index, row) {
              $("#tabelaDados").append(
		'<tr class="' + 'border border-black p-2 mb-2 text-center">' +                   
	          '<td>' + row.HR_INICIO +'</td>' + 
                  '<td class="' + (row.HEMO === "RESERVADO" ? "reservado" : "livre") + ' border">' + row.HEMO + '</td>'+ 
                  '<td class="' + (row.SALA_01 === "RESERVADO" ? "reservado" : "livre") + ' border">' + row.SALA_01 + '</td>'+ 
                  '<td class="' + (row.SALA_02 === "RESERVADO" ? "reservado" : "livre") + ' border">' + row.SALA_02 + '</td>'+ 
                  '<td class="' + (row.SALA_03 === "RESERVADO" ? "reservado" : "livre") + ' border">' + row.SALA_03 + '</td>'+ 
                  '<td class="' + (row.SALA_04 === "RESERVADO" ? "reservado" : "livre") + ' border">' + row.SALA_04 + '</td>'+ 
                  '<td class="' + (row.SALA_05 === "RESERVADO" ? "reservado" : "livre") + ' border">' + row.SALA_05 + '</td>'+ 
                '</tr>'
              );
            });
          },
        });
      }
    </script>
  </body>
</html>
