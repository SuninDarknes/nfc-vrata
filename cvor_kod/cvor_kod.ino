//kod za custom implementaciju...

#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "K30";
const char* password = "paprika123";
String tajni_key = "Ja_sam_mali_cvor_K30";

String serverName = "https://tsck.eu/kontrolaulaza/cvor.php";

//web socket client - Gotal veli da nam to treba

unsigned long lastTime = 0;

unsigned long timerDelay = 15000; //update rate 15sek

void setup() {
   Serial.begin(115200);
   pinMode(23,OUTPUT);
   WiFi.begin(ssid, password);
   Serial.println("Connecting");
   while(WiFi.status() != WL_CONNECTED) {
     delay(500);
     Serial.print(".");
   }
   Serial.println("");
   Serial.print("IP: ");
   Serial.println(WiFi.localIP());
   Serial.print("MAC: ");
   Serial.println(WiFi.macAddress());
   
}

void loop() {
   if ((millis() - lastTime) > timerDelay) {
     if(WiFi.status()== WL_CONNECTED){
       HTTPClient http;

       http.begin(serverName.c_str());
        http.addHeader("Content-Type", "application/json");

        bool stanje = 0;
  
        String jsonData = "{\"Stanje_vrata\" : " + String(stanje) + ", \"tajni_key\" : \"" + tajni_key + "\"}";

       int httpResponseCode = http.POST(jsonData.c_str());

       if (httpResponseCode>0) {
         Serial.print("HTTP Response code: ");
         Serial.println(httpResponseCode);
         String payload = http.getString();
         Serial.println(payload);
         if(payload == "dobar dan"){
           //Serial.println("radi");
           digitalWrite(23, HIGH);
           delay(10000);
           digitalWrite(23, LOW);
         }else{
           //Serial.println("ne radi");
         }
       }
       else {
         Serial.print("Error code: ");
         Serial.println(httpResponseCode);
       }
       http.end();
     }
     else {
       Serial.println("WiFi Disconnected");
     }
     lastTime = millis();
   }
}