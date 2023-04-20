package com.example.togetherstrongerapp;

public class Activity {
    private String name, description;
    int maxAttendee, duration, priceAttendee;

    public Activity(String name, String description, int maxAttendee, int duration, int priceAttendee) {
        this.name = name;
        this.description = description;
        this.maxAttendee = maxAttendee;
        this.duration = duration;
        this.priceAttendee = priceAttendee;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public int getMaxAttendee() {
        return maxAttendee;
    }

    public void setMaxAttendee(int maxAttendee) {
        this.maxAttendee = maxAttendee;
    }

    public int getDuration() {
        return duration;
    }

    public void setDuration(int duration) {
        this.duration = duration;
    }

    public int getPriceAttendee() {
        return priceAttendee;
    }

    public void setPriceAttendee(int priceAttendee) {
        this.priceAttendee = priceAttendee;
    }
}
