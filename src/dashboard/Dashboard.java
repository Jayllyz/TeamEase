package dashboard;

import java.awt.BorderLayout;
import java.awt.Color;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Map;
import java.util.Properties;
import java.util.Scanner;
import java.awt.BasicStroke;
import javax.swing.JFrame;
import javax.swing.SwingUtilities;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartPanel;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.axis.CategoryAxis;
import org.jfree.chart.axis.NumberAxis;
import org.jfree.chart.axis.NumberTickUnit;
import org.jfree.chart.axis.SymbolAxis;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.chart.plot.XYPlot;
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
        XYDataset dataset = new XYSeriesCollection(series);

        JFreeChart chart = ChartFactory.createXYLineChart(
            "Nombre de réservations par mois",
            "Mois",
            "Nombre de reservations",
            dataset,
            PlotOrientation.VERTICAL,
            true,
            true,
            false
        );

        XYPlot plot = chart.getXYPlot();
        plot.setDomainGridlinePaint(Color.lightGray);
        plot.setRangeGridlinePaint(Color.lightGray);
        plot.setRenderer(new org.jfree.chart.renderer.xy.XYLineAndShapeRenderer());

        SymbolAxis xAxis = new SymbolAxis("Année et mois", date);
        plot.setDomainAxis(xAxis);

        NumberAxis yAxis = new NumberAxis("Nombre de réservations");
        yAxis.setTickUnit(new NumberTickUnit(1));
        plot.setRangeAxis(yAxis);

        ChartPanel chartPanel = new ChartPanel(chart);
        JFrame frame = new JFrame("Line Chart");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setLayout(new BorderLayout());
        frame.add(chartPanel, BorderLayout.CENTER);
        frame.pack();
        frame.setLocationRelativeTo(null);
        frame.setVisible(true);
    }


    
    //     DefaultPieDataset dataset = new DefaultPieDataset();
    //     dataset.setValue("Category 1", 43.2);
    //     dataset.setValue("Category 2", 27.9);
    //     dataset.setValue("Category 3", 79.5);

    //     JFreeChart chart = ChartFactory.createPieChart(
    //         "Dashboard",
    //         dataset,
    //         true,
    //         true,
    //         false
    //     );

    //     ChartPanel chartPanel = new ChartPanel(chart);

    //     JFrame frame = new JFrame("Dashboard");
    //     frame.getContentPane().add(chartPanel, BorderLayout.CENTER);
    //     frame.setSize(500, 500);
    //     frame.setVisible(true);
    // }
}