@extends('layout.master')
@section('mytitle', __('lms.all_leads'))
@section('content')
    @php
        $breadcrumb[] = ['title' => 'Home'];
        $breadcrumb[] = ['title' => 'Lms'];
        $breadcrumb[] = ['title' => __('lms.lms_dashboard')];
    @endphp
    <style>
        .canvasjs-chart-credit {
            display: none !important;
        }
    </style>
    <x-content-header :breadcrumb="$breadcrumb" />
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pending_leads }}</h3>

                                    <p>Pending Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-2 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $takenover_leads }}</h3>

                                    <p>Takenover Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-2 col-6">
                            <!-- small box -->
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>{{ $inprocess_leads }}</h3>

                                    <p>In Process Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-2 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $successfull_leads }}</h3>

                                    <p>Successfull Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-2 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $unSuccessfull_leads }}</h3>

                                    <p>Unsuccessfull Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-2 col-6">
                            <!-- small box -->
                            <div class="small-box bg-gradient-maroon">
                                <div class="inner">
                                    <h3>{{ $all_leads }}</h3>

                                    <p>Total Leads</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <section class="col-lg-12 connectedSortable ui-sortable">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <div class="card-header bg-dark ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Monthly Leads
                                </h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                @foreach ($leadsCountByMonth as $leadsCount)
                                    <?php
                                    if ($leadsCount->month == 1) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Jan'];
                                    }
                                    if ($leadsCount->month == 2) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Feb'];
                                    }
                                    if ($leadsCount->month == 3) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Mar'];
                                    }
                                    if ($leadsCount->month == 4) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Apr'];
                                    }
                                    if ($leadsCount->month == 5) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'May'];
                                    }
                                    if ($leadsCount->month == 6) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Jun'];
                                    }
                                    if ($leadsCount->month == 7) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Jul'];
                                    }
                                    if ($leadsCount->month == 8) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Aug'];
                                    }
                                    if ($leadsCount->month == 9) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Sep'];
                                    }
                                    if ($leadsCount->month == 10) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Oct'];
                                    }
                                    if ($leadsCount->month == 11) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Nov'];
                                    }
                                    if ($leadsCount->month == 12) {
                                        $dataPoints[] = ['y' => $leadsCount->count, 'label' => 'Dec'];
                                    }
                                    ?>
                                @endforeach
                                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                            </div><!--end-card-body-->
                        </div>
                        <!-- /.card -->
                    </section>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
<script>
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            data: [{
                type: "column",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
