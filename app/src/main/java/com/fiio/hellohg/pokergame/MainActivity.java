package com.fiio.hellohg.pokergame;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
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
import com.facebook.CallbackManager;
import com.facebook.FacebookCallback;
import com.facebook.FacebookException;
import com.facebook.FacebookSdk;
import com.facebook.GraphRequest;
import com.facebook.GraphResponse;
import com.facebook.login.LoginResult;
import com.facebook.login.widget.LoginButton;

import org.json.JSONException;
import org.json.JSONObject;

import java.net.URL;
import java.util.HashMap;
import java.util.Map;

public class MainActivity extends Activity implements View.OnClickListener{
    LoginButton loginButton;
    TextView textView;
    String userId;
    int usersendID;
    URL imageURL = null;
    String user_Id;
    CallbackManager callbackManager;
    String userName;
    String email;
    Button user;
    int value;
    SharedPreferences.Editor editor;
    SharedPreferences sharedPreferences;
//    String TAG="string";

    @Override

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        FacebookSdk.sdkInitialize(getApplicationContext());
        setContentView(R.layout.activity_main);
        sharedPreferences=getApplicationContext().getSharedPreferences("poker",MODE_PRIVATE);
        editor=sharedPreferences.edit();
        user=(Button)findViewById(R.id.user);
        user.setOnClickListener(this);
        callbackManager = CallbackManager.Factory.create();
        loginButton = (LoginButton) findViewById(R.id.login_button);
        Log.i("fb", "inflating loginbuton");
        loginButton.setReadPermissions("public_profile", "email");
        Log.i("fb", "set permissionsk");
        loginButton.registerCallback(callbackManager, new FacebookCallback<LoginResult>() {
            @Override
            public void onSuccess(LoginResult loginResult) {
                Log.i("fb", "on success");
               final String userId = loginResult.getAccessToken().getUserId();

                Log.i("userid", userId);
                String token = loginResult.getAccessToken().getToken();
                Log.i("token", token);
                int i = loginResult.getAccessToken().describeContents();
                Log.i("contentsdescribe", i + "");

                GraphRequest request = GraphRequest.newMeRequest(
                        loginResult.getAccessToken(),
                        new GraphRequest.GraphJSONObjectCallback() {
                            @Override
                            public void onCompleted(
                                    JSONObject object,
                                    GraphResponse response) {
                                // Application code
                                Log.v("LoginActivity", response.toString());
                                Log.v("LoginActivity", object.toString());
                                try {
                                    userName = object.getString("name");
                                    email = object.getString("email");
                                   // URL imageURL = new URL("https://graph.facebook.com/" + userId + "/picture?type=large");
                                   // Bitmap image = BitmapFactory.decodeStream(imageURL.openConnection().getInputStream());
                                   // Drawable d = new BitmapDrawable(getResources(), image);

                                   //usersendID= insertToDatabase(userName);

                                } catch (JSONException e) {
                                }

                            }
                        });
                Bundle parameters = new Bundle();
                parameters.putString("fields", "id,name,email,gender");
                request.setParameters(parameters);
                request.executeAsync();
//                Intent intent=new Intent(getApplicationContext(), profilepage.class);
//                intent.putExtra("username",userName);
//                intent.putExtra("emailid",email);
//                intent.putExtra("userID",usersendID);
                //startActivity(intent);
            }

            @Override
            public void onCancel() {
                Log.i("fb", "on cancel");
            }


            @Override
            public void onError(FacebookException e) {
                Log.i("fb", "on error" + e);
            }
        });


    }
        @Override
        protected void onActivityResult ( int requestCode, int resultCode, Intent data){
            super.onActivityResult(requestCode, resultCode, data);
            Log.i("fb", "on activity result");
            callbackManager.onActivityResult(requestCode, resultCode, data);
            Bundle bundle = data.getExtras();
            Log.i("result", bundle.toString());
        }

       public void insertToDatabase()
       {
           RequestQueue requestQueue;

           final String TAG="VolleyRequest";
           TextView tv;
           String url="http://10.60.30.52/poker/www/initUser.php";
           StringRequest strReq = new StringRequest(Request.Method.POST,
                   url,new Response.Listener<String>() {

               @Override
               public void onResponse(String response) {
                   Log.i(TAG, response.toString());
                   Log.i("Response","received");

                   try {
                       JSONObject responseObj = new JSONObject(response);
                       Log.i("Json","Parsing Json Response");
                       // Parsing json object response
                       // response will be a json object
                       value=responseObj.getInt("user_id");

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
                   params.put("username","name");
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

    @Override
    public void onClick(View view) {
        if(view==user){
          insertToDatabase();
            editor.putInt("user_id",value);
        }
    }
}


