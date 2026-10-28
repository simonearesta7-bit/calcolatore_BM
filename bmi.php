<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.html');
    exit;
}
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN_IP';
$data_ora = date('d/m/Y,H:i:s');
$raw_nome = $_POST['nome'] ?? '';
$raw_peso = $_POST['peso'] ?? '';
$raw_altezza = $_POST['altezza'] ?? '';
$nome = htmlspecialchars(trim($raw_nome));
$peso = floatval($raw_peso);
$altezza_cm = floatval($raw_altezza);
$nome_valido = !empty($nome);
$peso_valido = ($peso > 0);
$altezza_valida = ($altezza_cm > 0);

$dati_validi = ($nome_valido && $peso_valido && $altezza_valida);

if ($dati_validi) {
    $altezza_m = $altezza_cm / 100;
    $bmi = $peso / ($altezza_m ** 2);
    $bmi_formattato = number_format($bmi, 2, '.', '');
    if ($bmi < 18.5) {
        $categoria = 'SOTTOPESO';
        $classe_css = 'sottopeso';
    } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
        $categoria = 'NORMOPESO';
        $classe_css = 'normopeso';
    } elseif ($bmi >= 25 && $bmi <= 29.9) {
        $categoria = 'SOVRAPPESO';
        $classe_css = 'sovrappeso';
    } else {
        $categoria = 'OBESITÀ';
        $classe_css = 'obesita';
    }
    $log_nome = $nome;
    $log_altezza = $altezza_cm;
    $log_peso = $peso;
    $log_bmi = $bmi_formattato;
    $log_class = $categoria;

} else {
    $log_nome = $nome_valido ? $nome : 'NOT_VALID';
    $log_altezza = $altezza_valida ? $altezza_cm : 'NOT_VALID';
    $log_peso = $peso_valido ? $peso : 'NOT_VALID';
    $log_bmi = 'NOT_VALID';
    $log_class = 'NOT_VALID';
}
$file_log = "log.csv";
$file = fopen($file_log, "a");
$riga_log = $data_ora . "," . $ip_address . "," . $log_nome . "," . $log_altezza . "," . $log_peso . "," . $log_bmi . "," . $log_class . "\n";
fwrite($file, $riga_log);
fclose($file);
if (!$dati_validi) {
    header('Location: form.html');
    exit;
}
?>
