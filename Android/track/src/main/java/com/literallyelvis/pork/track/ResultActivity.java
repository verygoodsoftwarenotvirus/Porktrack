package com.literallyelvis.pork.track;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

/**
 * Created by elvis on 12/2/14.
 */

public class ResultActivity extends Activity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_result);
        Intent intent = getIntent();
        String results = intent.getStringExtra("RESULT");
        TextView result = (TextView) findViewById(R.id.resultsText);
        result.setText(results);
    }
}
