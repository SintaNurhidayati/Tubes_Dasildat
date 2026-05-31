<?php

function predictDiabetes($features) {
    // Path ke script Python
    $pythonScript = "C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/scripts/e-nose.py";
    
    // Konversi features ke JSON
    $inputJson = json_encode($features);
    
    // Jalankan Python
    $command = "python \"$pythonScript\" \"$inputJson\" 2>&1";
    $output = shell_exec($command);
    
    // Bersihkan output
    $output = trim($output);
    
    // Parse JSON
    $result = json_decode($output, true);
    
    return $result;
}

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $pregnancies = str_replace(',', '.', $_POST['pregnancies']);
    $glucose = str_replace(',', '.', $_POST['glucose']);
    $blood_pressure = str_replace(',', '.', $_POST['blood_pressure']);
    $skin_thickness = str_replace(',', '.', $_POST['skin_thickness']);
    $insulin = str_replace(',', '.', $_POST['insulin']);
    $bmi = str_replace(',', '.', $_POST['bmi']);
    $diabetes_pedigree = str_replace(',', '.', $_POST['diabetes_pedigree']);
    $age = str_replace(',', '.', $_POST['age']);
    
    $features = [
        (float)$pregnancies,
        (float)$glucose,
        (float)$blood_pressure,
        (float)$skin_thickness,
        (float)$insulin,
        (float)$bmi,
        (float)$diabetes_pedigree,
        (float)$age
    ];
    
    $result = predictDiabetes($features);
    
    if (!isset($result['class'])) {
        $error = "Prediksi gagal. " . json_encode($result);
        $result = null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Prediksi Diabetes</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #2c3e50; }
        .subtitle { text-align: center; color: #7f8c8d; margin-bottom: 20px; font-size: 14px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input:focus { outline: none; border-color: #3498db; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        button:hover { background: #2980b9; }
        .result { margin-top: 20px; padding: 15px; border-radius: 5px; text-align: center; }
        .result.diabetes { background: #fee; border: 1px solid #e74c3c; }
        .result.normal { background: #e8f5e9; border: 1px solid #4caf50; }
        .result.diabetes strong { color: #e74c3c; }
        .result.normal strong { color: #4caf50; }
        .error { background: #fee; border: 1px solid #e74c3c; padding: 10px; border-radius: 5px; color: #c0392b; margin: 15px 0; text-align: center; }
        .info { margin-top: 20px; padding: 10px; background: #f8f9fa; font-size: 12px; text-align: center; color: #666; }
        .nav { text-align: center; margin-top: 15px; }
        .nav a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🩺 Prediksi Diabetes</h1>
        <div class="subtitle">Masukkan data pasien untuk prediksi</div>
        
        <form method="POST">
            <div class="form-group">
                <label>📊 Jumlah Kehamilan</label>
                <input type="number" step="any" name="pregnancies" value="6" required>
            </div>
            <div class="form-group">
                <label>🩸 Kadar Glukosa</label>
                <input type="number" step="any" name="glucose" value="148" required>
            </div>
            <div class="form-group">
                <label>❤️ Tekanan Darah</label>
                <input type="number" step="any" name="blood_pressure" value="72" required>
            </div>
            <div class="form-group">
                <label>📏 Ketebalan Kulit</label>
                <input type="number" step="any" name="skin_thickness" value="35" required>
            </div>
            <div class="form-group">
                <label>💉 Insulin</label>
                <input type="number" step="any" name="insulin" value="0" required>
            </div>
            <div class="form-group">
                <label>⚖️ BMI</label>
                <input type="number" step="any" name="bmi" value="33.6" required>
            </div>
            <div class="form-group">
                <label>📈 Diabetes Pedigree</label>
                <input type="number" step="any" name="diabetes_pedigree" value="0.627" required>
            </div>
            <div class="form-group">
                <label>🎂 Usia (Tahun)</label>
                <input type="number" step="any" name="age" value="50" required>
            </div>
            <button type="submit">🔍 Prediksi Sekarang</button>
        </form>
        
        <?php if ($error): ?>
            <div class="error">
                <strong>⚠️ Error:</strong><br><?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($result && isset($result['class'])): ?>
            <div class="result <?php echo strtolower($result['class']); ?>">
                <strong><?php echo $result['class']; ?></strong>
            </div>
        <?php endif; ?>
        
        <div class="info">
            Model: SVM (Support Vector Machine) | Akurasi: ~77%
        </div>
        
        <div class="nav">
            <a href="coba_machine_learning.php">Coba Multiple Test Cases</a>
        </div>
    </div>
</body>
</html>