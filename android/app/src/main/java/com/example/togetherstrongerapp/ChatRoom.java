package com.example.togetherstrongerapp;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
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

public class ChatRoom extends AppCompatActivity {

    private TextView nameChatRoom;
    private ListView chatMessages;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_chat_room);

        Intent intent = getIntent();
        String name = intent.getStringExtra("name");

        nameChatRoom = findViewById(R.id.nameChatRoom);
        chatMessages = findViewById(R.id.chatMessages);

        nameChatRoom.setText(name);

        MessageAdapter adapter = new MessageAdapter(getChat(), getApplicationContext());
        this.chatMessages.setAdapter(adapter);
    }

    public List<Message> getChat(){
        List<Message> chat = new ArrayList<>();

        SharedPreferences preferences = getSharedPreferences("chatRoom", MODE_PRIVATE);
        SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
        int id = preferences.getInt("id", 0);
        String token = connected.getString("token", "");
        String url = "https://togetherandstronger.site/api/api.php/chat/getChat/" + id;

        Log.d("URL", url);

        StringRequest request = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response){
                try {
                    JSONObject json = new JSONObject(response);

                    Toast.makeText(ChatRoom.this, "Response: " + response, Toast.LENGTH_LONG).show();
                    JSONArray jsonChat = json.getJSONArray("data");

                    for (int i = 0; i < jsonChat.length(); i++) {
                        JSONObject current = jsonChat.getJSONObject(i);

                        Message message = new Message(
                                current.getString("firstName"),
                                current.getString("lastName"),
                                current.getString("message"),
                                current.getString("date")
                        );

                        chat.add(message);
                    }

                    MessageAdapter adapter = new MessageAdapter(chat, getApplicationContext());
                    chatMessages.setAdapter(adapter);

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener(){
            @Override
            public void onErrorResponse(VolleyError error){
                Toast.makeText(getApplicationContext(), "Error: " + error.getMessage(), Toast.LENGTH_LONG).show();
            }
        })
        {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("Authorization", token);
                return params;
            }
        };

        return chat;
    }
}
