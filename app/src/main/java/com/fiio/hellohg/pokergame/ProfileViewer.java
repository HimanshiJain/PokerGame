package com.fiio.hellohg.pokergame;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.widget.EditText;

public class ProfileViewer extends Activity {

    String username;
    String email;
    EditText e1,e2;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile_viewer);
        e1=(EditText)findViewById(R.id.editText2);
        e2=(EditText)findViewById(R.id.editText3);
        Intent myIntent=getIntent();
        username=myIntent.getStringExtra("username");
        email=myIntent.getStringExtra("email");
        e1.setText(username);
        e2.setText(email);
    }


}
