package dashboard;

import com.roxstudio.utils.CUrl;

public class Test {
    public void main(String[] args) {
        CUrl curl = new CUrl("http://httpbin.org/post")
                .data("hello=world&foo=bar")
                .data("foo=overwrite");
        String response = curl.exec(CUrl.UTF8, null);
        System.out.println(response);
    }
}
