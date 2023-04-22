package dashboard.charts;

import java.util.HashMap;
import java.util.Map;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.data.general.DefaultPieDataset;

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
