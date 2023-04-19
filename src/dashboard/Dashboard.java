package dashboard;

import java.awt.Color;
import java.awt.GridLayout;
import java.util.HashMap;
import java.util.Map;
import javax.swing.JFrame;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartPanel;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.axis.NumberAxis;
import org.jfree.chart.axis.NumberTickUnit;
import org.jfree.chart.axis.SymbolAxis;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.chart.plot.XYPlot;
import org.jfree.data.category.DefaultCategoryDataset;
import org.jfree.data.general.DefaultPieDataset;
import org.jfree.data.xy.XYDataset;
import org.jfree.data.xy.XYSeries;
import org.jfree.data.xy.XYSeriesCollection;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class Dashboard {
    public static void main(String[] args) {
        
        Admin.main(args);

        String adminEmail = Admin.getString("email");
        String adminPassword = Admin.getString("password");
        
        CUrl curl = new CUrl("http://localhost/api/api.php/auth/login");
        String jsonPayload = "{\"email\": \"" + adminEmail + "\", \"password\": \"" + adminPassword + "\"}";
        curl.opt("-X", "POST");
        curl.data(jsonPayload);
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);
        String token = obj.getString("token");

        XYSeries series = new XYSeries("Quantité de réservations");
        curl = new CUrl("http://localhost/api/api.php/activities/countActivitiesByMonth");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        response = curl.exec(CUrl.UTF8, null);

        obj = new JSONObject(response);
        JSONArray arr = obj.getJSONArray("data");
        String[] date = new String[arr.length()];
        for (int i = 0; i < arr.length(); i++)
        {
            int count = arr.getJSONObject(i).getInt("count");
            date[i] = arr.getJSONObject(i).getString("date");
            series.add(i, count);
        }
        XYDataset reservationPerMonth = new XYSeriesCollection(series);

        JFreeChart chartReservationPerMonth = ChartFactory.createXYLineChart(
            "Nombre de réservations par mois",
            "Mois",
            "Nombre de reservations",
            reservationPerMonth,
            PlotOrientation.VERTICAL,
            true,
            true,
            false
        );

        XYPlot plot = chartReservationPerMonth.getXYPlot();
        plot.setDomainGridlinePaint(Color.lightGray);
        plot.setRangeGridlinePaint(Color.lightGray);
        plot.setRenderer(new org.jfree.chart.renderer.xy.XYLineAndShapeRenderer());

        SymbolAxis xAxis = new SymbolAxis("Année et mois", date);
        plot.setDomainAxis(xAxis);

        NumberAxis yAxis = new NumberAxis("Nombre de réservations");
        yAxis.setTickUnit(new NumberTickUnit(1));
        plot.setRangeAxis(yAxis);

        ChartPanel chartPanelReservationPerMonth = new ChartPanel(chartReservationPerMonth);

        DefaultCategoryDataset companySpending = new DefaultCategoryDataset();

        curl = new CUrl("http://localhost/api/api.php/company/topPaid");
        headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        response = curl.exec(CUrl.UTF8, null);

        obj = new JSONObject(response);
        arr = obj.getJSONArray("data");
        for (int i = 0; i < arr.length(); i++)
        {
            int count = arr.getJSONObject(i).getInt("amount");
            String company = arr.getJSONObject(i).getString("companyName");
            companySpending.addValue(count, "Entreprise", company + "(" + count + "€)");
        }

        JFreeChart chartCompanySpending = ChartFactory.createBarChart(
            "Dépenses par entreprise",
            "Entreprises",
            "Montants",
            companySpending,
            org.jfree.chart.plot.PlotOrientation.VERTICAL,
            true,
            true,
            false
        );
    
        chartCompanySpending.getCategoryPlot().getRenderer().setSeriesPaint(0, new Color(128, 128, 255));

        ChartPanel chartPanelCompanySpending = new ChartPanel(chartCompanySpending);

        DefaultPieDataset topCompanySpending = new DefaultPieDataset();
        
        curl = new CUrl("http://localhost/api/api.php/company/topPaid");
        headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        response = curl.exec(CUrl.UTF8, null);

        obj = new JSONObject(response);
        arr = obj.getJSONArray("data");
        for (int i = 0; i < arr.length(); i++)
        {
            int amount = arr.getJSONObject(i).getInt("amount");
            String company = arr.getJSONObject(i).getString("companyName");
            topCompanySpending.setValue(company + "(" + amount + "€)", amount);
        }

        JFreeChart chartTopCompanySpending = ChartFactory.createPieChart(
            "Les 5 entreprises ayant le plus dépensé",
            topCompanySpending,
            false,
            true,
            false
        );

        ChartPanel chartPanelTopCompanySpending = new ChartPanel(chartTopCompanySpending);

        JFrame mainFrame = new JFrame("Dashboard");
        mainFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        mainFrame.setLayout(new GridLayout(2, 2));
        mainFrame.add(chartPanelCompanySpending);
        mainFrame.add(chartPanelTopCompanySpending);
        mainFrame.add(chartPanelReservationPerMonth);
        mainFrame.setSize(1000, 800);
        mainFrame.setVisible(true);
    }
}