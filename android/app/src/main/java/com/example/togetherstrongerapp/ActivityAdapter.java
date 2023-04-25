package com.example.togetherstrongerapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.List;

public class ActivityAdapter extends BaseAdapter {

    private List<Activity> catalog;
    private Context context;

    public ActivityAdapter(List<Activity> catalog, Context context) {
        this.catalog = catalog;
        this.context = context;
    }

    @Override
    public int getCount() {
        return this.catalog.size();
    }

    @Override
    public Object getItem(int i) {
        return this.catalog.get(i);
    }

    @Override
    public long getItemId(int i) {
        return 0;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(view == null){
            LayoutInflater inflater = LayoutInflater.from(this.context);
            view = inflater.inflate(R.layout.activity_item, null);
        }

        TextView name = view.findViewById(R.id.name);
        TextView description = view.findViewById(R.id.description);

        Activity current = (Activity)getItem(i);

        name.setText(current.getName());
        description.setText(current.getDescription());

        return view;
    }
}