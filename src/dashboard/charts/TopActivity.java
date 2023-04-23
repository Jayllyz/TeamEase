package dashboard.charts;

import java.util.HashMap;
import java.util.Map;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.data.general.DefaultPieDataset;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class TopActivity {
    public static JFreeChart getChart(String token){
        DefaultPieDataset topActivity = new DefaultPieDataset();
        
        CUrl curl = new CUrl("http://localhost:8000/api/api.php/activities/topActivities");
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
        for (int i = 0; i < arr.length(); i++)
        {
            int count = arr.getJSONObject(i).getInt("count");
            String activity = arr.getJSONObject(i).getString("name");
            topActivity.setValue(activity + "(" + count + ")", count);
        }

        JFreeChart chartTopActivity = ChartFactory.createPieChart(
            "Les 10 activitiés les plus réservées",
            topActivity,
            false,
            true,
            false
        );

        return chartTopActivity;
    }
}
