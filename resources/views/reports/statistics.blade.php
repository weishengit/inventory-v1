@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Statistics</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Statistics</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Dashboard</div>

                        <div class="card-body">

                            <canvas id="myChart" width="400" height="400"></canvas>

                        </div>

                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

</div><!-- /.content-wrapper -->

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
<script>
    function dynamicColors() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgba(" + r + "," + g + "," + b + ", 0.5)";
    }

    async function getSupplierItems() {
        const url = "{!! route('fetch.items') !!}" + "/0";
        const res = await fetch(url);
        const data = await res.json();
        console.log(url);

        let itemName = [];
        let itemQuantity = [];
        let itemColor = [];
        data.forEach((item) => {
            itemName.push(item.name);
            itemQuantity.push(item.quantity);
            itemColor.push(dynamicColors());
        });

        let result = {name: itemName, quantity: itemQuantity, color: itemColor};
        const d = {
            labels: result.name,
            datasets: [{
                label: 'Dataset 1',
                data: result.quantity,
                backgroundColor: result.color,
                }]
        };
        const config = {
            type: 'pie',
            data: d,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Items'
                        }
                    }
            },
        };

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, config);
    }


    window.onload = function exampleFunction() {
        getSupplierItems();
    }



    </script>
@endsection
