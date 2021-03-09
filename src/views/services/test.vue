<template>
  <v-container>
    <div class="hello" id="chartdiv"></div>
  </v-container>
</template>

<script>
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default {
  name: "HelloWorld",
  data: () => ({
datatest: [
      {
        country: "Lithuania",
        litres: 501.9,
      },
      {
        country: "Czech Republic",
        litres: 301.9,
      },
      {
        country: "Ireland",
        litres: 201.1,
      },
      {
        country: "Germany",
        litres: 165.8,
      },
      {
        country: "Australia",
        litres: 139.9,
      },      
      {
        country: "Australia",
        litres: 139.9,
      },
    ]
  }),
  mounted() {
    let chart = am4core.create("chartdiv", am4charts.PieChart);
    chart.data = this.datatest

    // Set inner radius
    chart.innerRadius = am4core.percent(50);

    // Add and configure Series
    let pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "litres";
    pieSeries.dataFields.category = "country";
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;

    // This creates initial animation
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    // end am4core.ready()
  },
};
</script>
