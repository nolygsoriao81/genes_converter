<?php 


   $result_data = $data->data;
   $genes_json = $data->genes_json;
 
    ?>

<div id="genes_table">
<table>
    <tr class="tr-bg">
        <th width="7%">WEIGHT</th>
        <th width="7%">COUNT</th>
        <th width="7%">TOTAL</th>
        <th width="20%">GENE</th>
        <th width="20%">GENOTYPE</th>
        <th width="20%">RISK</th>
        <th width="30%">NOTES</th>
    </tr>
    <tr class="tr-cols">
        <th colspan="7" style="text-align:left;">
            <h1 id="th-title">MODULE: NUTRITION</h1></th>
    </tr>

<?php foreach($result_data as $key => $val):?>
    <tr class="tr-cols-sub">
                        <th colspan="7" style="text-align:left;">
                            <h2 id="th-subTitle" style="color:#ff1d6e;"><?php echo $key ?></h2></th>

                    </tr>

                    <tr>

                                                
                            <th>
                                <?=isset($genes_json["data_label"][$key]["column_1"])?$genes_json["data_label"][$key]["column_1"]: ""; ?>
                                
                            </th>
                            <th>
                                
                                <?=isset($genes_json["data_label"][$key]["column_2"])?$genes_json["data_label"][$key]["column_2"]: ""; ?>
                            
                            </th>
                            <th colspan="">
                            <?=isset($genes_json["data_label"][$key]["column_3"])?$genes_json["data_label"][$key]["column_3"]: "TOTAL"; ?>
                            
                                
                            </th>
                            <th class="blue-back color-white">
                                <?=isset($genes_json["data_label"][$key]["column_4"])?$genes_json["data_label"][$key]["column_4"]: "GENE"; ?>
                            
                            </th>
                            <th class="blue-back color-white">
                            <?=isset($genes_json["data_label"][$key]["column_5"])?$genes_json["data_label"][$key]["column_5"]: "GENOTYPE"; ?>
                            
                            </th>
                            <th class="blue-back color-white">
                            <?=isset($genes_json["data_label"][$key]["column_6"])?$genes_json["data_label"][$key]["column_6"]: "RISK"; ?>
                            
                            </th>
                            <th class="blue-back color-white">
                                <?=isset($genes_json["data_label"][$key]["column_7"])?$genes_json["data_label"][$key]["column_7"]: "NOTES"; ?>
                            
                            </th>
                            </tr>

                            <?php 
              $cur_gene_type = null;
              $genetype_collector = array();
          ?>
    <?php foreach($val as $row_data ): ?>
        <?php 
        
        $givenGeneType = !isset($row_data['givenGeneType']) ? "null" : $row_data['givenGeneType'];
        $givenGeneNum = !isset($row_data['givenGeneNum']) ? 0 : $row_data['givenGeneNum'];
        $count = !isset($row_data['count']) ? 0 : $row_data['count'];
        
        $genetype_collector[$givenGeneType ][] = $givenGeneNum;
        $genetype_collector[$givenGeneType ."_count"][] =  $count;

      ?>
        

                    <tr style="text-align:center;">
            <td>
                   <?=isset($row_data['givenGeneNum']) ? $row_data['givenGeneNum'] : "" ;?>
                    
            </td>
            <td>
             
                <?=isset($row_data['count']) ? $row_data['count'] : "" ;?>
            </td>
            <td></td>

            <td>
                <?=$row_data['gene']; ?>
            </td>

            <td style="background:<?=$row_data['color_code']?>">
                <?=$row_data['genoType']; ?>
            </td>
            <td>
                <?=$row_data['risk']; ?>
            </td>
            <td>
                <?=$row_data['notes']; ?>
            </td>
        </tr>
    <?php endforeach; ?>

 
    <?php   foreach ($genetype_collector as $freq_key => $value) : ?>

<?php    # code...
      $pos = strpos($freq_key, "count");

      $total_givengenes = 0;
      $total_count = 0;
      $total = 0;
      $average_computation = 0;
      
      if($pos == 0 && $freq_key != "null" && $freq_key != "null_count"): ?>

  <?php   
   
            $total_givengenes = array_sum($value) ;
            $total_count = array_sum($genetype_collector[$freq_key."_count"]) ;
           
            // $total = ($total_count + $total_givengenes) > 1 ? ($total_count + $total_givengenes)   : 0 ;
            $total = $genes_json["data_label"][$key]["total_".strtolower($freq_key)];
           
            $average_computation = round(($total_count / $total), 2); 
            
            //echo  $average_computation;

            if($average_computation > 0 && $average_computation < 0.34){
                   $average_color_coding = "#4d9d59";
                   $average_color_message = "You have a below average risk for this section.";
            }
            else if($average_computation > 0.34 && $average_computation < 0.66){
                    $average_color_coding  = "#fbbf3d";
                    $average_color_message = "You have an average risk for this section.";
            }
            else{
                    $average_color_coding  = "#f84421";
                    $average_color_message = "You have an high average risk for this section.";
            } 

  ?>
  <tr>
      <td colspan="2"><b>Total <?=$freq_key?> :</b></td>
      <td>
          <?= $total_givengenes?>
      </td>

      <td colspan="4"></td>
  </tr>
  <tr>
      <td colspan="2"><b>Total count :</b></td>

      <td>
          <?=$total_count?>
      </td>
      <td colspan="4"></td>
  </tr>
  <tr>
      <td colspan="2"><b>Total</b></td>

      <td>
          <?= $total?>
      </td>

      <td colspan="4"></td>
  </tr>
  <tr>

      <td colspan="2">
          <?= $average_computation ."%";?>
              <?=$average_color_message?>
      </td>

      <td colspan="2"></td>
      <td style="background:<?= $average_color_coding; ?>" colspan="">
          <?= $average_computation ."%";?>

      </td>
      <td colspan="2">
          <?=$average_color_message?>
      </td>
  </tr>

  <?php endif; ?>

      <?php endforeach; ?>

<?php endforeach; ?>


</table>
</div>