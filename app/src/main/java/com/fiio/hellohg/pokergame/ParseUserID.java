package com.fiio.hellohg.pokergame;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;

/**
 * Created by GISI on 20-02-2016.
 */
public class ParseUserID {
    String jasonString;

    public ParseUserID(String jString) {
        jasonString = jString;
    }

    public int JsonRead() throws UnsupportedEncodingException, JSONException {

        JSONObject temp = new JSONObject(jasonString);
        JSONObject ids = temp.getJSONObject("ids");
        int id=ids.getInt("user_id");
        return id;
    }
}