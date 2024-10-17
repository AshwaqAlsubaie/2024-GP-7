#include <Wire.h>
#include <Adafruit_MPU6050.h>
#include <Adafruit_Sensor.h>
#include <WiFi.h>
#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h"

Adafruit_MPU6050 mpu;

#define WIFI_SSID "ZTE_40E345_2.4G"
#define WIFI_PASSWORD "6tQ3572XN2"
#define API_KEY "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA"
#define DATABASE_URL "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com"

FirebaseConfig config;
FirebaseAuth auth;
FirebaseData fbdo;

const int MPU_addr = 0x68;  // I2C address of the MPU-6050
int16_t AcX, AcY, AcZ, Tmp, GyX, GyY, GyZ;
float ax = 0, ay = 0, az = 0, gx = 0, gy = 0, gz = 0;
boolean fall = false;  // stores if a fall has occurred
int angleChange = 0;

// Buzzer pin
const int buzzerPin = 13;
const float FALL_THRESHOLD = 2.8;  // Threshold for detecting fall (in G's)
const int FALL_DETECTION_DELAY = 2000;  // Time delay to beep in ms after a fall

float gForce;

void mpu_read();
void updateFirebase(bool fallDetected);
void triggerBuzzer();

void setup() {
  Serial.begin(115200);

  // Connect to Wi-Fi
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Connecting to Wi-Fi");

  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1500);
  }

  Serial.println();
  Serial.print("Connected with IP: ");
  Serial.println(WiFi.localIP());
  Serial.println();

  // Firebase configuration
  config.api_key = API_KEY;
  config.database_url = DATABASE_URL;

  Firebase.begin(&config, &auth);
  Firebase.reconnectWiFi(true);

  if (Firebase.signUp(&config, &auth, "", "")) {
    Serial.println("Sign-up successful");
  } else {
    Serial.println("Sign-up failed");
    Serial.println(config.signer.signupError.message.c_str());
  }

  // Initialize MPU6050
  Wire.begin();
  Wire.beginTransmission(MPU_addr);
  Wire.write(0x6B);  // PWR_MGMT_1 register
  Wire.write(0);     // set to zero (wakes up the MPU-6050)
  Wire.endTransmission(true);

  if (!mpu.begin()) {
    Serial.println("Failed to find MPU6050 chip");
    while (1) {
      delay(10);
    }
  }
  mpu.setAccelerometerRange(MPU6050_RANGE_2_G);
  mpu.setGyroRange(MPU6050_RANGE_250_DEG);
  mpu.setFilterBandwidth(MPU6050_BAND_21_HZ);

  Serial.println("MPU6050 initialized");

  // Initialize buzzer
  pinMode(buzzerPin, OUTPUT);
  digitalWrite(buzzerPin, LOW);
}

void loop() {
  mpu_read();  // Read updated MPU data

  // Update sensor data
  ax = (AcX - 2050) / 16384.00;
  ay = (AcY - 77) / 16384.00;
  az = (AcZ - 1947) / 16384.00;
  gx = (GyX + 270) / 131.07;
  gy = (GyY - 351) / 131.07;
  gz = (GyZ + 136) / 131.07;

  sensors_event_t a, g, temp;
  mpu.getEvent(&a, &g, &temp);

  // Calculate G-force
   gForce = sqrt(a.acceleration.x * a.acceleration.x +
                      a.acceleration.y * a.acceleration.y +
                      a.acceleration.z * a.acceleration.z) / 9.8;  // Convert m/s² to G's

  Serial.print("G-force: ");
  Serial.println(gForce);

  // Check if G-force exceeds threshold
  if (gForce > FALL_THRESHOLD) {
    Serial.println("G-force threshold reached, checking angle change...");
    angleChange = sqrt(gx * gx + gy * gy + gz * gz);
    if (angleChange >= 25 && angleChange <= 400) { //30
      fall = true;
    }
  }

  // If fall is detected, activate buzzer and update Firebase
  if (fall == true) {
    Serial.println("FALL DETECTED");
    triggerBuzzer();
    updateFirebase(true);
    fall = false;  // Reset fall flag after handling fall
    delay(FALL_DETECTION_DELAY);  // Delay to prevent continuous triggering
  } else {
    updateFirebase(false);
  }

  delay(20);  // Delay between readings
}

void mpu_read() {
  Wire.beginTransmission(MPU_addr);
  Wire.write(0x3B);  // starting with register 0x3B (ACCEL_XOUT_H)
  Wire.endTransmission(false);
  Wire.requestFrom(MPU_addr, 14, true);  // request a total of 14 registers

  AcX = Wire.read() << 8 | Wire.read();  // 0x3B (ACCEL_XOUT_H) & 0x3C (ACCEL_XOUT_L)
  AcY = Wire.read() << 8 | Wire.read();  // 0x3D (ACCEL_YOUT_H) & 0x3E (ACCEL_YOUT_L)
  AcZ = Wire.read() << 8 | Wire.read();  // 0x3F (ACCEL_ZOUT_H) & 0x40 (ACCEL_ZOUT_L)
  Tmp = Wire.read() << 8 | Wire.read();  // 0x41 (TEMP_OUT_H) & 0x42 (TEMP_OUT_L)
  GyX = Wire.read() << 8 | Wire.read();  // 0x43 (GYRO_XOUT_H) & 0x44 (GYRO_XOUT_L)
  GyY = Wire.read() << 8 | Wire.read();  // 0x45 (GYRO_YOUT_H) & 0x46 (GYRO_YOUT_L)
  GyZ = Wire.read() << 8 | Wire.read();  // 0x47 (GYRO_ZOUT_H) & 0x48 (GYRO_ZOUT_L)
}

void triggerBuzzer() {
  // Activate the buzzer for a short beep
  digitalWrite(buzzerPin, HIGH);
  delay(3000);  // Beep duration
  digitalWrite(buzzerPin, LOW);
}

void updateFirebase(bool fallDetected) {
  if (fallDetected) {
    Firebase.RTDB.setString(&fbdo, "/Sensor/Sensors1/FallDetected", "Fall Detected");
    Firebase.RTDB.setString(&fbdo, "/Sensor/Sensors1/Fallvalue", gForce);
  } else {
    Firebase.RTDB.setString(&fbdo, "/Sensor/Sensors1/FallDetected", "No Fall Detected");
  }
}
