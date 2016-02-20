package com.fiio.hellohg.pokergame;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NetworkError;
import com.android.volley.NoConnectionError;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.RetryPolicy;
import com.android.volley.ServerError;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

//import org.apache.http.HttpEntity;
//import org.apache.http.HttpResponse;
//import org.apache.http.NameValuePair;
//import org.apache.http.client.ClientProtocolException;
//import org.apache.http.client.HttpClient;
//import org.apache.http.client.entity.UrlEncodedFormEntity;
//import org.apache.http.client.methods.HttpPost;
//import org.apache.http.impl.client.DefaultHttpClient;
//import org.apache.http.message.BasicNameValuePair;

public class profilepage extends Activity {
Button tut;
    String username;
    String email;
    String userIdplayerId;
    int userSendId;
    Integer ids[] = new Integer[2];

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profilepage);
        tut = (Button) findViewById(R.id.tutorial);
        tut.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse("https://www.youtube.com/watch?v=cnm_V7A-G6c")));


            }
        });
        Intent myIntent = getIntent();
        username = myIntent.getStringExtra("username");
        email = myIntent.getStringExtra("emailid");
        userSendId=myIntent.getIntExtra("userID",0);

    }

    public void play(View v) {


        RequestQueue requestQueue;

        final String TAG="VolleyRequest";
        TextView tv;
        String url="10.60.30.52/";
        StringRequest strReq = new StringRequest(Request.Method.GET,
                url,new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.i(TAG, response.toString());
                Log.i("Response","received");

                try {
                    JasonParsing j = new JasonParsing(response);

                    ids = j.JsonRead();
                    getValues(ids);



                } catch (JSONException e) {
                    Log.i(TAG, "Error: JSON EXception");
                } catch (UnsupportedEncodingException e) {
                    e.printStackTrace();
                }

            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                if (error instanceof TimeoutError) {
                    Log.i(TAG, "Error: TimeoutError " + error.getMessage());
                }else if( error instanceof NoConnectionError){
                    Log.i(TAG, "Error: NoConnectionError" + error.getMessage());
                }
                else if (error instanceof AuthFailureError) {
                    Log.i(TAG, "Error: AuthFailureError " + error.getMessage());
                } else if (error instanceof ServerError) {
                    Log.i(TAG, "Error: ServerError " + error.getMessage());
                } else if (error instanceof NetworkError) {
                    Log.i(TAG, "Error: NetworkError " + error.getMessage());
                } else if (error instanceof ParseError) {
                    Log.i(TAG, "Error: ParseError " + error.getMessage());
                }

            }
        })
        {



            /**
             * Passing user parameters to our server
             * @return
             */
            @Override
            protected Map<String,String> getParams() {
                Map<String, String> params = new HashMap<String,String>();
                params.put("user_id", String.valueOf(userSendId));

                return params;
            }


        }
                ;
        //JsonObjectRequest jsonObjectRequest=new JsonObjectRequest(Request.Method.POST,Config.URL_REQUEST_SMS,null,)

        int socketTimeout = 60000;
        RetryPolicy policy = new DefaultRetryPolicy(socketTimeout,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT);
        strReq.setRetryPolicy(policy);
        Log.i("Request", strReq.toString());
        // Adding request to request queue
        requestQueue = Volley.newRequestQueue(getApplicationContext());
        requestQueue.add(strReq);


    }


    public void getValues(Integer[] ids)
    {
        Integer[] idValues=ids;
        final int cardtableID=idValues[0];
        final int playerID=idValues[1];

        RequestQueue requestQueue;

        final String TAG="VolleyRequest";
        TextView tv;

        String url="10.60.30.52/";
        StringRequest strReq = new StringRequest(Request.Method.GET,
                url,new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.i(TAG, response.toString());
                Log.i("Response","received");

                try {
                    JSONObject responseObj = new JSONObject(response);
                    Data d=new Data();
                    d.play=responseObj.getBoolean("play");
                    d.card1=responseObj.getString("c1");
                    d.card2=responseObj.getString("c2");
                    d.message=responseObj.getString("mesaage");
                    d.position=responseObj.getInt("position");
                    d.turn=responseObj.getInt("turn");
                    JSONObject j=responseObj.getJSONObject("arr");

                } catch (JSONException e) {
                    Log.i(TAG, "Error: JSON EXception");
                }

            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                if (error instanceof TimeoutError) {
                    Log.i(TAG, "Error: TimeoutError " + error.getMessage());
                }else if( error instanceof NoConnectionError){
                    Log.i(TAG, "Error: NoConnectionError" + error.getMessage());
                }
                else if (error instanceof AuthFailureError) {
                    Log.i(TAG, "Error: AuthFailureError " + error.getMessage());
                } else if (error instanceof ServerError) {
                    Log.i(TAG, "Error: ServerError " + error.getMessage());
                } else if (error instanceof NetworkError) {
                    Log.i(TAG, "Error: NetworkError " + error.getMessage());
                } else if (error instanceof ParseError) {
                    Log.i(TAG, "Error: ParseError " + error.getMessage());
                }

            }
        })
        {



            /**
             * Passing user parameters to our server
             * @return
             */
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("player_id",String.valueOf(playerID));
                params.put("carttable_id",String.valueOf(cardtableID));

                Log.e(TAG, "Posting params: " + params.toString());

                return params;
            }


        }
                ;
        //JsonObjectRequest jsonObjectRequest=new JsonObjectRequest(Request.Method.POST,Config.URL_REQUEST_SMS,null,)

        int socketTimeout = 60000;
        RetryPolicy policy = new DefaultRetryPolicy(socketTimeout,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT);
        strReq.setRetryPolicy(policy);
        Log.i("Request", strReq.toString());
        // Adding request to request queue
        requestQueue = Volley.newRequestQueue(getApplicationContext());
        requestQueue.add(strReq);


    }

    public void view(View v) {
        Intent intent = new Intent(this, ProfileViewer.class);
        intent.putExtra("username", username);
        intent.putExtra("emailid", email);
        startActivity(intent);
    }

}
