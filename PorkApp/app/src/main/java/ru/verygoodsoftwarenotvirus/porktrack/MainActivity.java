package ru.verygoodsoftwarenotvirus.porktrack;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.ActionBar;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.os.Build;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;

import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.AdView;

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
import java.util.Date;


public class MainActivity extends ActionBarActivity {

    String url = "";
    String results = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

       if (savedInstanceState == null) {
            getSupportFragmentManager().beginTransaction()
                    .add(R.id.container, new UserEntryFragment())
                    .add(R.id.container, new AdFragment())
                    .commit();
        }

        DatePicker datePicker = (DatePicker) findViewById(R.id.datePicker);
        datePicker.setMaxDate(new Date().getTime());

        EditText numberOf = (EditText) findViewById(R.id.numberOf);
        Spinner timetype = (Spinner) findViewById(R.id.timeType);
        Spinner earlate = (Spinner) findViewById(R.id.earlate);
        Spinner listSelect = (Spinner) findViewById(R.id.listSelect);

        Button submitButton = (Button) findViewById(R.id.porkButton);

        // Create an ArrayAdapter using the string array and a default spinner layout
        ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(this,
                R.array.list_array, android.R.layout.simple_spinner_item);
        // Specify the layout to use when the list of choices appears
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        // Apply the adapter to the spinner
        listSelect.setAdapter(adapter);

        adapter = ArrayAdapter.createFromResource(this,
                R.array.timetype_array, android.R.layout.simple_spinner_item);
        // Apply the adapter to the spinner
        timetype.setAdapter(adapter);

        adapter = ArrayAdapter.createFromResource(this,
                R.array.earlate_array, android.R.layout.simple_spinner_item);
        // Apply the adapter to the spinner
        earlate.setAdapter(adapter);

        numberOf.setVisibility(View.INVISIBLE);
        timetype.setVisibility(View.INVISIBLE);
        earlate.setVisibility(View.INVISIBLE);

        CheckBox peculiarCheck = (CheckBox) findViewById(R.id.peculiarCheck);

        peculiarCheck.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                EditText numberOf = (EditText) findViewById(R.id.numberOf);
                Spinner timetype = (Spinner) findViewById(R.id.timeType);
                Spinner earlate = (Spinner) findViewById(R.id.earlate);
                if (isChecked) {
                    numberOf.setVisibility(View.VISIBLE);
                    timetype.setVisibility(View.VISIBLE);
                    earlate.setVisibility(View.VISIBLE);
                } else {
                    numberOf.setVisibility(View.INVISIBLE);
                    timetype.setVisibility(View.INVISIBLE);
                    earlate.setVisibility(View.INVISIBLE);
                }
            }
        });

        submitButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePicker datePicker = (DatePicker) findViewById(R.id.datePicker);
                EditText numberOf = (EditText) findViewById(R.id.numberOf);
                Spinner timetype = (Spinner) findViewById(R.id.timeType);
                Spinner earlate = (Spinner) findViewById(R.id.earlate);
                Spinner listSelect = (Spinner) findViewById(R.id.listSelect);

                String numOf = numberOf.getText().toString();
                if (numOf.length() == 0) {
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

                url = "http://www.porktrack.com/mobile.php?" +
                        "list=" + list +
                        "&year=" + datePicker.getYear() +
                        "&month=" + month +
                        "&day=" + datePicker.getDayOfMonth() +
                        "&offset=" + numOf +
                        "&timetype=" + timetype.getSelectedItem().toString() +
                        "&earlate=" + earlate.getSelectedItem().toString();


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
            HttpClient httpClient = new DefaultHttpClient();
            HttpPost httpPost = new HttpPost(url);

            ArrayList<NameValuePair> param = new ArrayList<NameValuePair>();

            try {
                httpPost.setEntity(new UrlEncodedFormEntity(param));

                HttpResponse httpResponse = httpClient.execute(httpPost);
                HttpEntity httpEntity = httpResponse.getEntity();

                //read content
                instream =  httpEntity.getContent();
            } catch (Exception e) {
                // TODO: handle exception
                Log.e("log_tag", "Error in http connection " + e.toString());
            }

            try {
                BufferedReader br = new BufferedReader(new InputStreamReader(instream));
                results = br.readLine();
                instream.close();
            } catch (Exception e) {
                // TODO: handle exception
                Log.e("log_tag", "Error converting result "+e.toString());
            }

            return null;

        }
        protected void onPostExecute(Void v) {
            try {
                JSONObject jObject = new JSONObject(results);
                String artist = jObject.getString("artist");
                String title = jObject.getString("title");
                String vid = jObject.getString("vid");
                String result = "You were (probably) conceived to " + title + " by " + artist + "!";



            } catch (JSONException e) {
                Log.e("JSON stuff", "ERROR");
                e.printStackTrace();
            }
        }
    }

    public static class UserEntryFragment extends Fragment {

        public UserEntryFragment() {
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            return inflater.inflate(R.layout.fragment_main, container, false);
        }
    }

    public static class ResultsFragment extends Fragment {

        public ResultsFragment() {
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            return inflater.inflate(R.layout.fragment_result, container, false);
        }
    }

    public static class AdFragment extends Fragment {
        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            return inflater.inflate(R.layout.fragment_ad, container, false);
        }

        @Override
        public void onActivityCreated(Bundle bundle) {
            super.onActivityCreated(bundle);
            AdView mAdView = (AdView) getView().findViewById(R.id.adView);
            AdRequest adRequest = new AdRequest.Builder().build();
            mAdView.loadAd(adRequest);
        }
    }
}
