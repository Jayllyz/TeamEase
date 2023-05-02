package com.dashboard.charts;

import com.roxstudio.utils.CUrl;
import org.json.JSONArray;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class HomeData {
    public static int getActivitiesNumber(String token){
        CUrl curl = new CUrl("https://togetherandstronger.site/api/api.php/activities/countAllActivities");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        curl.insecure();
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);

        Boolean success = obj.getBoolean("success");
        if(success == false){
            return -1;
        }
        JSONArray arr = obj.getJSONArray("data");
        for (int i = 0; i < arr.length(); i++) {
            int count = arr.getJSONObject(i).getInt("count");
            return count;
        }
        return -1;
    }

    public static int getCompanyNumber(String token){
        CUrl curl = new CUrl("https://togetherandstronger.site/api/api.php/company/countAllCompany");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        curl.insecure();
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);

        Boolean success = obj.getBoolean("success");
        if(success == false){
            return -1;
        }
        JSONArray arr = obj.getJSONArray("data");
        for (int i = 0; i < arr.length(); i++) {
            int count = arr.getJSONObject(i).getInt("count");
            return count;
        }
        return -1;
    }

    public static int getReservationNumber(String token){
        CUrl curl = new CUrl("https://togetherandstronger.site/api/api.php/activities/countAllReservation");
        Map<String, String> headersSent = new HashMap<String, String>();
        headersSent.put("Authorization", token);
        curl.headers(headersSent);
        curl.insecure();
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);

        Boolean success = obj.getBoolean("success");
        if(success == false){
            return -1;
        }
        JSONArray arr = obj.getJSONArray("data");
        for (int i = 0; i < arr.length(); i++) {
            int count = arr.getJSONObject(i).getInt("count");
            return count;
        }
        return -1;
    }
}
