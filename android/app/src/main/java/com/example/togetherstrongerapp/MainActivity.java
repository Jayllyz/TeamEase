package com.example.togetherstrongerapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.android.volley.VolleyError;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {

    private EditText emailInput, passwordInput;
    private Button loginButton;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
        boolean isLoggedIn = connected.getBoolean("connected", false);
        if (isLoggedIn) {
            Intent intent = new Intent(MainActivity.this, ReservationActivity.class);
            startActivity(intent);
            return;
        }

        this.emailInput = findViewById(R.id.emailInput);
        this.passwordInput = findViewById(R.id.passwordInput);
        this.loginButton = findViewById(R.id.loginButton);

        this.loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String email = emailInput.getText().toString();
                String password = passwordInput.getText().toString();

                if (email.isEmpty() || password.isEmpty()) {
                    Toast.makeText(MainActivity.this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
                    return;
                }
                RequestQueue queue = Volley.newRequestQueue(MainActivity.this);
                String url = "https://togetherandstronger.site/api/api.php/auth/login";
                JSONObject jsonBody = new JSONObject();
                try {
                    jsonBody.put("email", email);
                    jsonBody.put("password", password);
                } catch (JSONException e) {
                    throw new RuntimeException(e);
                }
                JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, url, jsonBody, new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            if (response.getBoolean("success")) {
                                Toast.makeText(MainActivity.this, "Connexion réussie", Toast.LENGTH_SHORT).show();
                                SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
                                connected.edit().putBoolean("connected", true).apply();
                                connected.edit().putString("token", response.getString("token")).apply();

                                JSONObject data = response.getJSONObject("data");
                                boolean rights = data.getBoolean("rights");

                                if(!rights){
                                    connected.edit().putBoolean("attendee", true).apply();
                                    connected.edit().putString("firstName", data.getString("firstName")).apply();
                                    connected.edit().putString("lastName", data.getString("lastName")).apply();
                                }
                            } else {
                                Toast.makeText(MainActivity.this, response.toString(), Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(MainActivity.this, "Connexion échouée", Toast.LENGTH_SHORT).show();
                    }
                });

                queue.add(request);

                Handler handler = new Handler();
                handler.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        Intent intent = new Intent(MainActivity.this, ReservationActivity.class);
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

                        startActivity(intent);
                    }
                }, 1000);
            }
        });
    }
}
