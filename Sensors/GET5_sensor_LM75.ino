

/*
 * HTTP Client GET Request
 * Copyright (c) 2018, circuits4you.com
 * All rights reserved.
 * https://circuits4you.com 
 * Connects to WiFi HotSpot. */
 
#include <ESP8266WiFi.h>
#include <DNSServer.h>            //Local DNS Server used for redirecting all requests to the configuration portal//aa
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <WiFiManager.h>

#include <Wire.h>
 
#include <lm75.h>
 
TempI2C_LM75 termo = TempI2C_LM75(0x48,TempI2C_LM75::nine_bits);


String deviceName = "nodemcu2";// može se dodati i username
int status = 0;
 
//=======================================================================
//                    Power on setup
//=======================================================================
 
void setup() {
  
  delay(1000);
  Serial.begin(115200);
  WiFiManager wifiManager;

  wifiManager.setConfigPortalTimeout(180);
  
  // Uncomment and run it once, if you want to erase all the stored information
  //wifiManager.resetSettings();
  
  // set custom ip for portal
  wifiManager.setAPStaticIPConfig(IPAddress(10,0,1,1), IPAddress(10,0,1,1), IPAddress(255,255,255,0));// POTREBNO SE SPOJITI NA MREŽU KOJU GENERIRA NODEMCU I NAKON TOGA U BROWSER UPISATI 10.0.1.1

  // fetches ssid and pass from eeprom and tries to connect
  // if it does not connect it starts an access point with the specified name
  // here  "AutoConnectAP"
  // and goes into a blocking loop awaiting configuration
  wifiManager.autoConnect("nodemcu2", "nodemcu123");//SSID I PASSWORD OD NODEMCU-A// DATI IME SVAKOM UREĐAJU I NJEGA PROVESTI U BAZU//IME KUPCA NEKA BUDE ZAJEDNIČI FOREIGN KEY
  //wifiManager.autoConnect("NODEMCU2");
  // or use this for auto generated name ESP + ChipID
  // wifiManager.autoConnect();
  Serial.println("Device name and password:");
  Serial.println(deviceName);
  Serial.println("nodemcu123");
  // if you get here you have connected to the WiFi
  Serial.println("Connected.");
}
 
//=======================================================================
//                    Main Program Loop
//=======================================================================
void loop() {
  HTTPClient http;    //Declare object of class HTTPClient
    String deviceData, postData;

    Serial.println("Device data sent to db");    //Print request response payload

    deviceData = String(termo.getTemp())+" °C";

    Serial.println(deviceData);//Print request response payload
    
    //Post Data
    postData = "data=" + deviceData + "&name=" + deviceName;
    
    status = http.begin("http://hvariot.000webhostapp.com/postdata.php"); //Don't use https, type instedad http             //Specify request destination
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header
    
    Serial.println("HTTP status");
    Serial.println(status);
    
    int httpCode = http.POST(postData);   //Send the request
    String payload = http.getString();    //Get the response payload
    
    Serial.println(httpCode);   //Print HTTP return code
    Serial.println(payload);    //Print request response payload
    
    http.end();  //Close connection
  
  
  
  delay(60000);  //GET Data at every minute
}
//=======================================================================
