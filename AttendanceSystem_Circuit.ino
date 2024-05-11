
#include <Arduino.h>
#include <WiFi.h>
#include <SPI.h>
#include <MFRC522.h>
#include <TimeLib.h>
#include <WiFiUdp.h>
#include <NTPClient.h>

#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h" 

#define SS_PIN 5   // Slave Select (SS) pin for SPI communication
#define RST_PIN 4  // Reset pin for the MFRC522 RFID reader module
#define BUZZER_PIN 2  // GPIO pin for the buzzer

#define WIFI_SSID ""
#define WIFI_PASSWORD ""
#define API_KEY "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA"
#define DATABASE_URL "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com"

const long gmtOffset = 3 * 3600; // GMT offset in seconds (GMT+3: 3 hours * 3600 seconds/hour)
const char* ntpServer = "sa.pool.ntp.org"; // NTP server for Saudi Arabia
WiFiUDP udp;
NTPClient timeClient(udp, ntpServer, gmtOffset);


MFRC522 mfrc522(SS_PIN, RST_PIN); // Create MFRC522 instance
WiFiClient client;

FirebaseConfig config;
FirebaseAuth auth;
FirebaseData fbdo;

// Define constants for UID values
const String UID_WORKER_1 = "d38a2e16";
String sensorPath1;


void setup() {
  Serial.begin(115200); // Initialize serial communication
  SPI.begin();   // Initialize SPI bus

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
  
  mfrc522.PCD_Init();   // Initialize MFRC522 RFID module
  pinMode(BUZZER_PIN, OUTPUT); 
  digitalWrite(BUZZER_PIN, LOW); // Turn off the buzzer initially

  // Initialize NTPClient
  timeClient.begin();

  // Set sync provider for TimeLib
  setSyncProvider(getNtpTime);
  setSyncInterval(3600); // Sync time every hour
}

void loop() {
    checkRFID();  // Check for new RFID cards
  
  delay(2000);
}


void checkRFID() {
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    String uid = getCardUID();
    Serial.print("UID tag :");
    Serial.println(uid);
    delay(100);
    

    if (hour()>= 5 && hour()< 7) {
      Serial.println("present");
      activateBuzzer1();
      recordAttendance(uid, "Present");
   
    } else {
      Serial.println("absent");
      activateBuzzer2();
      recordAttendance(uid, "Absent");
      
    }

    mfrc522.PICC_HaltA();
    mfrc522.PCD_StopCrypto1();
  }
}

String getCardUID() {
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  return uid;
}


void recordAttendance(const String& uid, const String& status) {
  String data = epochToDateString(timeClient.getEpochTime());
  if (uid == UID_WORKER_1) {
     sensorPath1 = "Sensor/Sensors1";
  }
  Firebase.RTDB.setString(&fbdo, sensorPath1 + "/AttendanceStatus", status);
  Firebase.RTDB.setString(&fbdo, sensorPath1 + "/AttendanceDate", data.c_str());
}

void activateBuzzer1() {
  digitalWrite(BUZZER_PIN, HIGH); // Turn on the buzzer
  delay(1000); // Buzz for 1 second

  digitalWrite(BUZZER_PIN, LOW); // Turn off the buzzer
}



void activateBuzzer2() {
  for (int i = 0; i < 3; i++) {
    digitalWrite(BUZZER_PIN, HIGH); 
    delay(200); // Buzz for 200 ms
    digitalWrite(BUZZER_PIN,LOW); // Stop buzzing
    delay(10); 
  }
  delay(500);
}


time_t getNtpTime() {
  timeClient.update();
  return timeClient.getEpochTime();
}

String epochToDateString(time_t epochTime) {
  char dateBuffer[20]; // Buffer to hold the formatted date string
  sprintf(dateBuffer, "%04d-%02d-%02d", year(epochTime), month(epochTime), day(epochTime));
  return String(dateBuffer);
}
