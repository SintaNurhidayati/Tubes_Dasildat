<?php
function predictDiabetes5Fitur($features)
{
    // Path ke script Python
    $pythonScript = "C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/scripts/e-nose_5fitur.py";

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

$test_cases = [
    [
        'name' => 'Pasien Risiko Tinggi Diabetes',
        'features' => [148, 33.6, 50, 6, 0.627]
    ],
    [
        'name' => 'Pasien Sehat (Normal)',
        'features' => [85, 26.6, 31, 1, 0.351]
    ],
    [
        'name' => 'Pasien Risiko Tinggi (Kasus 2)',
        'features' => [183, 23.3, 32, 8, 0.672]
    ],
    [
        'name' => 'Pasien Muda Sehat',
        'features' => [89, 28.1, 21, 0, 0.167]
    ],
    [
        'name' => 'Pasien dengan BMI Tinggi',
        'features' => [120, 35.0, 45, 3, 0.500]
    ],
    [
        'name' => 'Pasien Usia Lanjut',
        'features' => [130, 32.0, 65, 5, 0.800]
    ]
];

// Lakukan prediksi untuk semua test cases
$results = [];
foreach ($test_cases as $case) {
    $result = predictDiabetes5Fitur($case['features']);
    $results[] = [
        'name' => $case['name'],
        'features' => $case['features'],
        'class' => isset($result['class']) ? $result['class'] : 'Error'
    ];
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Coba Machine Learning - 5 Fitur Terbaik</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #3498db;
            color: white;
        }

        .diabetes {
            background: #fee;
            color: #e74c3c;
            font-weight: bold;
        }

        .normal {
            background: #e8f5e9;
            color: #4caf50;
            font-weight: bold;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background: #2980b9;
        }

        .info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🧪 Data Coba Machine Learning Model </h1>
        <div class="subtitle">Testing berbagai skenario prediksi diabetes</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Skenario</th>
                    <th>Data (Glucose, BMI, Age, Pregnancies, Pedigree)</th>
                    <th>Hasil Prediksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $i => $r): ?>
                    <tr class="<?php echo strtolower($r['class']); ?>">
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $r['name']; ?></td>
                        <td><?php echo implode(', ', $r['features']); ?></td>
                        <td><?php echo $r['class']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="btn-group">
            <a href="panggil_5fitur.php" class="btn-back">Kembali ke Form Prediksi</a>
        </div>

        <div class="info">
            <strong>📊 Informasi Model 5 Fitur:</strong><br>
            • Algoritma: SVM (Support Vector Machine)<br>
            • Fitur: 5 terbaik dari seleksi fitur (F-Score)<br>
            • Total test cases: <?php echo count($results); ?> skenario<br>
            • Hasil prediksi ditampilkan dalam tabel untuk perbandingan
        </div>
    </div>
</body>

</html>