package com.fiio.hellohg.pokergame;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;

/**
 * Created by GISI on 20-02-2016.
 */
class Data
{


boolean play;
String message;
 int position;
    int turn;
    String card1;
    String card2;
    String[] arr;
    public Data()
    {}
}


public class JasonParserData {
    String jasonString;
    public JasonParserData(String jString)
    {
        jasonString=jString;
    }
    public Data JsonRead() throws UnsupportedEncodingException, JSONException {

        JSONObject ids = new JSONObject(jasonString);
        Data d=new Data();
        d.play=ids.getBoolean("play");
        d.message=ids.getString("message");
        d.position=ids.getInt("position");
        d.turn=ids.getInt("turn");
        d.card1=ids.getString("c1");
        d.card2=ids.getString("c2");
        return d;
}}
