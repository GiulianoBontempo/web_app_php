<?php 
include "../inc/dbinfo.inc";

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);

function AddEmployee($connection, $name, $address) {
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);

    $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$n', '$a');";

    if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}

function VerifyEmployeesTable($connection, $dbName) {
    if(!TableExists("EMPLOYEES", $connection, $dbName)) {
        $query = "CREATE TABLE EMPLOYEES (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(45),
            ADDRESS VARCHAR(90)
        )";

        if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
    }
}

function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    if(mysqli_num_rows($checktable) > 0) return true;

    return false;
}

function AddAluno($connection, $nome, $sobrenome, $media, $data_nascimento) {
    $n = mysqli_real_escape_string($connection, $nome);
    $s = mysqli_real_escape_string($connection, $sobrenome);
    $m = mysqli_real_escape_string($connection, $media);
    $d = mysqli_real_escape_string($connection, $data_nascimento);

    $query = "INSERT INTO ALUNOS (NOME, SOBRENOME, MEDIA, DATA_NASCIMENTO) VALUES ('$n', '$s', '$m', '$d');";

    if(!mysqli_query($connection, $query)) echo("<p>Error adding aluno data.</p>");
}

function VerifyAlunosTable($connection, $dbName) {
    if(!TableExists("ALUNOS", $connection, $dbName)) {
        $query = "CREATE TABLE ALUNOS (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NOME VARCHAR(45),
            SOBRENOME VARCHAR(45),
            MEDIA DECIMAL(5,2),
            DATA_NASCIMENTO DATE
        )";

        if(!mysqli_query($connection, $query)) echo("<p>Error creating alunos table.</p>");
    }
}

VerifyEmployeesTable($connection, DB_DATABASE);
VerifyAlunosTable($connection, DB_DATABASE);

$employee_name = htmlentities($_POST['NAME']);
$employee_address = htmlentities($_POST['ADDRESS']);
$aluno_nome = htmlentities($_POST['NOME']);
$aluno_sobrenome = htmlentities($_POST['SOBRENOME']);
$aluno_media = htmlentities($_POST['MEDIA']);
$aluno_data_nascimento = htmlentities($_POST['DATA_NASCIMENTO']);

if (strlen($employee_name) || strlen($employee_address)) {
    AddEmployee($connection, $employee_name, $employee_address);
}

if (strlen($aluno_nome) || strlen($aluno_sobrenome) || strlen($aluno_media) || strlen($aluno_data_nascimento)) {
    AddAluno($connection, $aluno_nome, $aluno_sobrenome, $aluno_media, $aluno_data_nascimento);
}
?>
<html>
<body>
<h1>Sample page</h1>

<!-- Employee form -->
<h4>Add employee</h4>
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
    </tr>
    <tr>
      <td><input type="text" name="NAME" maxlength="45" size="30" /></td>
      <td><input type="text" name="ADDRESS" maxlength="90" size="60" /></td>
      <td><input type="submit" value="Add Data" /></td>
    </tr>
  </table>
</form>

<!-- Aluno form -->
<h4>Adicionar aluno</h4>
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NOME</td>
      <td>SOBRENOME</td>
      <td>MEDIA</td>
      <td>DATA NASCIMENTO</td>
    </tr>
    <tr>
      <td><input type="text" name="NOME" maxlength="45" size="30" /></td>
      <td><input type="text" name="SOBRENOME" maxlength="45" size="30" /></td>
      <td><input type="number" name="MEDIA" step="0.01" size="10" /></td>
      <td><input type="date" name="DATA_NASCIMENTO" /></td>
      <td><input type="submit" value="Add Aluno" /></td>
    </tr>
  </table>
</form>

<!-- Display EMPLOYEES table data. -->
<h4>Employees</h4>
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>ADDRESS</td>
  </tr>
<?php
$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");
while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
  echo "</tr>";
}
?>
</table>

<!-- Display ALUNOS table data. -->
<h4>Alunos</h4>
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NOME</td>
    <td>SOBRENOME</td>
    <td>MEDIA</td>
    <td>DATA NASCIMENTO</td>
  </tr>
<?php
$result = mysqli_query($connection, "SELECT * FROM ALUNOS");
while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>";
  echo "</tr>";
}
?>
</table>

<?php
mysqli_free_result($result);
mysqli_close($connection);
?>
</body>
</html>
