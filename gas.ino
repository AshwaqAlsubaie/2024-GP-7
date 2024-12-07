
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

void ReadGasValue() {
  GasValue = analogRead(MQ2_PIN);
}

void uploadDataToFirebase() {
  Serial.println(GasValue);
  // Check if gas is detected and upload it to Firebase
  if (GasValue > GAS_THRESHOLD) {
    if (Firebase.RTDB.setString(&fbdo, "/Sensor/Sensors1/GasDetected", "Gas Detected")) {
      Serial.println("Gas detected and uploaded to Firebase!");
    } else {
      Serial.println("Failed to upload gas detection status to Firebase");
      Serial.println(fbdo.errorReason());
    }
  } else {
    // Optional: Upload "No Gas Detected" or similar if needed
    if (Firebase.RTDB.setString(&fbdo, "/Sensor/Sensors1/GasDetected", "No Gas Detected")) {
      Serial.println("No gas detected and uploaded to Firebase!");
    } else {
      Serial.println("Failed to upload no gas status to Firebase");
      Serial.println(fbdo.errorReason());
    }
  }
}
