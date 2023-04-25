package com.example.togetherstrongerapp;

import static java.lang.String.valueOf;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.android.volley.VolleyError;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    private Button connect, reservation;
    private ListView catalog;

    public String token;
    private boolean connected = false;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        this.catalog = findViewById(R.id.catalog);
        this.connect = findViewById(R.id.connect);
        this.reservation = findViewById(R.id.reservation);

        SharedPreferences preferences = getSharedPreferences("connected", MODE_PRIVATE);

        if(preferences.getBoolean("connected", true)){
            connected = true;
            this.token = getSharedPreferences("connected", MODE_PRIVATE).getString("token", "");
        }
        if (connected) {
            this.connect.setText("Déconnexion");
        }

        ActivityAdapter adapter = new ActivityAdapter(getActivities(token), this);
        catalog.setAdapter(adapter);

        this.connect.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                SharedPreferences preferences = getSharedPreferences("connected", MODE_PRIVATE);
                Toast.makeText(MainActivity.this, valueOf(connected), Toast.LENGTH_SHORT).show();
                if (!connected) {
                    Intent intent = new Intent(MainActivity.this, Login.class);
                    startActivity(intent);
                } else {
                    preferences.edit().putBoolean("connected", false).apply();
                    connect.setText("Connexion");
                }
            }
        });

        this.reservation.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (connected) {
                    Intent intent = new Intent(MainActivity.this, Reservation.class);
                    startActivity(intent);
                } else {
                    Toast.makeText(MainActivity.this, "Vous devez être connecté pour accéder à cette page", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }


    public List<Activity> getActivities(String token){

        List<Activity> activities = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "http://10.0.2.2/api/api.php/company";
        StringRequest request = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject json = new JSONObject(response);
                    JSONArray jsonActivities = json.getJSONArray("data");
                    for (int i = 0; i < jsonActivities.length(); i++) {
                        JSONObject jsonActivity = jsonActivities.getJSONObject(i);
                        Activity activity = new Activity(
                                jsonActivity.getString("name"),
                                jsonActivity.getString("description"),
                                Integer.parseInt(jsonActivity.getString("maxAttendee")),
                                Integer.parseInt(jsonActivity.getString("duration")),
                                Integer.parseInt(jsonActivity.getString("priceAttendee")));
                        activities.add(activity);
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
        });
        queue.add(request);
        return activities;
    }
}