package dashboard.charts;

import java.awt.Color;
import java.util.HashMap;
import java.util.Map;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
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

public class ReservationPerMonth {
    public static JFreeChart getChart(String token){
        XYSeries series = new XYSeries("Quantité de réservations");
        CUrl curl = new CUrl("http://localhost:8000/api/api.php/activities/countActivitiesByMonth");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        String response = curl.exec(CUrl.UTF8, null);
        
        JSONObject obj = new JSONObject(response);

        Boolean success = obj.getBoolean("success");
        if(success == false){
            return null;
        }
        
        JSONArray arr = obj.getJSONArray("data");
        String[] date = new String[arr.length()];
        for (int i = 0; i < arr.length(); i++)
        {
            int count = arr.getJSONObject(i).getInt("count");
            date[i] = arr.getJSONObject(i).getString("newDate");
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

        return chartReservationPerMonth;
    }
}
