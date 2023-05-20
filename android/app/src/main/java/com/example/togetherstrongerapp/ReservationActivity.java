package com.example.togetherstrongerapp;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.ServerError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class ReservationActivity extends AppCompatActivity {

    private ListView list;
    private Button logout;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_reservation);

        this.list = findViewById(R.id.list);
        this.logout = findViewById(R.id.logout);

        SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
        String token = connected.getString("token", "");

        this.logout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
                SharedPreferences.Editor editor = connected.edit();
                editor.clear();
                editor.apply();
                Intent intent = new Intent(ReservationActivity.this, MainActivity.class);
                startActivity(intent);
            }
        });

        getAttendance();
    }

    private void startChatRoom(String name) {
        Intent intent = new Intent(ReservationActivity.this, ChatRoom.class);
        intent.putExtra("name", name);
        startActivity(intent);
    }

    private void getAttendance(){
        RequestQueue queue = Volley.newRequestQueue(ReservationActivity.this);
        SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
        String url = "https://togetherandstronger.site/api/api.php/user/getAttendance";

        StringRequest request = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject json = new JSONObject(response);
                    JSONObject data = json.getJSONObject("data");
                    int reservationId = data.getInt("id");

                    AlertDialog.Builder builder = new AlertDialog.Builder(ReservationActivity.this);
                    builder.setTitle("Confirmation présence");
                    builder.setMessage("Confirmer votre présence !");
                    builder.setPositiveButton("Je suis là !", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i) {
                            RequestQueue queue = Volley.newRequestQueue(ReservationActivity.this);

                            SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);

                            String url = "https://togetherandstronger.site/api/api.php/user/validateAttendance/" + reservationId;

                            StringRequest request =  new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
                                @Override
                                public void onResponse(String response) {
                                    Toast.makeText(ReservationActivity.this, "Présence confirmé", Toast.LENGTH_LONG).show();
                                }
                            }, new Response.ErrorListener(){
                                @Override
                                public void onErrorResponse(VolleyError error){
                                    Log.d("Error", error.toString());
                                }
                            }) {
                                @Override
                                public Map<String, String> getHeaders() throws AuthFailureError {
                                    String token = connected.getString("token", "");
                                    Map<String, String> params = new HashMap<String, String>();
                                    params.put("Authorization", token);
                                    return params;
                                }
                            };
                            queue.add(request);
                        }


                    });
                    AlertDialog dialog = builder.create();
                    dialog.show();


                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener(){
            @Override
            public void onErrorResponse(VolleyError error){
                Log.d("Error", error.toString());
            }
        }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                String token = connected.getString("token", "");
                Map<String, String> params = new HashMap<String, String>();
                params.put("Authorization", token);
                return params;
            }
        };

        queue.add(request);
    }

    public interface ReservationsCallback {
        void onReservationsReceived(List<Reservation> reservations, String response) throws JSONException;
    }

    public void getReservations(String token, ReservationsCallback callback) {

        List<Reservation> reservations = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(this);

        SharedPreferences preferences = getSharedPreferences("connected", MODE_PRIVATE);
        Boolean attendee = preferences.getBoolean("attendee", false);

        String url;
        if(attendee){
            url = "https://togetherandstronger.site/api/api.php/user/getUserActivities";
        } else {
            url = "https://togetherandstronger.site/api/api.php/company";
        }
        StringRequest request = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    callback.onReservationsReceived(reservations, response);
                } catch (JSONException e) {
                    throw new RuntimeException(e);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error instanceof ServerError && error.networkResponse != null && error.networkResponse.statusCode == 404) {
                    String responseMessage = null;
                    if (error.networkResponse.data != null) {
                        try {
                            responseMessage = new String(error.networkResponse.data, "UTF-8");
                            JSONObject jsonResponse = new JSONObject(responseMessage);
                            String message = jsonResponse.getString("message");

                            if ("No activity found".equals(message)) {
                                return;
                            } else {
                                SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
                                SharedPreferences.Editor editor = connected.edit();
                                editor.clear();
                                editor.apply();
                                Intent intent = new Intent(ReservationActivity.this, MainActivity.class);
                                startActivity(intent);
                            }
                        } catch (UnsupportedEncodingException | JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }
                error.printStackTrace();
            }
        }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("Authorization", token);
                return params;
            }
        };
        queue.add(request);
    }
    
    @Override
    public void onResume(){
        super.onResume();
        SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
        String token = connected.getString("token", "");
        getReservations(token, new ReservationsCallback() {
            @Override
            public void onReservationsReceived(List<Reservation> reservations, String response) throws JSONException {
                JSONObject json = new JSONObject(response);

                JSONArray jsonReserv = json.getJSONArray("data");

                for (int i = 0; i < jsonReserv.length(); i++) {
                    JSONObject current = jsonReserv.getJSONObject(i);

                    Reservation reservation = new Reservation(
                            current.getInt("id"),
                            current.getString("nameActivity"),
                            current.getString("address"),
                            current.getString("city"),
                            current.getString("nameRoom"),
                            current.getString("date"),
                            current.getString("time"),
                            current.getString("duration")
                    );
                    reservations.add(reservation);
                }

                ReservationAdapter adapter = new ReservationAdapter(reservations, ReservationActivity.this);
                list.setAdapter(adapter);
                list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                    @Override
                    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                        Reservation selected = reservations.get(i);
                        SharedPreferences chatRoom = getSharedPreferences("chatRoom", MODE_PRIVATE);
                        chatRoom.edit().putInt("id", selected.getId()).apply();
                        startChatRoom(selected.getName());
                    }
                });
            }
        });
    }
}

