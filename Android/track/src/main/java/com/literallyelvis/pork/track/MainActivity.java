package com.literallyelvis.pork.track;

import android.app.Activity;
import android.app.DownloadManager;
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
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;


public class MainActivity extends Activity {
    String fart = "fart";
    String butt = "http://www.porktrack.com/mobile.php?";
    TextView results;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        EditText numberOf = (EditText) findViewById(R.id.numberOf);
        Spinner timetype = (Spinner) findViewById(R.id.timeType);
        Spinner earlate = (Spinner) findViewById(R.id.earlate);
        Spinner listSelect = (Spinner) findViewById(R.id.listSelect);

        Button submitButton = (Button) findViewById(R.id.submitButton);

        ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(this,
                R.array.timetype_array, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        timetype.setAdapter(adapter);

        adapter = ArrayAdapter.createFromResource(this,
                R.array.earlate_array, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        earlate.setAdapter(adapter);

        adapter = ArrayAdapter.createFromResource(this,
                R.array.list_array, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        listSelect.setAdapter(adapter);

        numberOf.setVisibility(View.INVISIBLE);
        timetype.setVisibility(View.INVISIBLE);
        earlate.setVisibility(View.INVISIBLE);

        String url = "http://www.porktrack.com/mobile.php?list=track&year=1989&month=08&day=04&offset=10&timetype=days&earlate=early";

        submitButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePicker datePicker = (DatePicker) findViewById(R.id.datePicker);
                EditText numberOf = (EditText) findViewById(R.id.numberOf);
                Spinner timetype = (Spinner) findViewById(R.id.timeType);
                Spinner earlate = (Spinner) findViewById(R.id.earlate);
                Spinner listSelect = (Spinner) findViewById(R.id.listSelect);

                String numOf = numberOf.getText().toString();
                if( numOf.length() == 0 ) {
                    numOf = "0";
                }

                String list = listSelect.getSelectedItem().toString();
                if (list.equals("Hot 100")) {
                    list = "track";
                } else {
                    list = listSelect.getSelectedItem().toString().toLowerCase();
                }

                int month = datePicker.getMonth();
                month++;

                String url = "http://www.porktrack.com/mobile.php?" +
                      "list=" + list +
                      "&year=" + datePicker.getYear() +
                      "&month=" + month +
                      "&day=" + datePicker.getDayOfMonth() +
                      "&offset=" + numOf +
                      "&timetype=" + timetype.getSelectedItem().toString() +
                      "&earlate=" + earlate.getSelectedItem().toString();

                butt = url;

                new task().execute();

            }
        });
    }

    // Stolen from http://stackoverflow.com/questions/22793638/android-accessing-remote-mysql-database
    // because Android development is trash.
    class task extends AsyncTask<String, String, Void>
    {
        InputStream instream = null ;
        String result = "";
        @Override
        protected Void doInBackground(String... params) {
            String url_select = butt;

            HttpClient httpClient = new DefaultHttpClient();
            HttpPost httpPost = new HttpPost(url_select);

            ArrayList<NameValuePair> param = new ArrayList<NameValuePair>();

            try {
                httpPost.setEntity(new UrlEncodedFormEntity(param));

                HttpResponse httpResponse = httpClient.execute(httpPost);
                HttpEntity httpEntity = httpResponse.getEntity();

                //read content
                instream =  httpEntity.getContent();
            } catch (Exception e) {
                // TODO: handle exception
                Log.e("log_tag", "Error in http connection "+e.toString());
            }

            try {
                BufferedReader br = new BufferedReader(new InputStreamReader(instream));
                fart = br.readLine();
                instream.close();
            } catch (Exception e) {
                // TODO: handle exception
                Log.e("log_tag", "Error converting result "+e.toString());
            }

            return null;

        }
        protected void onPostExecute(Void v) {
            try {
                JSONObject jObject = new JSONObject(fart);
                String artist = jObject.getString("artist");
                String title = jObject.getString("title");
                String vid = jObject.getString("vid");
                String result = "You were (probably) conceived to " + title + " by " + artist + "!";
                Intent intent = new Intent(getBaseContext(), ResultActivity.class);
                intent.putExtra("RESULT", result);
                startActivity(intent);
            } catch (JSONException e) {
                Log.e("JSON stuff", "ERROR");
                e.printStackTrace();
            }
        }
    }

    public void onCheckboxClicked(View view) {
        // Is the view now checked?
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
                break;
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
