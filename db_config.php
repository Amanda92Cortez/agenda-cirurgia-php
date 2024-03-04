<?php

// Informações de conexão com o banco de dados Oracle
$hostname = "IP_SERVIDOR"; // substitua pelo nome do host do seu banco de dados Oracle
$username = "USUARIO_DB";
$password = "SENHA_DB";
$database = "DATABASE_DB";

// Tenta estabelecer uma conexão com o banco de dados Oracle
$conn = oci_connect($username, $password, "//" . $hostname . "/" . $database);

if (!$conn) {
  $e = oci_error();
  trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Verifica se foi enviado um filtro de data via GET
if (isset($_GET['data_filtro'])) {
  $dataFiltro = $_GET['data_filtro'];
  // Aqui você pode fazer ajustes conforme o formato da data no seu banco de dados
  $dataFiltro = date("d-m-Y", strtotime($dataFiltro));
  $query = "SELECT HR_INICIO, DT_AGENDA,
  CASE WHEN HEMO = 0 THEN 'LIVRE' ELSE 'RESERVADO' END HEMO,
  CASE WHEN SALA_01 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_01,
  CASE WHEN SALA_02 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_02,
  CASE WHEN SALA_03 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_03,
  CASE WHEN SALA_04 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_04,
  CASE WHEN SALA_05 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_05
  FROM (SELECT DS_SALA, HR_INICIO, CD_PESSOA_FISICA, DT_AGENDA
          FROM TASY.TABELA A
         WHERE A.DT_AGENDA = TO_DATE(:dataFiltro, 'DD-MM-YYYY')
        )
PIVOT(COUNT(CD_PESSOA_FISICA)
   FOR DS_SALA IN('HEMO' AS HEMO,
                  'SALA 01' AS SALA_01,
                  'SALA 02' AS SALA_02,
                  'SALA 03' AS SALA_03,
                  'SALA 04' AS SALA_04,
                  'SALA 05' AS SALA_05))
 ORDER BY HR_INICIO";

  $stid = oci_parse($conn, $query);
  oci_bind_by_name($stid, ":dataFiltro", $dataFiltro);
} else {
  // Se não houver filtro, executa a consulta sem restrições de data
  $query = "SELECT HR_INICIO, DT_AGENDA,
  CASE WHEN HEMO = 0 THEN 'LIVRE' ELSE 'RESERVADO' END HEMO,
  CASE WHEN SALA_01 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_01,
  CASE WHEN SALA_02 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_02,
  CASE WHEN SALA_03 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_03,
  CASE WHEN SALA_04 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_04,
  CASE WHEN SALA_05 = 0 THEN 'LIVRE' ELSE 'RESERVADO' END SALA_05
  FROM (SELECT DS_SALA, HR_INICIO, CD_PESSOA_FISICA, DT_AGENDA
          FROM TASY.TABELA A
         WHERE A.DT_AGENDA = TRUNC(SYSDATE)
        )
PIVOT(COUNT(CD_PESSOA_FISICA)
   FOR DS_SALA IN('HEMO' AS HEMO,
                  'SALA 01' AS SALA_01,
                  'SALA 02' AS SALA_02,
                  'SALA 03' AS SALA_03,
                  'SALA 04' AS SALA_04,
                  'SALA 05' AS SALA_05))
 ORDER BY HR_INICIO";
  $stid = oci_parse($conn, $query);
}

oci_execute($stid);

// Prepara os dados para enviar ao frontend
$data = array();
while ($row = oci_fetch_assoc($stid)) {
  $data[] = $row;
}

// Fecha a conexão com o banco de dados Oracle
oci_free_statement($stid);
oci_close($conn);

// Envia os dados em formato JSON para o frontend
echo json_encode($data);

?>
