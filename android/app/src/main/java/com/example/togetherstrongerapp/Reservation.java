package com.example.togetherstrongerapp;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Reservation extends AppCompatActivity {
    private ListView list;
        @Override
        public void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_reservation);

            list = findViewById(R.id.list);

            SharedPreferences preferences = getSharedPreferences("connected", MODE_PRIVATE);
            String token = preferences.getString("token", "");

            ActivityAdapter adapter = new ActivityAdapter(getActivities(token), this);
            list.setAdapter(adapter);
        }

    public List<Activity> getActivities(String token){

        List<Activity> activities = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "https://togetherandstronger.site/api/api.php/company";
        StringRequest request = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject json = new JSONObject(response);

                    JSONArray jsonActivities = json.getJSONArray("data");

                    System.out.println(jsonActivities);
                    Log.d("json", jsonActivities.toString());
                    for (int i = 0; i < jsonActivities.length(); i++) {
                        JSONObject jsonActivity = jsonActivities.getJSONObject(i);

                        Toast.makeText(Reservation.this, jsonActivity.toString(), Toast.LENGTH_SHORT).show();

                        String id_activity = jsonActivity.getString("id_activity");

                        Toast.makeText(Reservation.this, id_activity, Toast.LENGTH_LONG).show();


                        String url = "https://togetherandstronger/api/api.php/activities/" + id_activity;

                        StringRequest requestId = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                try {
                                    JSONObject json = new JSONObject(response);
                                    Toast.makeText(Reservation.this, json.toString(), Toast.LENGTH_SHORT).show();

                                    JSONArray jsonActivities = json.getJSONArray("data");

                                    Activity activity = new Activity(
                                            jsonActivity.getString("name"),
                                            jsonActivity.getString("description"),
                                            Integer.parseInt(jsonActivity.getString("maxAttendee")),
                                            Integer.parseInt(jsonActivity.getString("duration")),
                                            Integer.parseInt(jsonActivity.getString("priceAttendee")));

                                    activities.add(activity);

                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }
                            }
                        }, new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                error.printStackTrace();
                            }
                        }) {
                            @Override
                            public Map<String, String> getHeaders() throws AuthFailureError {
                                Map<String, String>  params = new HashMap<String, String>();
                                params.put("Authorization", token);
                                return params;
                            }
                        };

                        queue.add(requestId);

                        Toast.makeText(Reservation.this, activities.toString(), Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                error.printStackTrace();
            }
        }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String>  params = new HashMap<String, String>();
                params.put("Authorization", token);
                return params;
            }
        };
        queue.add(request);
        return activities;
    }
}
