package com.example.togetherstrongerapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.TextView;

import java.util.List;

public class ReservationAdapter extends BaseAdapter {

    private List<Reservation> catalog;
    private Context context;

    public ReservationAdapter(List<Reservation> catalog, Context context) {
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
            view = inflater.inflate(R.layout.activity_reserv_item, null);
        }

        TextView name = view.findViewById(R.id.name);
        TextView date = view.findViewById(R.id.date);
        TextView duration = view.findViewById(R.id.duration);
        TextView hour = view.findViewById(R.id.heure);
        TextView nameRoom = view.findViewById(R.id.nameRoom);
        TextView address = view.findViewById(R.id.address);
        TextView city = view.findViewById(R.id.city);


        Reservation current = (Reservation)getItem(i);

        name.setText(current.getName());
        date.setText(current.getDate());
        duration.setText(current.getDuration());
        hour.setText(current.getTimeStart());
        nameRoom.setText(current.getNameRoom());
        address.setText(current.getAddress());
        city.setText(current.getCity());


        return view;
    }
}