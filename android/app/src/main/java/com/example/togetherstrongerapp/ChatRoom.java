package com.example.togetherstrongerapp;

import android.content.Intent;
import android.content.SharedPreferences;
import android.icu.util.Calendar;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
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
    private EditText messageInput;
    private Button send;
    List<Message> chat = new ArrayList<>();

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_chat_room);

        Intent intent = getIntent();
        String name = intent.getStringExtra("name");

        nameChatRoom = findViewById(R.id.nameChatRoom);
        chatMessages = findViewById(R.id.chatMessages);
        messageInput = findViewById(R.id.messageInput);
        send = findViewById(R.id.send);

        nameChatRoom.setText(name);

        MessageAdapter adapter = new MessageAdapter(getChat(), ChatRoom.this);
//        Handler handler = new Handler();
//        handler.postDelayed(new Runnable() {
//            @Override
//            public void run() {
//                Toast.makeText(ChatRoom.this, "Refresh", Toast.LENGTH_LONG).show();
//                chatMessages.setAdapter(adapter);
//            }
//        }, 500);
        this.chatMessages.setAdapter(adapter);

        this.send.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String message = messageInput.getText().toString();

                if (message.isEmpty()) {
                    return;
                }

                RequestQueue queue = Volley.newRequestQueue(ChatRoom.this);
                SharedPreferences chatRoom = getSharedPreferences("chatRoom", MODE_PRIVATE);
                SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
                int id = chatRoom.getInt("id", 0);
                String url = "https://togetherandstronger.site/api/api.php/chat/sendMessage/" + id;

                JSONObject jsonBody = new JSONObject();
                try {
                    jsonBody.put("message", message);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, url, jsonBody, new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            JSONObject json = new JSONObject(response.toString());
                            JSONObject data = json.getJSONObject("data");
                            String firstName = data.getString("firstName");
                            String lastName = data.getString("lastName");

                            Calendar calendar = Calendar.getInstance();
                            int hour = calendar.get(Calendar.HOUR_OF_DAY);
                            int minute = calendar.get(Calendar.MINUTE);
                            String date = hour + ":" + minute;

                            Message message = new Message(
                                    firstName,
                                    lastName,
                                    messageInput.getText().toString(),
                                    date
                            );

                            chat.add(message);

                            MessageAdapter adapter = new MessageAdapter(chat, ChatRoom.this);
                            chatMessages.setAdapter(adapter);
                            messageInput.setText("");

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener(){
                    @Override
                    public void onErrorResponse(VolleyError error){
                        Toast.makeText(ChatRoom.this, "Error: " + error.getMessage(), Toast.LENGTH_LONG).show();
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
    }

    public List<Message> getChat(){
        RequestQueue queue = Volley.newRequestQueue(this);

        SharedPreferences chatRoom = getSharedPreferences("chatRoom", MODE_PRIVATE);
        SharedPreferences connected = getSharedPreferences("connected", MODE_PRIVATE);
        int id = chatRoom.getInt("id", 0);
        String token = connected.getString("token", "");
        String url = "https://togetherandstronger.site/api/api.php/chat/getChat/" + id;

        StringRequest request = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response){
                try {
                    JSONObject json = new JSONObject(response);

                    JSONArray jsonChat = json.getJSONArray("data");

                    for (int i = 0; i < jsonChat.length(); i++) {
                        JSONObject current = jsonChat.getJSONObject(i);

                        Message message = new Message(
                                current.getString("firstName"),
                                current.getString("lastName"),
                                current.getString("content"),
                                current.getString("date")
                        );

                        chat.add(message);
                    }

                    MessageAdapter adapter = new MessageAdapter(chat, ChatRoom.this);
                    chatMessages.setAdapter(adapter);

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener(){
            @Override
            public void onErrorResponse(VolleyError error){
                Toast.makeText(ChatRoom.this, "Error: " + error.getMessage(), Toast.LENGTH_LONG).show();
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
        return chat;
    }
}
