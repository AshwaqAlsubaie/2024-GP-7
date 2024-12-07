#include <Wire.h>
#include <WiFi.h>
#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h"

#include <Adafruit_MLX90614.h>
#include <MQUnifiedsensor.h>

#define WIFI_SSID "potato"
#define WIFI_PASSWORD "12345678"

#define API_KEY "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA"
#define DATABASE_URL "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com"

// Firebase configuration and objects
FirebaseConfig config;
FirebaseAuth auth;
FirebaseData fbdo; 

// Define the MQ-2 sensor pin
#define MQ2_PIN 34  

int GasValue;

// Timing variables
const unsigned long writeInterval = 1200;  // Write interval (in ms) for gas
unsigned long sendDataPrevMillis = 0;      // For tracking the write interval


#define GAS_THRESHOLD 800
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

#include "Audio.h"
#include "ArduinoJson.h"
#include <time.h>
// I2S pins
#define I2S_DOUT 22
#define I2S_BCLK 26
#define I2S_LRC 25
Audio audio;
String audioURL;

unsigned long latestTimestamp = 0;
String latestAudioURL = "";

// Variables for timing
unsigned long lastCheckedTime = 0;
const unsigned long checkInterval = 2000;  // 15 seconds //5

FirebaseJsonData jsonData;


void setup() {
  Serial.begin(115200);
  // Connect to Wi-Fi
  setupWiFi();
  setupFirebase();
  //////////////////////////////////////////////////////////////////////////////////////////////////////////
 
  // Initialize MAX98357 setup
  audio.setPinout(I2S_BCLK, I2S_LRC, I2S_DOUT);
  audio.setVolume(80);
  // Initial check for latest audio
  checkForNewAudio();

}

void loop() {

  ReadGasValue();
  if (Firebase.ready() && (millis() - sendDataPrevMillis > writeInterval || sendDataPrevMillis == 0)) {
    sendDataPrevMillis = millis();
    uploadDataToFirebase();
  }
   
  //////////////////////////////////////////////////////
  if (millis() - lastCheckedTime >= checkInterval) {
    lastCheckedTime = millis();
    checkForNewAudio();  // Call the function to check for new audio
  }
  audio.loop();  

}
