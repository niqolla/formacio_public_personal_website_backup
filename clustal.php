<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Clustal-Alignment
    </title>
  
    <?php include_once 'includes/includehead.php'; ?>

    <?php require "globals.ini.php"; ?>
    
</head>

<?php
$placeholderFASTA = ">P04247\nMGLSDGEWQLVLNVWGKVEADLAGHGQEVLIGLFKTHPETLDKFDKFKNLKSEEDMKGS\nDLKKHGCTVLTALGTILKKKGQHAAEIQPLAQSHATKHKIPVKYLEFISEIIIEVLKKRH\nSGDFGADAQGAMSKALELFRNDIAAKYKELGFQG\n\n>P02144\nMGLSDGEWQLVLNVWGKVEADIPGHGQEVLIRLFKGHPETLEKFDKFKHLKSEDEMKASE\nDLKKHGATVLTALGGILKKKGHHEAEIKPLAQSHATKHKIPVKYLEFISECIIQVLQSKH\nPGDFGADAQGAMNKALELFRKDMASNYKELGFQG";
$placeholderIDS = "P04247\nQ01966\nP02144\nO34167";
?>

<body>
        <section id="app" class="contact section-bg" style="padding-bottom: 0px;">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h1>Clustal-Align</h1>
                </div>

                <div class="container">
                    <div style="text-align: right;">
                    <!-- <div class="row"> -->
                        <!-- <div class="info-box mb-4"> -->
                        <h3>Select an output format</h3>
                            <select id="output_format">
                                <option value="fa">FASTA</option>
                                <option value="clu">CLUSTAL</option>
                                <option value="msf">MSF</option>
                                <option value="phy">PHY</option>
                                <option value="selex">SELEX</option>
                                <option value="st">STOCKOLM</option>
                                <option value="vie">VIENNA</option>
                            </select>
                        <!-- </div> -->
                    <!-- </div> -->
                    </div>
                    <hr>
                    <!-- </div> -->
                    <!-- <div class="col-lg-6"> -->
                        <!-- <div class="info-box mb-4"> -->
                            <h3>Get Alignment from Sequences</h3>
                            <textarea id="fastatext" name="fastatext" rows="10" cols="64" style="font-size: 10pt; font-family: 'Courier New', Courier, monospace; width: 99%" placeholder="<?php echo htmlspecialchars($placeholderFASTA); ?>"></textarea>
                            <br><br>
                            <div style="text-align: right;"><button id="runfastatext" class="button-clustal">Align Sequnces</button></div>
                        <!-- </div> -->
                    <!-- </div> -->
                        <hr>
                    <!-- <div class="col-lg-3 col-md-6"> -->
                        <!-- <div class="info-box  mb-4"> -->
                            <h3>Get Alignment from UniProtIDs</h3>
                            <textarea id="uniprotid" name="uniprotid" rows="3" cols="15"  style="font-size: 10pt; font-family: 'Courier New', Courier, monospace" placeholder="<?php echo htmlspecialchars($placeholderIDS); ?>"></textarea>
                            <br><br>
                            <div style="text-align: right;"><button id="rununiprotid" class="button-clustal">Align By UniProtID</button></div>
                        <!-- </div> -->
                    <!-- </div> -->
                        <hr>
                    <!-- <div class="col-lg-3 col-md-6"> -->
                        <!-- <div class="row"> -->
                            <!-- <div class="info-box  mb-4"> -->
                                <h3>Get Alignment from File</h3>
                                <form style="margin-bottom: 6px" id="form-demo" onsubmit="return false">
                                    <label for="file-upload" class="custom-file-upload">
                                        Upload File
                                    </label>
                                    <input id="file-upload" name='upload_cont_img' accept=".fa,.fasta,.txt,.fna,.ffn,.faa,.frn" type="file" style="display:none;">
                                    <br><br>
                                    <div style="text-align: right;"><button id="runfastafile" class="button-clustal">Align from File</button></div>
                                </form>
                            <!-- </div> -->
                        <!-- </div> -->
                        <hr>
                </div>
                
            </div>

            <br><br><br>

        </section>
        <section id="results" class="contact section-bg" style="padding-top: 0px;">
            <div class="container">
                <div id="result"></div>
            </div>
        </section>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#runfastatext').on('click', function(){
                var fastatext = $('#fastatext').val();
                var selector = document.getElementById("output_format");
                var output_format = selector.value;
                console.log(output_format);
                var output_format_text = selector.options[selector.selectedIndex].text;
                if(fastatext != "") {
                    //console.log(fastatext);
                    $.ajax({
                        url: "clustal_execution.php",
                        type: "POST",
                        data: {
                            mode: "runfastatext",
                            fastatext: fastatext,
                            output_format: output_format
                        },
                        success: function(data) {
                            $('#result').html(data);
                        }
                    })
                } else {
                    alert("Input is required")
                }
            })

            $('#rununiprotid').on('click', function(){
                var uniprotid = $('#uniprotid').val();
                var selector = document.getElementById("output_format");
                var output_format = selector.value;
                console.log(output_format);
                var output_format_text = selector.options[selector.selectedIndex].text;
                if(uniprotid != "") {
                    //console.log(fastatext);
                    $.ajax({
                        url: "clustal_execution.php",
                        type: "POST",
                        data: {
                            mode: "rununiprotid",
                            fastatext: uniprotid,
                            output_format: output_format
                        },
                        success: function(data) {
                            $('#result').html(data);
                        }
                    })
                } else {
                    alert("Input is required")
                }
            })

            $('#file-upload').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });
            $('#runfastafile').on('click', function(){
                var file_data = $('#file-upload').prop('files')[0];
                var selector = document.getElementById("output_format");
                var output_format = selector.value;
                console.log(output_format);
                var output_format_text = selector.options[selector.selectedIndex].text;
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('mode', 'runfastafile');
                form_data.append('output_format', output_format);
                // alert(form_data);
                $.ajax({
                    url: "clustal_execution.php",
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(data) {
                        console.log(data);
                        $('#result').html(data);
                    }
                })
            })

			});

            $(document).ready(function() {
                $("#runfastatext").click(function() {
                $('html, body').animate({
                    scrollTop: $("#results").offset().top
                }, 1000);
                });
            });
            $(document).ready(function() {
                $("#rununiprotid").click(function() {
                    setTimeout(function() {
                    $('html, body').animate({
                        scrollTop: $("#results").offset().top
                    }, 1000);
                    }, 3000); // Wait for 5000 milliseconds (5 seconds)
                });
                });
            $(document).ready(function() {
                $("#runfastafile").click(function() {
                $('html, body').animate({
                    scrollTop: $("#results").offset().top
                }, 1000);
                });
            });

        
    </script>
  <?php include_once 'includes/includebottom.php'; ?>
</body>
