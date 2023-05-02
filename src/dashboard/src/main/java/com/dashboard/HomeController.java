package com.dashboard;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Label;

import java.net.URL;
import java.util.ResourceBundle;

import static com.dashboard.charts.HomeData.*;

public class HomeController implements Initializable {

    @FXML
    private Label activityNumber;

    @FXML
    private Label companyNumber;

    @FXML
    private Label reservationNumber;

    private String token;

    public void setToken(String token) {
        this.token = token;
    }

    @Override
    public void initialize(URL location, ResourceBundle resources) {

        int numberOfActivities = getActivitiesNumber(token);
        int numberOfCompany = getCompanyNumber(token);
        int numberOfReservation = getReservationNumber(token);

        if(numberOfActivities == -1){
            activityNumber.setText("Inconnu");
        } else if (numberOfActivities == 0){
            activityNumber.setText("Aucune activité");
        } else {
            activityNumber.setText(String.valueOf(numberOfActivities));
        }

        if(numberOfCompany == -1){
            companyNumber.setText("Inconnu");
        } else if (numberOfCompany == 0){
            companyNumber.setText("Aucune entreprise");
        } else {
            companyNumber.setText(String.valueOf(numberOfCompany));
        }

        if(numberOfReservation == -1){
            reservationNumber.setText("Inconnu");
        } else if (numberOfReservation == 0){
            reservationNumber.setText("Aucune réservation");
        } else {
            reservationNumber.setText(String.valueOf(numberOfReservation));
        }

    }
}
