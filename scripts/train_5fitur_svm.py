import joblib
import pandas as pd
from sklearn.svm import SVC
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split

print("TRAINING MODEL 5 FITUR DENGAN SVM (TERBAIK)")

# Load data
df = pd.read_csv('C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/dataset/diabetes.csv')

# 5 fitur terbaik dari hasil seleksi 
features_5 = ['Glucose', 'BMI', 'Age', 'Pregnancies', 'DiabetesPedigreeFunction']
print(f"Fitur yang digunakan: {features_5}")

X = df[features_5]
y = df['Outcome']

# Split data
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)

# Scaling
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

model = SVC(C=10, gamma=0.01, kernel='rbf', probability=True, random_state=42)
model.fit(X_train_scaled, y_train)

# Evaluasi
akurasi = model.score(X_test_scaled, y_test)
print(f"\nAkurasi SVM dengan 5 fitur: {akurasi:.2%}")

# Simpan model dan scaler
joblib.dump(model, 'C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/models/Diabetes_5Fitur_SVM.sav')
joblib.dump(scaler, 'C:/xampp/htdocs/TUGAS_DIABETES_PREDICTION/models/Diabetes_5Fitur_Scaler.sav')

print("SELESAI!")
print(f"Model SVM 5 fitur: models/Diabetes_5Fitur_SVM.sav")
print(f"Scaler 5 fitur: models/Diabetes_5Fitur_Scaler.sav")