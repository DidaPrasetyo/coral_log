<?php
include('header.php');
?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->

                    <!-- Content Row -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tabelData" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Timestamp</th>
                                            <th>Person Detected</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Timestamp</th>
                                            <th>Person Detected</th>
                                            <th>Image</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        // Check if there are any results
                                        if ($result) {
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row["detect_time"] ?></td>
                                                    <td><?= $row["count"] ?></td>
                                                    <td>
                                                        <?php
                                                        $imageData = $row['img'];

                                                // Display the image
                                                        if (isset($imageData)) {
                                                            $base64Image = base64_encode($imageData);
                                                            echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="BLOB Image"><br>';
                                                        } else {
                                                            echo 'Image not found<br>';
                                                        }
                                                    }

                                            // Close the result set
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

<?php
include('footer.php');
?>