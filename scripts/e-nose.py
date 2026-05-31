# scripts/e-nose.py
import joblib
import pandas as pd
import numpy as np
import sys
import json
import os
import warnings
warnings.filterwarnings('ignore')

# KONFIGURASI PATH 
model_diabetes = 'C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/models/Tubes_Dasildat_SVM.sav'
scaler_diabetes = 'C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/models/Tubes_Dasildat_Scaler.sav'

# LOAD MODEL
try:
    classifier_diabetes = joblib.load(model_diabetes)
    scaler_diabetes = joblib.load(scaler_diabetes)
except Exception as e:
    print(json.dumps({'error': f'Gagal load model: {str(e)}'}))
    sys.exit(1)

# FUNGSI PREDIKSI
def get_label(x):
    return 'Diabetes' if x == 1 else 'Tidak Diabetes'

# MAIN
if __name__ == "__main__":
    try:
        # Terima input dari command line
        if len(sys.argv) > 1:
            input_data = json.loads(sys.argv[1])
        else:
            input_data = json.loads(sys.stdin.read())
        
        # Kolom dataset
        columns = ['Pregnancies', 'Glucose', 'BloodPressure', 
                   'SkinThickness', 'Insulin', 'BMI', 
                   'DiabetesPedigreeFunction', 'Age']
        
        # Konversi ke DataFrame
        df = pd.DataFrame([input_data], columns=columns)
        
        # Scaling
        features = scaler_diabetes.transform(df)
        
        # Prediksi
        hasil = classifier_diabetes.predict(features)
        label = get_label(hasil[0])
        
        # Output JSON
        print(json.dumps({'class': label}))
        
    except Exception as e:
        print(json.dumps({'error': str(e)}))