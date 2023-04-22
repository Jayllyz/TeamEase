package dashboard.charts;

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
import org.jfree.chart.plot.CategoryPlot;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.chart.plot.XYPlot;
import org.jfree.data.category.DefaultCategoryDataset;
import org.jfree.data.general.DefaultPieDataset;
import org.jfree.data.xy.XYDataset;
import org.jfree.data.xy.XYSeries;
import org.jfree.data.xy.XYSeriesCollection;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class TopProvider {
    public static JFreeChart getChart(String token){
        DefaultPieDataset topProvider = new DefaultPieDataset();
        
        CUrl curl = new CUrl("http://localhost/api/api.php/provider/topAnimate");
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
            topProvider.setValue(firstName + " " + lastName + "(" + count + ")", count);
        }

        JFreeChart chartTopProvider = ChartFactory.createPieChart(
            "Les 5 prestataires ayant le plus d'activités",
            topProvider,
            false,
            true,
            false
        );

        return chartTopProvider;
    }
}
