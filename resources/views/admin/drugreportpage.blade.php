<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <x-nav-link :href="route('showDrugReport')" :active="request()->routeIs('showDrugReport')">
                        {{ __('Drugs') }}
                    </x-nav-link>
                    <x-nav-link :href="route('batch')" :active="request()->routeIs('batch')">
                        {{ __('Batch Clinic') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </x-slot>

    <center>
        <div style="width:80%;margin-top:30px">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
        </div>
    </center>s

    <div class="py-12">
        <div class="container">
            <div class="row">
                <!-- Sidebar Column -->
                <div class="col-md-3">
                    <form id="clinicForm" method="POST" action="{{ route('yeardrug') }}">
                        @csrf
                        <fieldset>
                            <div class="mb-3">
                                <label for="month" class="form-label">Select Month:</label>
                                <select id="month" name="month" class="form-select">
                                    <option value="" >Select a month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="year" class="form-label">Select Year:</label>
                                <select id="year" name="year" class="form-select">
                                    <option value="" disabled selected>Select a year</option>
                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </fieldset>
                    </form>
                </div>

                <!-- Main Content Column -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Yearly Drug Report
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Drug Name</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="resultTableBody">
                                    <!-- Dynamic rows will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Stock distributed throughout {{ now()->year }}
                        </div>
                        <div class="card-body">
                            <!-- Chart Area -->
                            <canvas id="resultChart" style="margin: 20px;"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Stock dispensed throughout {{ now()->year }}
                        </div>
                        <div class="card-body">
                            <!-- Chart Area -->
                            <canvas id="resultChart2" style="margin-top: 20px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Include Chart.js and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            $('#clinicForm').on('submit', function(event) {
                event.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('yeardrug') }}',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        $('#resultTableBody').html(data.html);
                        updateChart(data.chartData);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('An error occurred while fetching data.');
                    }
                });
            });
        });

        function updateChart(chartData) {
            const ctx = document.getElementById('resultChart').getContext('2d');
            if (window.myChart) {
                window.myChart.destroy();
            }
            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Quantity Distributed',
                        data: chartData.values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
