<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mostra_risultato = true;
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
        $errore_messaggio = "Inserisci un nome valido e assicuratevi che peso e altezza siano numeri maggiori di zero.";
        $log_nome = !empty($nome) ? $nome : 'NOT_VALID';
        $log_altezza = 'NOT_VALID';
        $log_peso = 'NOT_VALID';
        $log_bmi = 'NOT_VALID';
        $log_class = 'NOT_VALID';
    }
    $file_log = "log.csv";
    $file = @fopen($file_log, "a");
    if ($file) {
        $riga_log = $data_ora . "," . $ip_address . "," . $log_nome . "," . $log_altezza . "," . $log_peso . "," . $log_bmi . "," . $log_class . "\n";
        @fwrite($file, $riga_log);
        @fclose($file);
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore BMI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .risultato { margin-top: 25px; padding: 15px; border-top: 2px solid #eee; text-align: center; }
        .errore { color: #d9534f; background: #fdf7f7; padding: 10px; border-radius: 4px; }
        .sottopeso { color: #17a2b8; }
        .normopeso { color: #28a745; }
        .sovrappeso { color: #ffc107; }
        .obesita { color: #dc3545; }
    </style>
</head>
<body>
<div class="container">
    <h2>Calcolatore BMI</h2>
        <form method="POST" action="index.php">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="form-group">
            <label for="peso">Peso (kg):</label>
            <input type="number" step="0.1" id="peso" name="peso" value="<?php echo htmlspecialchars($peso); ?>" required>
        </div>
        <div class="form-group">
            <label for="altezza">Altezza (cm):</label>
            <input type="number" step="1" id="altezza" name="altezza" value="<?php echo htmlspecialchars($altezza_cm); ?>" required>
        </div>
        <button type="submit">Calcola BMI</button>
    </form>
    <?php if ($mostra_risultato): ?>
        <div class="risultato">
            <?php if ($dati_validi): ?>
                <h3>Risultato per <?php echo $nome; ?></h3>
                <p>Il tuo BMI è: <strong><?php echo $bmi_formattato; ?></strong></p>
                <p class="<?php echo $classe_css; ?>">Categoria: <strong><?php echo $categoria; ?></strong></p>
            <?php else: ?>
                <p class="errore"><?php echo $errore_messaggio; ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>