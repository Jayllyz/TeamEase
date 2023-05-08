package com.example.togetherstrongerapp;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
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

public class ReservationActivity extends AppCompatActivity {

    private ListView list;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_reservation);

        this.list = findViewById(R.id.list);

        SharedPreferences preferences = getSharedPreferences("connected", MODE_PRIVATE);
        String token = preferences.getString("token", "");

        getReservations(token, new ReservationsCallback() {
            @Override
            public void onReservationsReceived(List<Reservation> reservations, String response) throws JSONException {
                JSONObject json = new JSONObject(response);

                JSONArray jsonReserv = json.getJSONArray("data");

                for (int i = 0; i < jsonReserv.length(); i++) {
                    JSONObject current = jsonReserv.getJSONObject(i);

                    Reservation reservation = new Reservation(current.getString("nameActivity"),
                            current.getString("address"), current.getString("city"),
                            current.getString("nameRoom"), current.getString("date"),
                            current.getString("time"), current.getString("duration"));
                    reservations.add(reservation);
                }

                ReservationAdapter adapter = new ReservationAdapter(reservations, ReservationActivity.this);
                list.setAdapter(adapter);
                list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                    @Override
                    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                        Reservation selected = reservations.get(i);
                        startChatRoom(selected.getName());
                    }
                });
            }
        });
    }

    private void startChatRoom(String name) {
        setContentView(R.layout.activity_chat_room);
        TextView nameChatRoom;
        nameChatRoom = findViewById(R.id.nameChatRoom);
        nameChatRoom.setText(name);
    }


    public interface ReservationsCallback {
        void onReservationsReceived(List<Reservation> reservations, String response) throws JSONException;
    }

    public void getReservations(String token, ReservationsCallback callback) {

        List<Reservation> reservations = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(this);
        String url = "https://togetherandstronger.site/api/api.php/company";
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
}

