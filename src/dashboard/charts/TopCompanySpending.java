package dashboard.charts;

import java.util.HashMap;
import java.util.Map;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.data.general.DefaultPieDataset;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class TopCompanySpending {
    public static JFreeChart getChart(String token){
        DefaultPieDataset topCompanySpending = new DefaultPieDataset();
        
        CUrl curl = new CUrl("http://localhost:8000/api/api.php/company/topPaid");
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

        return chartTopCompanySpending;
    }
}
