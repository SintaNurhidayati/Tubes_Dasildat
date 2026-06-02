import joblib
import pandas as pd
import numpy as np
import sys
import json
import warnings
warnings.filterwarnings('ignore')

# Path ke model 5 fitur 
model_path = 'C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/models/Diabetes_5Fitur_SVM.sav'
scaler_path = 'C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/models/Diabetes_5Fitur_Scaler.sav'

# 5 fitur terbaik 
FEATURES = ['Glucose', 'BMI', 'Age', 'Pregnancies', 'DiabetesPedigreeFunction']

# Load model
try:
    model = joblib.load(model_path)
    scaler = joblib.load(scaler_path)
except Exception as e:
    print(json.dumps({'error': f'Gagal load model: {str(e)}'}))
    sys.exit(1)

def get_label(x):
    return 'Diabetes' if x == 1 else 'Tidak Diabetes'

if __name__ == "__main__":
    try:
        if len(sys.argv) > 1:
            input_data = json.loads(sys.argv[1])
        else:
            input_data = json.loads(sys.stdin.read())
        
        # Input harus 5 nilai: [Glucose, BMI, Age, Pregnancies, Pedigree]
        df = pd.DataFrame([input_data], columns=FEATURES)
        scaled = scaler.transform(df)
        hasil = model.predict(scaled)[0]
        label = get_label(hasil)
        
        print(json.dumps({'class': label}))
        
    except Exception as e:
        print(json.dumps({'error': str(e)}))