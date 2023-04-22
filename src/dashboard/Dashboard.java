package dashboard;

import dashboard.charts.CompanySpending;
import dashboard.charts.ProviderActivity;
import dashboard.charts.ReservationPerMonth;
import dashboard.charts.TopActivity;
import dashboard.charts.TopCompanySpending;
import dashboard.charts.TopProvider;

import javax.swing.JFrame;
import org.jfree.chart.ChartPanel;

public class Dashboard {
    public static void main(String[] args) {

        String token = Token.getToken();

        ChartPanel chartPanelReservationPerMonth = new ChartPanel(ReservationPerMonth.getChart(token));
        ChartPanel chartPanelTopActivity = new ChartPanel(TopActivity.getChart(token));
        ChartPanel chartPanelCompanySpending = new ChartPanel(CompanySpending.getChart(token));
        ChartPanel chartPanelTopCompanySpending = new ChartPanel(TopCompanySpending.getChart(token));
        ChartPanel chartPanelProviderActivity = new ChartPanel(ProviderActivity.getChart(token));
        ChartPanel chartPanelTopProvider = new ChartPanel(TopProvider.getChart(token));

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
    }
}