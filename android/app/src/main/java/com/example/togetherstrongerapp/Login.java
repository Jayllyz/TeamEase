package com.example.togetherstrongerapp;

import static java.lang.String.valueOf;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class Login extends AppCompatActivity {
    private EditText emailInput, passwordInput;
    private Button loginButton;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        this.emailInput = findViewById(R.id.emailInput);
        this.passwordInput = findViewById(R.id.passwordInput);
        this.loginButton = findViewById(R.id.loginButton);

        this.loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String email = emailInput.getText().toString();
                String password = passwordInput.getText().toString();

                if (email.isEmpty() || password.isEmpty()) {
                    Toast.makeText(Login.this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
                    return;
                }
                RequestQueue queue = Volley.newRequestQueue(Login.this);
                String url = "http://10.0.2.2/api/api.php/auth/login";
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
                                Toast.makeText(Login.this, "Connexion réussie", Toast.LENGTH_SHORT).show();
                                SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
                                connected.edit().putBoolean("connected", true).apply();
                                connected.edit().putString("token", response.getString("token")).apply();
                                Toast.makeText(Login.this, valueOf(connected), Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(Login.this, "Connexion échouée", Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(Login.this, "Connexion échouée", Toast.LENGTH_SHORT).show();
                    }
                });

                queue.add(request);
                Intent intent = new Intent(Login.this, MainActivity.class);
                startActivity(intent);
            }
        });
    }
}