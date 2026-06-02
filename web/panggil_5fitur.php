<?php
function predictDiabetes($features)
{
    $pythonScript = "C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/scripts/e-nose_5fitur.py";
    $inputJson = json_encode($features);
    $command = "python \"$pythonScript\" \"$inputJson\" 2>&1";
    $output = shell_exec($command);
    $output = trim($output);
    return json_decode($output, true);
}

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $features = [
        (float) str_replace(',', '.', $_POST['glucose']),
        (float) str_replace(',', '.', $_POST['bmi']),
        (float) str_replace(',', '.', $_POST['age']),
        (float) str_replace(',', '.', $_POST['pregnancies']),
        (float) str_replace(',', '.', $_POST['pedigree'])
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
    <title>Prediksi Diabetes - 5 Fitur Terbaik</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #3498db;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background: #2980b9;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .result.diabetes {
            background: #fee;
            border: 1px solid #e74c3c;
        }

        .result.normal {
            background: #e8f5e9;
            border: 1px solid #4caf50;
        }

        .result.diabetes strong {
            color: #e74c3c;
        }

        .result.normal strong {
            color: #4caf50;
        }

        .error {
            background: #fee;
            border: 1px solid #e74c3c;
            padding: 10px;
            border-radius: 5px;
            color: #c0392b;
            margin: 15px 0;
            text-align: center;
        }

        .info {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            font-size: 12px;
            text-align: center;
            color: #666;
        }

        .nav {
            text-align: center;
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav a {
            color: #3498db;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav a:hover {
            background: #e3f2fd;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🩺 Prediksi Diabetes</h1>
        <div class="subtitle">Menggunakan 5 Fitur Terbaik (Hasil Seleksi Fitur)</div>

        <form method="POST">
            <div class="form-group">
                <label>Kadar Glukosa</label>
                <input type="number" step="any" name="glucose"
                    value="<?php echo isset($_POST['glucose']) ? htmlspecialchars($_POST['glucose']) : '148'; ?>" required>
            </div>
            <div class="form-group">
                <label>BMI</label>
                <input type="number" step="any" name="bmi"
                    value="<?php echo isset($_POST['bmi']) ? htmlspecialchars($_POST['bmi']) : '33.6'; ?>" required>
            </div>
            <div class="form-group">
                <label>Usia</label>
                <input type="number" step="any" name="age"
                    value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '50'; ?>" required>
            </div>
            <div class="form-group">
                <label>Jumlah Kehamilan (Pregnancies)</label>
                <input type="number" step="any" name="pregnancies"
                    value="<?php echo isset($_POST['pregnancies']) ? htmlspecialchars($_POST['pregnancies']) : '6'; ?>" required>
            </div>
            <div class="form-group">
                <label>Diabetes Pedigree</label>
                <input type="number" step="any" name="pedigree"
                    value="<?php echo isset($_POST['pedigree']) ? htmlspecialchars($_POST['pedigree']) : '0.627'; ?>" required>
            </div>
            <button type="submit">🔍 Prediksi</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><strong>Error:</strong><br><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($result && isset($result['class'])): ?>
            <div class="result <?php echo strtolower($result['class']); ?>">
                <strong><?php echo $result['class']; ?></strong>
            </div>
        <?php endif; ?>

        <div class="info">
            <strong>Informasi:</strong><br>
            Algoritma: SVM (Support Vector Machine)<br>
            Fitur: 5 terbaik (Glucose, BMI, Age, Pregnancies, Pedigree)<br>
            Hasil seleksi fitur berdasarkan F-Score
        </div>

        <div class="nav">
            <a href="coba_machine_learning_5fitur.php">🧪 Coba Test Cases </a>
            <a href="panggil_machine_learning.php">🔁 Kembali ke 8 Fitur </a>
        </div>
    </div>
</body>

</html>