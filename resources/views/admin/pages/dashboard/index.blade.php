@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div id="order_summary" style="width: 900px; height: 500px;"></div>
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('my-script')
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = google.visualization.arrayToDataTable(@json($result));

      var options = {
        title: 'Order Summary'
      };

      var chart = new google.visualization.PieChart(document.getElementById('order_summary'));

      chart.draw(data, options);
    }
  </script>
@endsection