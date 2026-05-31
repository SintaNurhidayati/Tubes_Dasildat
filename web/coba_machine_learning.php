<?php

function predictDiabetes($features) {
    $pythonScript = dirname(__DIR__) . '/scripts/e-nose.py';
    $inputJson = json_encode($features);
    $command = escapeshellcmd("python3 $pythonScript '$inputJson'");
    $output = shell_exec($command);
    return json_decode($output, true);
}

$test_cases = [
    ['name' => 'Pasien Risiko Tinggi', 'features' => [6, 148, 72, 35, 0, 33.6, 0.627, 50]],
    ['name' => 'Pasien Sehat', 'features' => [1, 85, 66, 29, 0, 26.6, 0.351, 31]],
    ['name' => 'Pasien Risiko Tinggi', 'features' => [8, 183, 64, 0, 0, 23.3, 0.672, 32]],
    ['name' => 'Pasien Muda Sehat', 'features' => [0, 89, 66, 23, 94, 28.1, 0.167, 21]],
];

$results = [];
foreach ($test_cases as $case) {
    $results[] = [
        'name' => $case['name'],
        'features' => $case['features'],
        'result' => predictDiabetes($case['features'])
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Coba Machine Learning</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #3498db; color: white; }
        .diabetes { background: #fee; }
        .normal { background: #e8f5e9; }
        .btn { display: inline-block; margin-top: 15px; padding: 8px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Coba Machine Learning Model</h2>
         <table>
            <tr><th>No</th><th>Skenario</th><th>Data</th><th>Hasil</th></tr>
            <?php foreach($results as $i => $r): ?>
            <tr class="<?php echo strtolower($r['result']['class'] ?? ''); ?>">
                <td><?php echo $i+1; ?></td>
                <td><?php echo $r['name']; ?></td>
                <td><?php echo implode(', ', $r['features']); ?></td>
                <td><strong><?php echo $r['result']['class'] ?? 'Error'; ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="panggil_machine_learning.php" class="btn">Kembali</a>
    </div>
</body>
</html>