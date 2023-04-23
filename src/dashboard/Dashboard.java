package dashboard;

import dashboard.charts.CompanySpending;
import dashboard.charts.ProviderActivity;
import dashboard.charts.ReservationPerMonth;
import dashboard.charts.TopActivity;
import dashboard.charts.TopCompanySpending;
import dashboard.charts.TopProvider;

import javafx.application.Application;
import javafx.embed.swing.SwingFXUtils;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.layout.AnchorPane;
import javafx.stage.Stage;

import java.awt.image.BufferedImage;
import java.io.IOException;

import javax.swing.text.Element;
import javax.swing.text.html.ImageView;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.fx.ChartViewer;
import org.jfree.data.statistics.HistogramDataset;

public class Dashboard extends Application {
    public static void main(String[] args) {

        // ChartPanel chartPanelReservationPerMonth = new ChartPanel(ReservationPerMonth.getChart(token));
        // ChartPanel chartPanelTopActivity = new ChartPanel(TopActivity.getChart(token));
        // ChartPanel chartPanelCompanySpending = new ChartPanel(CompanySpending.getChart(token));
        // ChartPanel chartPanelTopCompanySpending = new ChartPanel(TopCompanySpending.getChart(token));
        // ChartPanel chartPanelProviderActivity = new ChartPanel(ProviderActivity.getChart(token));
        // ChartPanel chartPanelTopProvider = new ChartPanel(TopProvider.getChart(token));

        // JFrame mainFrame = new JFrame("Dashboard");
        // mainFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        // mainFrame.add(chartPanelCompanySpending);
        // mainFrame.add(chartPanelTopCompanySpending);
        // mainFrame.add(chartPanelProviderActivity);
        // mainFrame.add(chartPanelTopProvider);
        // mainFrame.add(chartPanelReservationPerMonth);
        // mainFrame.add(chartPanelTopActivity);
        // mainFrame.setSize(1500, 800);
        // mainFrame.setVisible(true);

        launch(args);
    }

    @Override
    public void start(Stage primaryStage) throws IOException {
        String token = Token.getToken();
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("Dashboard.fxml"));
        Parent root = fxmlLoader.load();

        AnchorPane chartPane = (AnchorPane) fxmlLoader.getNamespace().get("showChart");
        JFreeChart reservationPerMonth = ReservationPerMonth.getChart(token);
        ChartViewer chartViewer = new ChartViewer(reservationPerMonth);

        Scene scene = new Scene(root);

        primaryStage.setTitle("Dashboard");
        primaryStage.setScene(scene);
        primaryStage.show();

        //On creer la pane d'abord sinon getWidth et getHeight renvoient 0
        chartViewer.setPrefWidth(chartPane.getWidth());
        chartViewer.setPrefHeight(chartPane.getHeight());
        chartPane.getChildren().add(chartViewer);
    }
}