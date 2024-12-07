void checkForNewAudio() {
  // Retrieve the latest audio URL based on timestamp
  if (Firebase.RTDB.getJSON(&fbdo, "/messages/101/message")) {
    FirebaseJson& json = fbdo.jsonObject();
    size_t numMessages = json.iteratorBegin();

    String key, value;
    int type;
    unsigned long tempTimestamp = latestTimestamp;  // Temporary variable to compare timestamps
    String tempAudioURL = "";                       // Temporary variable to store the latest URL if a new one is found
    String latestTimestampStr;                      // Declare the variable to hold the latest timestamp string

    // Iterate over all nodes to find the latest one
    for (size_t i = 0; i < numMessages; i++) {
      json.iteratorGet(i, type, key, value);  // Get the key and value

      unsigned long timestamp;
      String audio_url;
      String timestampStr;

      // Retrieve the 'timestamp' from the node
      if (json.get(jsonData, key + "/timestamp")) {
        timestampStr = jsonData.stringValue;
        timestamp = parseTimestamp(timestampStr);  // Parse the timestamp string
      }

      // Retrieve the 'audio_url' from the node
      if (json.get(jsonData, key + "/audio_url")) {
        audio_url = jsonData.stringValue;
      }

      // Check if this is the latest timestamp
      if (timestamp > tempTimestamp) {
        tempTimestamp = timestamp;
        tempAudioURL = audio_url;
        latestTimestampStr = timestampStr;  
      }} json.iteratorEnd();

    // If we found a newer audio file, update and play it
    if (tempTimestamp > latestTimestamp) {
      latestTimestamp = tempTimestamp;
      latestAudioURL = tempAudioURL;

      if (latestAudioURL != "") {
        Serial.println("New audio found. Playing latest audio URL: " + latestAudioURL);
        Serial.println("Timestamp: " + latestTimestampStr);  
        audio.connecttohost(latestAudioURL.c_str());
      }
    } else {
      Serial.println("No new audio found.");
    }
  } else {
    Serial.println("Failed to get data from Firebase.");
    Serial.println("Reason: " + fbdo.errorReason());
  }
}

// Function to parse the timestamp string (assuming format "MM/DD/YYYY, HH:MM:SS AM/PM")
unsigned long parseTimestamp(String timestampStr) {
  struct tm timeinfo;
  const char* format = "%m/%d/%Y, %I:%M:%S %p";
  // Use strptime to parse the string into a tm structure
  if (strptime(timestampStr.c_str(), format, &timeinfo) == NULL) {
    Serial.println("Failed to parse timestamp");
    return 0;  // Return 0 if parsing fails
  }

  // Convert tm structure into time_t (Unix timestamp)
  time_t rawtime = mktime(&timeinfo);

  // Convert time_t to unsigned long for compatibility
  return (unsigned long)rawtime;
}
