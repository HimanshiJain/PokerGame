package com.fiio.hellohg.pokergame;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;

/**
 * Created by GISI on 20-02-2016.
 */
public class JasonParsing {
    String jasonString;
    Integer[] resultStrs = new Integer[2];
    public JasonParsing(String jString)
    {
        jasonString=jString;
    }
    public Integer[] JsonRead() throws UnsupportedEncodingException, JSONException {

        JSONObject ids = new JSONObject(jasonString);
        int userId=ids.getInt("user_id");
        int table_id=ids.getInt("player_id");            ///// NAME OF ROW
        resultStrs[0]=userId;
        resultStrs[1]=table_id;
        return resultStrs;
    }
}
