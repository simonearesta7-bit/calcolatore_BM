<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}
$ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN_IP';
$data_ora = date('d/m/Y H:i:s');
$raw_nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$raw_peso = isset($_POST['peso']) ? $_POST['peso'] : '';
$raw_altezza = isset($_POST['altezza']) ? $_POST['altezza'] : '';
$nome = htmlspecialchars(trim($raw_nome));
$peso = floatval($raw_peso);
$altezza_cm = floatval($raw_altezza);
$nome_valido = !empty($nome);
$peso_valido = ($peso > 0);
$altezza_valida = ($altezza_cm > 0);
$dati_validi = ($nome_valido && $peso_valido && $altezza_valida);
if ($dati_validi) {
    $altezza_m = $altezza_cm / 100;
    $bmi = $peso / ($altezza_m * $altezza_m);
    $bmi_formattato = number_format($bmi, 2, '.', '');
    if ($bmi < 18.5) {
        $categoria = "SOTTOPESO";
        $classe_css = "sottopeso";
    } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
        $categoria = "NORMOPESO";
        $classe_css = "normopeso";
    } elseif ($bmi >= 25 && $bmi <= 29.9) {
        $categoria = "SOVRAPPESO";
        $classe_css = "sovrappeso";
    } else {
        $categoria = "OBESITA";
        $classe_css = "obesita";
    }
    $log_nome = $nome;
    $log_altezza = $altezza_cm;
    $log_peso = $peso;
    $log_bmi = $bmi_formattato;
    $log_class = $categoria;
} else {
    if (!empty($nome)) {
        $log_nome = $nome;
    } else {
        $log_nome = 'NOT_VALID';
    }
    $log_altezza = 'NOT_VALID';
    $log_peso = 'NOT_VALID';
    $log_bmi = 'NOT_VALID';
    $log_class = 'NOT_VALID';
}
$file_log = "log.csv";
$file = fopen($file_log, "a");
$riga_log = $data_ora . "," . $ip_address . "," . $log_nome . "," . $log_altezza . "," . $log_peso . "," . $log_bmi . "," . $log_class . "\n";
fwrite($file, $riga_log);
fclose($file);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Risultato BMI</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <div class="container" style="padding: 30px; text-align: center;">
        <h2>Risultato del Calcolo</h2>
        <?php if ($dati_validi): ?>
            <p>Ciao <strong><?php echo $nome; ?></strong>!</p>
            <p>Il tuo BMI è: <strong><?php echo $bmi_formattato; ?></strong></p>
            <p class="<?php echo $classe_css; ?>">Categoria: <strong><?php echo $categoria; ?></strong></p>
        <?php else: ?>
            <p style="color: red;">I dati inseriti non sono validi. Assicurati di inserire Nome, Peso e Altezza corretti.</p>
        <?php endif; ?>
        <br><br>
        <a href="index.html">Torna al modulo</a>
    </div>
</body>
</html>