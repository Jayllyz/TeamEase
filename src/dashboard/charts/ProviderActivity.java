package dashboard.charts;

import java.awt.Color;
import java.util.HashMap;
import java.util.Map;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.axis.NumberAxis;
import org.jfree.chart.axis.NumberTickUnit;
import org.jfree.chart.plot.CategoryPlot;
import org.jfree.data.category.DefaultCategoryDataset;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class ProviderActivity {
    public static JFreeChart getChart(String token){
        DefaultCategoryDataset providerActivity = new DefaultCategoryDataset();

        CUrl curl = new CUrl("http://localhost/api/api.php/provider/animate");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);
        JSONArray arr = obj.getJSONArray("data");
        for (int i = 0; i < arr.length(); i++)
        {
            int count = arr.getJSONObject(i).getInt("count");
            String firstName = arr.getJSONObject(i).getString("firstName");
            String lastName = arr.getJSONObject(i).getString("lastName");
            providerActivity.addValue(count, "Prestataire", firstName + " " + lastName + "(" + count + ")");
        }

        JFreeChart chartProviderActivity = ChartFactory.createBarChart(
            "Nombre d'activités par prestataire",
            "Prestataires",
            "Nombre d'activités",
            providerActivity,
            org.jfree.chart.plot.PlotOrientation.VERTICAL,
            false,
            true,
            false
        );

        CategoryPlot plot = chartProviderActivity.getCategoryPlot();
        NumberAxis rangeAxis = (NumberAxis) plot.getRangeAxis();
        rangeAxis.setTickUnit(new NumberTickUnit(1));
    
        chartProviderActivity.getCategoryPlot().getRenderer().setSeriesPaint(0, new Color(200,80, 80));

        return chartProviderActivity;
    }
}
