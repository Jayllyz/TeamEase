package com.dashboard.charts;

import java.awt.Color;
import java.util.HashMap;
import java.util.Map;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.data.category.DefaultCategoryDataset;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class CompanySpending {
    public static JFreeChart getChart(String token){
        DefaultCategoryDataset companySpending = new DefaultCategoryDataset();

        CUrl curl = new CUrl("https://togetherandstronger.site/api/api.php/company/paid");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        curl.insecure();
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);

        Boolean success = obj.getBoolean("success");
        if(success == false){
            return null;
        }

        JSONArray arr = obj.getJSONArray("data");
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
            false,
            true,
            false
        );
    
        chartCompanySpending.getCategoryPlot().getRenderer().setSeriesPaint(0, new Color(80, 80, 200));

        return chartCompanySpending;
    }
}
