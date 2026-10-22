<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.html');
    exit;
}
$nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
$peso = floatval($_POST['peso'] ?? 0);
$altezza_cm = floatval($_POST['altezza'] ?? 0);
if ($peso <= 0 || $altezza_cm <= 0) {
    header('Location: form.html');
    exit;
}
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
?>