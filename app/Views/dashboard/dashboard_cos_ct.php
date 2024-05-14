<?= $this->extend('template/layout'); ?>

<?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left:0;">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div style="margin-top:150px; margin-left:50px;">
                    <div class="row">
                        <div class="col-lg-2">
                           
                        </div>

                        <div class="col-lg-5">
                            <div class="container mt-5">
                                <h1 class="text-center">TARGET CYCLE TIME</h1>
                                <div id="cycle-time-target" class="mt-4">
                                    <!-- Data from API will be displayed here -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="container mt-5">
                                <h1 class="text-center">CYCLE TIME IN PROCESS</h1>
                                <div id="cycle-time-proses" class="mt-4">
                                    <!-- Data from API will be displayed here -->
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Function to update the dashboard
        function updateDashboard() {
            $.ajax({
                url: 'https://portal2.incoe.astra.co.id:8080/api/getCosCt?id_line=3',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    if (response.results && response.results.length > 0) {
                        const cycleTime = response.results[0].cycle_time;
                        $('#cycle-time-proses').html(`
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="card-title" style="font-size: 35px;">Cycle Time</h2>
                                    <p class="card-text" style="font-size: 35px;">${cycleTime}</p>
                                </div>
                            </div>
                        `);

                        $('#cycle-time-target').html(`
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="card-title" style="font-size: 35px;">Cycle Time</h2>
                                    <p class="card-text" style="font-size: 35px;">25.3</p>
                                </div>
                            </div>
                        `);
                    } else {
                        $('#cycle-time-proses').html('<p>No data available.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $('#cycle-time-proses').html('<p>Error loading data.</p>');
                }
            });
        }

        // Call the updateDashboard function when the page loads
        $(document).ready(function() {
            updateDashboard(); // Call immediately when the page loads
            setInterval(updateDashboard, 20000); // Call every 20 seconds thereafter
        });
    </script>

    <!-- <script>
        // Function to update the dashboard
        function updateDashboard() {
            $.ajax({
                url: 'https://portal2.incoe.astra.co.id:8080/api/getCosCt?id_line=1',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const cycleTime = response.results[0].cycle_time;
                    alert(cycleTime);
                    $('#cycle-time-proses').html(`
                        <p>Cycle Time: ${cycleTime}</p>
                    `);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Call the updateDashboard function when the page loads
        $(document).ready(function() {
            updateDashboard();
        });
    </script> -->

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script></script>
<?= $this->endSection(); ?>