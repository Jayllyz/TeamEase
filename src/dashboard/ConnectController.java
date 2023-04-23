package dashboard;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.input.KeyCode;
import javafx.stage.Stage;

public class ConnectController implements Initializable{

    @FXML
    private Button btnConnect;

    @FXML
    private Button btnExit;

    @FXML
    private TextField inputEmail;

    @FXML
    private PasswordField inputPassword;

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        inputEmail.setOnKeyPressed(event -> {
            if (event.getCode() == KeyCode.ENTER) {
                inputPassword.requestFocus();
            }
        });
    
        inputPassword.setOnKeyPressed(event -> {
            if (event.getCode() == KeyCode.ENTER) {
                btnConnect.fire();
            }
        });
    }

    @FXML
    private void handleClicks(ActionEvent event){
        if (event.getSource() == btnConnect) {
            String email = inputEmail.getText();
            String password = inputPassword.getText();
            String token = Token.getToken(email, password);
            System.out.println(token);
            if (token != null && token != "-1" && token != "-2") {
                FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("Dashboard.fxml"));
                Parent root;
                try {
                    root = fxmlLoader.load();
                    DashboardController controller = fxmlLoader.getController();
                    controller.setToken(token);
                    Scene scene = new Scene(root);
                    Stage stage = (Stage) btnConnect.getScene().getWindow();
                    stage.setTitle("Dashboard");
                    stage.setScene(scene);
                    stage.show();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            } else if (token == "-1") {
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Erreur de connexion");
                alert.setHeaderText(null);
                alert.setContentText("L'adresse email ou le mot de passe est incorrect.");

                alert.showAndWait();
            } else if (token == "-2") {
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Erreur de connexion");
                alert.setHeaderText(null);
                alert.setContentText("Votre compte n'est pas un administrateur.");

                alert.showAndWait();
            } else {
                Alert alert = new Alert(AlertType.INFORMATION);
                alert.setTitle("Erreur de connexion");
                alert.setHeaderText(null);
                alert.setContentText("Une erreur est survenue lors de la connexion.");

                alert.showAndWait();
            }
        } else if (event.getSource() == btnExit) {
            System.exit(0);
        }
    }
}
