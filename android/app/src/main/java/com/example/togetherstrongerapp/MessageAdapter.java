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

        TextView name = view.findViewById(R.id.name);
        TextView message = view.findViewById(R.id.message);

        Message current = (Message)getItem(i);

        name.setText(current.getName());
        message.setText(current.getMessage());

        return view;
    }
}
