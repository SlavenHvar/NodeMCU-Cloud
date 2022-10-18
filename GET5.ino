

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
 
String deviceName = "nodemcu4";// može se dodati i username
const int LEDPin = 5;
int counter1 = 0;// koji služi za updateat bazu o stanju nodemcua ON ili OFF svakih 5 minuta// odnsono ako dođe do 60
int counter1max=60;
int counterPL = 0;
int status = 0;
 
//=======================================================================
//                    Power on setup
//=======================================================================
 
void setup() {
  //make the LED pin output and initially turned off
  pinMode(LEDPin, OUTPUT);
  digitalWrite(LEDPin, LOW);
  
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
  wifiManager.autoConnect("nodemcu4", "nodemcu123");//SSID I PASSWORD OD NODEMCU-A// DATI IME SVAKOM UREĐAJU I NJEGA PROVESTI U BAZU//IME KUPCA NEKA BUDE ZAJEDNIČI FOREIGN KEY
  //wifiManager.autoConnect("NODEMCU4");
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
    String getData, Link, postData, dev_status;
    int check_status = 0;
    bool status_changed=false;
//  String ADCData, station, getData, Link;
//  int adcvalue=analogRead(A0);  //Read Analog value of LDR
//  ADCData = String(adcvalue);   //String to interger conversion
   // station = "A";
  if(counterPL == 0)//get data if power loss
  {
      Serial.println("--------------------------");   //Print HTTP return code
      Serial.println("Power Loss - status check");   //Print HTTP return code
      counterPL = 1;// ova petlja se treba izvoditi samo ako nestane struje odnosno kad se varijabla resetira na nulu.
      //GET Data
      getData = "?device="+ deviceName ;//+ "&station=" + station ;  //Note "?" added at front// ovi podaci se šalju pomoću get requesta na server gdje se mogu koristiti npr pomoću njih querat bazu
      Link = "http://hvariot.000webhostapp.com/getPowerLoss.php" + getData;
      
      http.begin(Link);     //Specify request destination
      
      int httpCode = http.GET();            //Send the request
      String payload = http.getString();    //Get the response payload
     
      Serial.println(httpCode);   //Print HTTP return code
      Serial.println("PAYLOAD: "+ payload);    //Print request response payload
      
      check_status = digitalRead(LEDPin);

      Serial.println("check_status1:");
      Serial.println(check_status);  
      
      
      if((payload=="ON" && check_status > 0.5 || payload=="OFF" && check_status < 0.5) || payload == "NotChanged")
      {
        Serial.println("Status not changed");
        status_changed=false;
        counter1++;
      }
      else
      {
        Serial.println("Status changed");
        status_changed=true;
        counter1=0;// setiraj na nulu jer će ovaj slučaj obavjestit stanje o bazi
      }
      
      Serial.println("Status changed/not changed:");
      Serial.println(status_changed);
      Serial.println("counter1:");
      Serial.println(counter1);
      
    
      if(payload=="ON")
      {
        digitalWrite(LEDPin, HIGH);
      }
      else if(payload=="OFF")
      {
        digitalWrite(LEDPin, LOW);
      }
    
      check_status = digitalRead(LEDPin);

      Serial.println("check_status2:");
      Serial.println(check_status); 
    
      if(check_status > 0.5)
      {
        dev_status="ON";
      }
      else if(check_status < 0.5)
      {
        dev_status="OFF";
      }

      Serial.println("dev_status:");
      Serial.println(dev_status);
      Serial.println("--------------------------");   //Print HTTP return code
      http.end();  //Close connection
  }
  else//get data regulary
  {
    Serial.println("--------------------------");   //Print HTTP return code
      Serial.println("Regular status check");   //Print HTTP return code
      //GET Data
      getData = "?device="+ deviceName ;//+ "&station=" + station ;  //Note "?" added at front// ovi podaci se šalju pomoću get requesta na server gdje se mogu koristiti npr pomoću njih querat bazu
      Link = "http://hvariot.000webhostapp.com/getdemo.php" + getData;
      
      http.begin(Link);     //Specify request destination
      
      int httpCode = http.GET();            //Send the request
      String payload = http.getString();    //Get the response payload
     
      Serial.println(httpCode);   //Print HTTP return code
      Serial.println("PAYLOAD: "+ payload);    //Print request response payload
      
      
      check_status = digitalRead(LEDPin);

      Serial.println("check_status1:");
      Serial.println(check_status); 

    
      if((payload=="ON" && check_status > 0.5 || payload=="OFF" && check_status < 0.5) || payload == "NotChanged")////check koje je stanje LEDPin - ako je se stanje promijenilo onda pošalji post request u db  
      {
        Serial.println("Status not changed");
        status_changed=false;
        counter1++;
      }
      else
      {
        Serial.println("Status changed");
        status_changed=true;
        counter1=0;
      }

      Serial.println("Status changed/not changed:");
      Serial.println(status_changed);
      Serial.println("counter1:");
      Serial.println(counter1);
    
      if(payload=="ON")
      {
        digitalWrite(LEDPin, HIGH);
      }
      else if(payload=="OFF")
      {
        digitalWrite(LEDPin, LOW);
      }
    
      check_status = digitalRead(LEDPin);

      Serial.println("check_status2:");
      Serial.println(check_status); 
    
      if(check_status > 0.5)
      {
        dev_status="ON";
      }
      else if(check_status < 0.5)
      {
        dev_status="OFF";
      }

      Serial.println("dev_status:");
      Serial.println(dev_status);
      Serial.println("--------------------------");   //Print HTTP return code
      http.end();  //Close connection
  }
 

  //post device status

  if(status_changed || counter1 > counter1max )
  {
    Serial.println("---------------");    //Print request response payload
    Serial.println("Device status sent to db");    //Print request response payload
    if(counter1 > counter1max)// ako je prošlo 5 minuta resetiraj counter1 jer se šalje status uređaja na bazu
    {
       counter1=0;
       Serial.println("counter1 reset");    //Print request response payload
    }
    Serial.println("counter1:");    //Print request response payload
    Serial.println(counter1);    //Print request response payload
    //Post Data
    postData = "status=" + dev_status + "&name=" + deviceName;
    
    status = http.begin("http://hvariot.000webhostapp.com/postdemo.php"); //Don't use https, type instedad http             //Specify request destination
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header
    
    Serial.println("HTTP status");
    Serial.println(status);
    
    int httpCode = http.POST(postData);   //Send the request
    String payload = http.getString();    //Get the response payload
    
    Serial.println(httpCode);   //Print HTTP return code
    Serial.println(payload);    //Print request response payload
    Serial.println(status_changed);    //Print request response payload
    Serial.println(counter1);    //Print request response payload
    
    http.end();  //Close connection
  }
  
  
  delay(3000);  //GET Data at every 1 seconds
}
//=======================================================================
