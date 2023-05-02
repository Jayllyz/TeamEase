package com.dashboard;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Label;

import java.net.URL;
import java.util.ResourceBundle;

import static com.dashboard.charts.HomeData.getActivitiesNumber;
import static com.dashboard.charts.HomeData.getCompanyNumber;

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

        if(numberOfActivities == -1){
            activityNumber.setText("Inconnu");
        } else if (numberOfActivities == 0){
            activityNumber.setText("Aucune activité");
        } else {
            activityNumber.setText(String.valueOf(numberOfActivities));
        }

//        companyNumber.setText(String.valueOf(Home.getCompaniesNumber()));
//        reservationNumber.setText(String.valueOf(Home.getReservationsNumber()));
    }
}
