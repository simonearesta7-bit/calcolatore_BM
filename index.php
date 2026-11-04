<?php
$nome = "";
$peso = "";
$altezza = "";
$err_nome = "";
$err_peso = "";
$err_altezza = "";
$mostra_risultato = false;
$bmi_formattato = "";
$categoria = "";
$classe_css = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $peso = isset($_POST['peso']) ? trim($_POST['peso']) : '';
    $altezza = isset($_POST['altezza']) ? trim($_POST['altezza']) : '';
    $peso_num = (float)$peso;
    $altezza_cm = (float)$altezza;

    if (empty($nome)) {
        $err_nome = "Valore non valido";
    }
    if ($peso_num <= 0) {
        $err_peso = "Valore non valido";
    }
    if ($altezza_cm <= 0) {
        $err_altezza = "Valore non valido";
    }
    if (empty($err_nome) && empty($err_peso) && empty($err_altezza)) {
        $mostra_risultato = true;
        $altezza_m = $altezza_cm / 100;
        $bmi = $peso_num / ($altezza_m * $altezza_m);
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
        $data_ora = date('d/m/Y H:i:s');
        $file_log = "log.csv";
        $file = @fopen($file_log, "a");
        if ($file) {
            $riga_log = $data_ora . "," . $nome . "," . $altezza_cm . "," . $peso_num . "," . $bmi_formattato . "," . $categoria . "\n";
            @fwrite($file, $riga_log);
            @fclose($file);
        }
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
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        input.input-error { border: 1px solid #d9534f; background-color: #fff0f0; }
        .error-message { color: #d9534f; font-size: 13px; margin-top: 4px; display: block; font-weight: bold; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #0056b3; }
        .risultato { margin-top: 25px; padding: 15px; border-top: 2px solid #eee; text-align: center; }
        .sottopeso { color: #17a2b8; }
        .normopeso { color: #28a745; }
        .sovrappeso { color: #ffc107; }
        .obesita { color: #dc3545; }
    </style>
</head>
<body>
<div class="container">
    <h2>Calcolatore BMI</h2>
    <form method="POST" action="index.php" novalidate>
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" 
                   class="<?php echo !empty($err_nome) ? 'input-error' : ''; ?>"
                   value="<?php echo htmlspecialchars($nome); ?>">
            <?php if (!empty($err_nome)): ?>
                <span class="error-message"><?php echo $err_nome; ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="peso">Peso (kg):</label>
            <input type="text" id="peso" name="peso" 
                   class="<?php echo !empty($err_peso) ? 'input-error' : ''; ?>"
                   value="<?php echo htmlspecialchars($peso); ?>">
            <?php if (!empty($err_peso)): ?>
                <span class="error-message"><?php echo $err_peso; ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="altezza">Altezza (cm):</label>
            <input type="text" id="altezza" name="altezza" 
                   class="<?php echo !empty($err_altezza) ? 'input-error' : ''; ?>"
                   value="<?php echo htmlspecialchars($altezza); ?>">
            <?php if (!empty($err_altezza)): ?>
                <span class="error-message"><?php echo $err_altezza; ?></span>
            <?php endif; ?>
        </div>
        <button type="submit">Calcola BMI</button>
    </form>
    <?php if ($mostra_risultato): ?>
        <div class="risultato">
            <h3>Risultato per <?php echo htmlspecialchars($nome); ?></h3>
            <p>Il tuo BMI è: <strong><?php echo $bmi_formattato; ?></strong></p>
            <p class="<?php echo $classe_css; ?>">Categoria: <strong><?php echo $categoria; ?></strong></p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>