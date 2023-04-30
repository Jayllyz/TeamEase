package com.dashboard;

import javafx.event.ActionEvent;

import java.net.URL;
import java.util.ResourceBundle;

import org.jfree.chart.JFreeChart;
import org.jfree.chart.fx.ChartViewer;

import com.dashboard.charts.CompanySpending;
import com.dashboard.charts.ProviderActivity;
import com.dashboard.charts.ReservationPerMonth;
import com.dashboard.charts.TopActivity;
import com.dashboard.charts.TopCompanySpending;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.layout.Pane;

public class DashboardController implements Initializable{

    @FXML
    private Button btnCompanySpending;

    @FXML
    private Button btnPopularActivity;

    @FXML
    private Button btnProviderActivity;

    @FXML
    private Button btnReservationPerMonth;

    @FXML
    private Button btnTopCompany;

    @FXML
    private Button btnTopProvider;

    @FXML
    private Button btnExit;

    @FXML
    private Pane chartPane;

    private String token;

    @Override
    public void initialize(URL location, ResourceBundle resources){
        
    }

    public void setToken(String token) {
        this.token = token;
    }

    @FXML
    private void handleClicks(ActionEvent event){
        if (event.getSource() == btnCompanySpending) {
            JFreeChart companySpending = CompanySpending.getChart(token);

            if(companySpending == null){
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Aucune donnée");
                alert.setHeaderText(null);
                alert.setContentText("Aucune donnée n'a été trouvée pour ce graphique.");

                alert.showAndWait();
            } else {
                ChartViewer chartViewer = new ChartViewer(companySpending);

                chartViewer.setPrefWidth(chartPane.getWidth());
                chartViewer.setPrefHeight(chartPane.getHeight());

                chartViewer.prefWidthProperty().bind(chartPane.widthProperty());
                chartViewer.prefHeightProperty().bind(chartPane.heightProperty());

                chartPane.getChildren().clear();
                chartPane.getChildren().add(chartViewer);
            }
        }
        else if(event.getSource() == btnPopularActivity){
            JFreeChart popularActivity = TopActivity.getChart(token);

            if(popularActivity == null){
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Aucune donnée");
                alert.setHeaderText(null);
                alert.setContentText("Aucune donnée n'a été trouvée pour ce graphique.");

                alert.showAndWait();
            } else {
                ChartViewer chartViewer = new ChartViewer(popularActivity);

                chartViewer.setPrefWidth(chartPane.getWidth());
                chartViewer.setPrefHeight(chartPane.getHeight());

                chartViewer.prefWidthProperty().bind(chartPane.widthProperty());
                chartViewer.prefHeightProperty().bind(chartPane.heightProperty());

                chartPane.getChildren().clear();
                chartPane.getChildren().add(chartViewer);
            }
        }
        else if(event.getSource() == btnProviderActivity){
            JFreeChart providerActivity = ProviderActivity.getChart(token);
            
            if(providerActivity == null){
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Aucune donnée");
                alert.setHeaderText(null);
                alert.setContentText("Aucune donnée n'a été trouvée pour ce graphique.");

                alert.showAndWait();
            } else {
                ChartViewer chartViewer = new ChartViewer(providerActivity);

                chartViewer.setPrefWidth(chartPane.getWidth());
                chartViewer.setPrefHeight(chartPane.getHeight());

                chartViewer.prefWidthProperty().bind(chartPane.widthProperty());
                chartViewer.prefHeightProperty().bind(chartPane.heightProperty());

                chartPane.getChildren().clear();
                chartPane.getChildren().add(chartViewer);
            }
        }
        else if(event.getSource() == btnReservationPerMonth){
            JFreeChart reservationPerMonth = ReservationPerMonth.getChart(token);
            if(reservationPerMonth == null){
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Aucune donnée");
                alert.setHeaderText(null);
                alert.setContentText("Aucune donnée n'a été trouvée pour ce graphique.");

                alert.showAndWait();
            } else {
                ChartViewer chartViewer = new ChartViewer(reservationPerMonth);

                chartViewer.setPrefWidth(chartPane.getWidth());
                chartViewer.setPrefHeight(chartPane.getHeight());

                chartViewer.prefWidthProperty().bind(chartPane.widthProperty());
                chartViewer.prefHeightProperty().bind(chartPane.heightProperty());

                chartPane.getChildren().clear();
                chartPane.getChildren().add(chartViewer);
            }
        }
        else if(event.getSource() == btnTopCompany){
            JFreeChart topCompany = TopCompanySpending.getChart(token);

            if(topCompany == null){
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Aucune donnée");
                alert.setHeaderText(null);
                alert.setContentText("Aucune donnée n'a été trouvée pour ce graphique.");

                alert.showAndWait();
            } else {
                ChartViewer chartViewer = new ChartViewer(topCompany);

                chartViewer.setPrefWidth(chartPane.getWidth());
                chartViewer.setPrefHeight(chartPane.getHeight());

                chartViewer.prefWidthProperty().bind(chartPane.widthProperty());
                chartViewer.prefHeightProperty().bind(chartPane.heightProperty());

                chartPane.getChildren().clear();
                chartPane.getChildren().add(chartViewer);
            }
        }
        else if(event.getSource() == btnTopProvider){
            JFreeChart topProvider = TopActivity.getChart(token);

            if(topProvider == null){
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Aucune donnée");
                alert.setHeaderText(null);
                alert.setContentText("Aucune donnée n'a été trouvée pour ce graphique.");

                alert.showAndWait();
            } else {
                ChartViewer chartViewer = new ChartViewer(topProvider);

                chartViewer.setPrefWidth(chartPane.getWidth());
                chartViewer.setPrefHeight(chartPane.getHeight());

                chartViewer.prefWidthProperty().bind(chartPane.widthProperty());
                chartViewer.prefHeightProperty().bind(chartPane.heightProperty());

                chartPane.getChildren().clear();
                chartPane.getChildren().add(chartViewer);
            }
        } else if(event.getSource() == btnExit){
            System.exit(0);
        }
    }
}
