/******************************
 * 
 * Estación meteorológica v0.0.1
 * ===========================
 * 
 * MdeMoUcH 2018
 * 
 * Notas:
 * -El termistor usa una resistencia de 10K
 * -La pantalla usa una resistencia de 1K
 * -El led usa resistencias de 1K
 * 
 * 
 * 
 ******************************/
#include <LiquidCrystal.h>

LiquidCrystal lcd(12, 11, 5, 4, 3, 2);

// Define Pins
#define BLUE 6
#define GREEN 9
#define RED 10
#define LEDPIN  13
#define TEMPPIN A0
#define RELE A1

int red;
int green;
int blue;

String cale;
String estado;

long startTime;



void setup(){
  
  pinMode(RED, OUTPUT);
  pinMode(GREEN, OUTPUT);
  pinMode(BLUE, OUTPUT);
  digitalWrite(RED, LOW);
  digitalWrite(GREEN, LOW);
  digitalWrite(BLUE, LOW);

  pinMode(RELE,OUTPUT);

  pinMode(LEDPIN, OUTPUT);

  digitalWrite(RELE,HIGH);//Apagado
  
  lcd.begin(16, 2);
  lcd.print("    MdeMoUcH");
  
  cale = "OFF";
  estado = "       OFF";

  red = 0;
  green = 0;
  blue = 55;

  startTime = 60000;

  Serial.begin(9600);
  
}




void loop(){
 
  int tempReading = analogRead(TEMPPIN);
  float tempK = log (10000.0 * ((1024.0/tempReading - 1)));
  tempK = 1 / (0.001129148 + (0.000234125 + (0.0000000876741 * tempK * tempK)) * tempK);
  float tempC = tempK - 273.15;
  float tempF = (tempC * 9.0) / 5.0 + 32.0;

  
  if(cale=="ON"){
    tempC = tempC + 0.9;
  }
  
  
  if(Serial.available() > 0){
    char value = Serial.read();
    if(value == '1'){
      cale = "ON";
      estado = "    ON (Heat)";
      red = 55;
      green = 0;
      blue = 0;
      digitalWrite(LEDPIN, HIGH);
    }else{
      if(value != '\n'){
        cale = "OFF";
        if(value == '2'){
          red = 0;
          green = 55;
          blue = 0;
          estado = "    ON (Wait)";
        }else{
          red = 0;
          green = 0;
          blue = 55;
          estado = "       OFF";
        }
        digitalWrite(LEDPIN, LOW);
      }
    }
  }

  String taxt = "";
  
  if(tempC > 22.00){
    taxt = " :P";
  }else if(tempC > 20.00){
    taxt = " :)";
  }else if(tempC < 18.00){
    taxt = " :(";
  }else if(tempC < 16.00){
    taxt = " X(";
  }else{
    taxt = " :|";
  }

  analogWrite(RED, red);
  analogWrite(GREEN, green);
  analogWrite(BLUE, blue);


  if(cale=="ON"){
    digitalWrite(RELE,LOW);//Encendido
  }else{
    digitalWrite(RELE,HIGH);//Apagado
  }

  if(millis() > 3000){
    lcd.setCursor(0, 1);
    lcd.print(estado+"          ");
    lcd.setCursor(0, 0);
  }else{
    lcd.setCursor(0, 1);
  }
  
  String temp = String(tempC);
  
  lcd.print(String("Temp: "+temp+"C "+taxt+""));

  int minuto = (millis() - startTime)/60000;
  if(minuto >= 1){
    Serial.print(temp+"\r\n");
    startTime = millis();
  }
  //Serial.print(String(startTime)+" "+String(millis())+"\r\n");
  delay(500);
  
}
