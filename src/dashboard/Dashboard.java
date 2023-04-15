package dashboard;

import java.awt.BorderLayout;
import javax.swing.JFrame;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.ChartPanel;
import org.jfree.chart.JFreeChart;
import org.jfree.data.general.DefaultPieDataset;


import com.roxstudio.utils.CUrl;

public class Dashboard {
    public static void main(String[] args) {
        CUrl curl = new CUrl("http://localhost/api/api.php/activities/allActivities");
        String response = curl.exec(CUrl.UTF8, null);
        System.out.println(response);

        DefaultPieDataset dataset = new DefaultPieDataset();
        dataset.setValue("Category 1", 43.2);
        dataset.setValue("Category 2", 27.9);
        dataset.setValue("Category 3", 79.5);

        JFreeChart chart = ChartFactory.createPieChart(
            "Dashboard",
            dataset,
            true,
            true,
            false
        );

        ChartPanel chartPanel = new ChartPanel(chart);

        JFrame frame = new JFrame("Dashboard");
        frame.getContentPane().add(chartPanel, BorderLayout.CENTER);
        frame.setSize(500, 500);
        frame.setVisible(true);
    }
}