#include <Wire.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Adafruit_MPU6050.h>
#include <Adafruit_Sensor.h>
#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h"


#define WIFI_SSID "ZTE_40E345_2.4G"
#define WIFI_PASSWORD "6tQ3572XN2"
#define API_KEY "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA"
#define DATABASE_URL "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com"


#define SS_PIN 21
#define RST_PIN 22
MFRC522 rfid(SS_PIN, RST_PIN);


Adafruit_MPU6050 mpu;
#define SDA_PIN 26
#define SCL_PIN 25


FirebaseConfig config;
FirebaseAuth auth;
FirebaseData fbdo;

String Floor;
String Section;
const char* locationMap[][3] = {
  {"e364cf1a", "First Floor", "Section 1"},
  {"938cd818", "First Floor", "Section 2"},
  {"4392d41a", "Second Floor", "Section 3"}
};


const int buzzerPin = 13;
const float FALL_THRESHOLD = 2.8;
const int FALL_DETECTION_DELAY = 2000;
bool fallDetected = false;
float gForce;


void setupWiFi();
void setupFirebase();
void setupRFID();
void setupMPU6050();
void LocationMonitoring();
void FallDetection();
void triggerBuzzer();
void updateFirebase(const String& path, const String& value);

void setup() {
  Serial.begin(115200);
  pinMode(buzzerPin, OUTPUT);
  digitalWrite(buzzerPin, LOW);

  setupWiFi();
  setupFirebase();
  setupRFID();
  setupMPU6050();
}

void loop() {
  void LocationMonitoring();
  void FallDetection();
  delay(100);
}

void setupWiFi() {
  Serial.print("Connecting to Wi-Fi");
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }
  Serial.println("\nConnected to Wi-Fi");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
}

void setupFirebase() {
  config.api_key = API_KEY;
  config.database_url = DATABASE_URL;
  Firebase.begin(&config, &auth);
  Firebase.reconnectWiFi(true);

  if (Firebase.signUp(&config, &auth, "", "")) {
    Serial.println("Firebase Sign-Up Successful");
  } else {
    Serial.println("Firebase Sign-Up Failed");
    Serial.println(config.signer.signupError.message.c_str());
  }
}

void setupRFID() {
  SPI.begin();
  rfid.PCD_Init();
  Serial.println("RFID Reader Initialized");
}

void setupMPU6050() {
  Wire.begin(SDA_PIN, SCL_PIN);
  if (!mpu.begin()) {
    Serial.println("Failed to find MPU6050 chip");
    while (1) delay(10);
  }
  mpu.setAccelerometerRange(MPU6050_RANGE_2_G);
  mpu.setGyroRange(MPU6050_RANGE_250_DEG);
  mpu.setFilterBandwidth(MPU6050_BAND_21_HZ);
  Serial.println("MPU6050 Initialized");
}

void LocationMonitoring() {
  if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
    String tagID = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
      tagID += String(rfid.uid.uidByte[i], HEX);
    }
    Serial.print("RFID Tag: ");
    Serial.println(tagID);

    bool found = false;
    for (int i = 0; i < sizeof(locationMap) / sizeof(locationMap[0]); i++) {
      if (tagID.equalsIgnoreCase(locationMap[i][0])) {
        Floor = locationMap[i][1];
        Section = locationMap[i][2];
        Serial.print("Floor: ");
        Serial.println(Floor);
        Serial.print("Section: ");
        Serial.println(Section);
        updateFirebase("Sensor/Sensors1/Floor", Floor);
        updateFirebase("Sensor/Sensors1/Section", Section);
        found = true;
        break;
      }
    }

    if (!found) {
      Serial.println("Location Unknown");
    }

    delay(1000);
    rfid.PICC_HaltA();
  }
}

void FallDetection() {
  sensors_event_t a, g, temp;
  mpu.getEvent(&a, &g, &temp);
  gForce = sqrt(a.acceleration.x * a.acceleration.x + 
                a.acceleration.y * a.acceleration.y + 
                a.acceleration.z * a.acceleration.z) / 9.8;

  Serial.print("G-Force: ");
  Serial.println(gForce);

  if (gForce > FALL_THRESHOLD) {
    Serial.println("Fall Detected");
    triggerBuzzer();
    updateFirebase("Sensor/Sensors1/FallDetected", "Fall Detected");
    delay(FALL_DETECTION_DELAY);
  } else {
    updateFirebase("Sensor/Sensors1/FallDetected", "No Fall Detected");
  }
}

void triggerBuzzer() {
  digitalWrite(buzzerPin, HIGH);
  delay(1000);
  digitalWrite(buzzerPin, LOW);
}

void updateFirebase(const String& path, const String& value) {
  if (Firebase.RTDB.setString(&fbdo, path, value)) {
    Serial.println("Firebase Updated: " + path + " = " + value);
  } else {
    Serial.println("Failed to update Firebase: " + path);
    Serial.println(fbdo.errorReason());
  }
}
