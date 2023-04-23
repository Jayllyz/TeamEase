package dashboard;

import org.json.*;

import com.roxstudio.utils.CUrl;

public class Token {
    public static String getToken(String email, String password) {
        
        CUrl curl = new CUrl("http://localhost:8000/api/api.php/auth/login");
        String jsonPayload = "{\"email\": \"" + email + "\", \"password\": \"" + password + "\"}";
        curl.opt("-X", "POST");
        curl.data(jsonPayload);
        String response = curl.exec(CUrl.UTF8, null);

        JSONObject obj = new JSONObject(response);

        Boolean success = obj.getBoolean("success");
        if(success == false){
            return "-1";
        }
        String token = obj.getString("token");
        JSONObject data = obj.getJSONObject("data");
        String rights = data.getString("rights");

        System.out.println(rights);

        if(rights.equals("2")){
            return token;
        } else {
            return "-2";
        }
    }
}
