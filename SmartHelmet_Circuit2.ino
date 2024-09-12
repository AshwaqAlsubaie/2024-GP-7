#include <Arduino.h>
#if defined(ESP32)
#include <WiFi.h>
#elif defined(ESP8266)
#include <ESP8266WiFi.h>
#endif
#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h" 
#include <Adafruit_MLX90614.h>
#include <Wire.h>

#define WIFI_SSID "ZTE_40E345_2.4G"
#define WIFI_PASSWORD "6tQ3572XN2"
#define API_KEY "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA"
#define DATABASE_URL "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com"


// Firebase configuration and objects
FirebaseConfig config;
FirebaseAuth auth;
FirebaseData fbdo;

// MLX90614 temperature sensor
Adafruit_MLX90614 mlx;

// Sensor data
float temperature;
float heartRate ;

// Global variables for heartbeat calculation
const int TAB_LENGTH = 20;
const int RISE_THRESHOLD = 2;
const int CALIB_OFFSET = 0;
float analog_Tab[TAB_LENGTH];
float analog_sum;
int ptr;
bool rising;
int rise_count;
float last;
float before;
float first;
float second;
float third;
long int last_beat;

// Timing variables
const unsigned long writeInterval = 9000; // Write interval (in ms)
unsigned long sendDataPrevMillis = 0;     // For tracking the write interval


void setupWiFi();
void setupFirebase();
void readSensors();
void uploadDataToFirebase();
void ReadTemperature();
void ReadHearRate(); 

void setup() {
  Serial.begin(115200);
  
  setupWiFi();
  setupFirebase();
 
  Wire.begin(D2, D1);
  mlx.begin();
}

void loop() {
  ReadTemperature();
  ReadHearRate();

  if (Firebase.ready() && (millis() - sendDataPrevMillis > writeInterval || sendDataPrevMillis == 0)) {
    sendDataPrevMillis = millis();
    uploadDataToFirebase();
  }
}

void setupWiFi() {
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
}

void setupFirebase() {
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
}

void ReadTemperature() {
  // Read temperature from the MLX90614 sensor
  temperature = mlx.readObjectTempC()+ 2;
}
 
 
 void ReadHearRate() {
  // Initialization variables
  for (int i = 0; i < TAB_LENGTH; i++)
  analog_Tab[i] = 0;
  analog_sum = 0;
  ptr = 0;
  
  int n_reads = 0;
  float start = millis();
  float analog_average = 0.;
  float now;
  
  // The loop reads the analog signal from the pulse sensor for 20 ms. 
  do {
    analog_average += analogRead(A0);
    n_reads++;
    now = millis(); 
  } while (now < start + 20); 
  analog_average /= n_reads;  
  
  analog_sum -= analog_Tab[ptr]; 
  analog_sum += analog_average; 
  analog_Tab[ptr] = analog_average;
  last = analog_sum / TAB_LENGTH;  

   // check for a rising curve (a heart beat)
  if (last > before) {
    rise_count++; 
    
    if (!rising && rise_count > RISE_THRESHOLD) { // we have detected a rising curve, which implies a heartbeat.
      rising = true; 
      // Record the time since last beat and keep track of the two previous times (first, second, third) to get a weighed average.
      first = millis() - last_beat;
      last_beat = millis(); 
      
      // Calculate the weighed average of heartbeat rate according to the three last beats
      heartRate = 60000. / (0.4 * first + 0.3 * second + 0.3 * third) + CALIB_OFFSET;
      third = second;
      second = first;
    }
  } else {
    // the curve is falling
    rising = false;
    rise_count = 0;
  }
  
  before = last;
  ptr++;
  ptr %= TAB_LENGTH;
}

void uploadDataToFirebase() {
  // Upload temperature to Firebase
  if (Firebase.RTDB.setFloat(&fbdo, "Sensor/Sensors1/BodyTemperature", temperature)) {
      Serial.print("Temperature: ");
      Serial.print(temperature);
      Serial.println(" °C");
      Serial.print("Temperature uploaded to Firebase and saved to: ");
      Serial.println(fbdo.dataPath());
    }
    else {
      Serial.println("Error uploading temperature to Firebase");
      Serial.println(fbdo.errorReason());
    }

    // Upload heartbeat to Firebase
    if (Firebase.RTDB.setFloat(&fbdo, "Sensor/Sensors1/BPM", heartRate )) {
      Serial.print("Heart Rate: ");
      Serial.print(heartRate);
      Serial.println(" BPM");
      Serial.print("Heart Rate uploaded to Firebase and saved to: ");
      Serial.println(fbdo.dataPath());
    }
    else {
      Serial.println("Error uploading heart rate to Firebase");
      Serial.println(fbdo.errorReason());
    }
}
