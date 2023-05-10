package com.example.togetherstrongerapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.List;

public class MessageAdapter extends BaseAdapter {
    private List<Message> chatMessages;
    private Context context;

    public MessageAdapter(List<Message> chatMessages, Context context) {
        this.chatMessages = chatMessages;
        this.context = context;
    }

    @Override
    public int getCount() {
        return this.chatMessages.size();
    }

    @Override
    public Object getItem(int i) {
        return this.chatMessages.get(i);
    }

    @Override
    public long getItemId(int i) {
        return 0;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            LayoutInflater inflater = LayoutInflater.from(this.context);
            view = inflater.inflate(R.layout.activity_message, null);
        }

        TextView firstName = view.findViewById(R.id.firstName);
        TextView lastName = view.findViewById(R.id.lastName);
        TextView message = view.findViewById(R.id.message);
        TextView date = view.findViewById(R.id.date);

        Message current = (Message)getItem(i);

        firstName.setText(current.getFirstName());
        lastName.setText(current.getLastName());
        message.setText(current.getMessage());
        date.setText(current.getDate());

        return view;
    }
}
