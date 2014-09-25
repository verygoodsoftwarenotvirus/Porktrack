package com.literallyelvis.porktrack;

import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;


public class Main extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Spinner listSelect = (Spinner) findViewById(R.id.listSelect);
        Spinner timetype = (Spinner) findViewById(R.id.timeType);
        Spinner earlate = (Spinner) findViewById(R.id.earlate);

        ArrayAdapter<CharSequence> tt_adapter = ArrayAdapter.createFromResource(this,
                R.array.timetype_array, android.R.layout.simple_spinner_item);
        tt_adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        timetype.setAdapter(tt_adapter);

        ArrayAdapter<CharSequence> el_adapter = ArrayAdapter.createFromResource(this,
                R.array.earlate_array, android.R.layout.simple_spinner_item);
        el_adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        earlate.setAdapter(el_adapter);

        ArrayAdapter<CharSequence> ls_adapter = ArrayAdapter.createFromResource(this,
                R.array.list_array, android.R.layout.simple_spinner_item);
        ls_adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        listSelect.setAdapter(ls_adapter);

        final Button submit = (Button) findViewById(R.id.submitButton);
        submit.setOnClickListener(new View.OnClickListener(){
            public void onClick(View v){

            }
        });
    }

    public void onCheckboxClicked(View view) {
        boolean checked = ((CheckBox) view).isChecked();

        EditText numberOf = (EditText) findViewById(R.id.numberOf);
        Spinner timetype = (Spinner) findViewById(R.id.timeType);
        Spinner earlate = (Spinner) findViewById(R.id.earlate);

        // Check which checkbox was clicked
        switch (view.getId()) {
            case R.id.pecBirthCheck:
                if (checked) {
                    numberOf.setVisibility(View.VISIBLE);
                    timetype.setVisibility(View.VISIBLE);
                    earlate.setVisibility(View.VISIBLE);
                } else {
                    numberOf.setVisibility(View.INVISIBLE);
                    timetype.setVisibility(View.INVISIBLE);
                    earlate.setVisibility(View.INVISIBLE);
                }
        }
    }

    public String formURL(){
        String  track = null,
                year = null,
                month = null,
                day = null,
                offset = null,
                timetype = null,
                earlate = null;

        EditText numberOf = (EditText) findViewById(R.id.numberOf);
        DatePicker datePicker = (DatePicker) findViewById(R.id.datePicker);
        CheckBox checkBox = (CheckBox) findViewById(R.id.pecBirthCheck);
        Button submitButton = (Button) findViewById(R.id.submitButton);

        String url = "http://porktrack.com/mobile.php?" +
                "list=" + track +
                "&year=" + year +
                "&month=" + month +
                "&day=" + day +
                "&offset=" + offset +
                "&timetype=" + timetype +
                "&earlate=" + earlate;

        return url;
    }

    private class GetData extends AsyncTask<String, Void, JSONObject> {

        String JSONstring = "";
        @Override
        protected JSONObject doInBackground(String... params) {
            String response;

            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpGet httpGet = new HttpGet(params[0]);
                HttpResponse httpResponse = httpclient.execute(httpGet);
                HttpEntity httpEntity = httpResponse.getEntity();

                response = EntityUtils.toString(httpEntity);
                JSONstring = response;
                Log.d("response is", response);

                return new JSONObject(response);

            } catch (Exception ex) {
                ex.printStackTrace();
            }

            return null;
        }

        @Override
        protected void onPostExecute(JSONObject result)
        {
            super.onPostExecute(result);
            Intent intent = new Intent(getBaseContext(), Result.class);
            intent.putExtra("JSON_RESULT", JSONstring);
            startActivity(intent);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
