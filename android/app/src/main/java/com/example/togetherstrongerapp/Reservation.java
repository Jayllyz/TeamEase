package com.example.togetherstrongerapp;


public class Reservation {
    private String name, address, city, nameRoom, date, timeStart, duration;
    private int id;

    public Reservation(int id, String name, String address, String city, String nameRoom, String date, String timeStart, String duration) {
        this.id = id;
        this.name = name;
        this.address = address;
        this.city = city;
        this.nameRoom = nameRoom;
        this.date = date;
        this.timeStart = timeStart;
        this.duration = duration;
    }

    public int getId() {
        return id;
    }

    public void setId(int id){
        this.id = id;
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

    public void setDate(String date) {
        this.date = String.valueOf(date);
    }

    public String getTimeStart() {
        return timeStart;
    }

    public void setTimeStart(String timeStart) {
        this.timeStart = String.valueOf(timeStart);
    }

    public String getDuration() {
        return duration;
    }

    public void setDuration(String duration) {
        this.duration = String.valueOf(duration);
    }
}
