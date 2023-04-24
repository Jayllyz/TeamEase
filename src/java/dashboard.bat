@echo off

rem Set the path to the dashboard
set DASHBOARD_PATH=C:\Program Files\Java\javafx-sdk-17.0.7\lib

rem Set the path to the jar file
set JAR_PATH=java.jar

rem Run the dashboard
java --enable-preview --module-path "%DASHBOARD_PATH%" --add-modules=javafx.controls,javafx.fxml -jar "%JAR_PATH%"