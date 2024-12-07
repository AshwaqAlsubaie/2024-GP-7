#include <WiFi.h>
#include <SPI.h>
#include <MFRC522.h>
#include <TimeLib.h>
#include <WiFiUdp.h>
#include <NTPClient.h>
#include <Firebase_ESP_Client.h>
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h"
#include <map>

#define SS_PIN 21
#define RST_PIN 22
#define BUZZER_PIN 15

#define WIFI_SSID "ZTE_40E345_2.4G"
#define WIFI_PASSWORD "6tQ3572XN2"
#define API_KEY "AIzaSyD02_rLhSo8zX3PGFN6pZS3Eg5szrxZ1QA"
#define DATABASE_URL "https://smart-helmet-database-affb6-default-rtdb.firebaseio.com"

const long gmtOffset = 3 * 3600;  
const char* ntpServer = "pool.ntp.org";  
WiFiUDP udp;
NTPClient timeClient(udp, ntpServer, gmtOffset);

MFRC522 mfrc522(SS_PIN, RST_PIN);
WiFiClient client;

FirebaseConfig config;
FirebaseAuth auth;
FirebaseData fbdo;


void checkRFID();
String getCardUID();
void recordAttendance(const String& uid);
void activateBuzzer1();
time_t getNtpTime();
String epochToDateString(time_t epochTime);
String epochToTimeString(time_t epochTime);

// Mapping of UIDs to Firebase paths
const std::map<String, String> uidToPath = {
    {"d38a2e16", "Sensor/Sensors1"},
    {"e364cf1a", "Sensor/Sensors2"},
};

void setup() {
    Serial.begin(115200);
    SPI.begin();

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

    mfrc522.PCD_Init();
    pinMode(BUZZER_PIN, OUTPUT);
    digitalWrite(BUZZER_PIN, LOW);

    timeClient.begin();
    setSyncProvider(getNtpTime);  
    setSyncInterval(3600);
}

void loop() {
    checkRFID();
    delay(2000);
}

String getCardUID() {
    String uid = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
        uid += String(mfrc522.uid.uidByte[i], HEX);
    }
    return uid;
}

void checkRFID() {
    String uid = "";
    if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
        uid = getCardUID();
        Serial.print("UID tag: ");
        Serial.println(uid);
        activateBuzzer1();

        recordAttendance(uid);
        
        mfrc522.PICC_HaltA();
        mfrc522.PCD_StopCrypto1();
    }
}

void recordAttendance(const String& uid) {
    if (uidToPath.count(uid)) {
        String path = uidToPath.at(uid);
        String status = "Card has read";
        String date = epochToDateString(timeClient.getEpochTime());
        String time = epochToTimeString(timeClient.getEpochTime());

        Serial.print("Attendance Date: ");
        Serial.println(date);
        Serial.print("Attendance Time: ");
        Serial.println(time);
        Serial.println();

        Firebase.RTDB.setString(&fbdo, path + "/AttendanceStatus", status);
        Firebase.RTDB.setString(&fbdo, path + "/AttendanceDate", date.c_str());
        Firebase.RTDB.setString(&fbdo, path + "/AttendanceTime", time.c_str());
    }
}

void activateBuzzer1() {
    digitalWrite(BUZZER_PIN, HIGH);
    delay(1000);
    digitalWrite(BUZZER_PIN, LOW);
}

// Function to retrieve time from the NTP server and return it as a time_t
time_t getNtpTime() {
    timeClient.update();
    return timeClient.getEpochTime();
}

String epochToDateString(time_t epochTime) {
    char dateBuffer[20];
    sprintf(dateBuffer, "%04d-%02d-%02d", year(epochTime), month(epochTime), day(epochTime));
    return String(dateBuffer);
}

String epochToTimeString(time_t epochTime) {
    char timeBuffer[20];
    sprintf(timeBuffer, "%02d:%02d:%02d", hour(epochTime), minute(epochTime), second(epochTime));
    return String(timeBuffer);
}
