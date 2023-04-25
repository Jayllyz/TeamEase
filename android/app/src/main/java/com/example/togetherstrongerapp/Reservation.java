package com.example.togetherstrongerapp;

import java.sql.Time;
import java.util.Date;

public class Reservation {
    private String name, address, city, nameRoom;
    private String date;
    private String timeStart;
    private String duration;

    public Reservation(String name, String address, String city, String nameRoom, String date, String timeStart, String duration) {
        this.name = name;
        this.address = address;
        this.city = city;
        this.nameRoom = nameRoom;
        this.date = date;
        this.timeStart = timeStart;
        this.duration = duration;
    }


    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getNameRoom() {
        return nameRoom;
    }

    public void setNameRoom(String nameRoom) {
        this.nameRoom = nameRoom;
    }

    public String getDate() {
        return date;
    }

    public void setDate(Date date) {
        this.date = String.valueOf(date);
    }

    public String getTimeStart() {
        return timeStart;
    }

    public void setTimeStart(Time timeStart) {
        this.timeStart = String.valueOf(timeStart);
    }

    public String getDuration() {
        return duration;
    }

    public void setDuration(Integer duration) {
        this.duration = String.valueOf(duration);
    }
}
