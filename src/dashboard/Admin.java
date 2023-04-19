package dashboard;

public class Admin {
    public static void main(String[] args) {
        System.setProperty("email", "teamease@gmail.com");
        System.setProperty("password", "Respons11");
    }

    public static String getString(String key) {
        return System.getProperty(key);
    }
}
