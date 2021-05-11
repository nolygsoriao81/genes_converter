
<?php




    class ReportGeneratorClass extends Genes_Template_Loader{
        
        
       /* Private attribute, cannot be accessed directly */
	private $dna_array;
    private $rawdata_array;
	/* Constructor */
	public function __construct()
	{
        $this->dna_array = '';
        $this->rawdata_array = '';


	}

    
	public function get_alldetailsgenesjson()
	{
       
		return $this->dna_array ;
	}
    
    
	public function get_allrawdatatext()
	{
            
        

		return $this->rawdata_array ;
	}
	

	public function set_dnapath($param)
	{
        $data = file_get_contents($param); 
        $dna = json_decode($data, true);
        
		$this->dna_array = $dna;
		
    }
    
	public function set_rawdatapath($param)
	{
        
        $file = $param;
        $lines = file($file);
        $first3 = array_slice($lines, 0, 28);
        $title = array_slice($lines, 28, 1);

        $array = [];
        for($i=2; $i<count($lines); $i++){
            $val = array_slice($lines, 28, $i);
            foreach($val as $v) {
            $val_arr = preg_split('/[\t]/', $v);

            }

            array_push($array, $val_arr);
        }

		$this->rawdata_array = $array;
		
	}
    public function generate_data(){
                
        $array = $this->get_allrawdatatext();
              
                $all_genes_det =$this->get_alldetailsgenesjson() ;

                $dna = $all_genes_det["data"] ;

                foreach($dna as $key => $value){
                    for($i=0; $i<count($dna[$key]); $i++){
                        $found = array_search( $dna[$key][$i]["rsID"], array_column($array, "0"));

                        if( $found === False ) {

                            $dna[$key][$i]["genoType"] = "#N/A";
                            $genetype = "NotFound";

                        }else{

                            $dna[$key][$i]["genoType"] = $array[$found][3];

                            $genetype = trim(  $dna[$key][$i]["genoType"]  ); 

                        }

                        $rsID = $dna[$key][$i]["rsID"];

                        $color_name = isset($all_genes_det["genotype_color"][$rsID][$genetype]) ? strtolower($all_genes_det["genotype_color"][$rsID][$genetype]): "null"  ;
                
                        $dna[$key][$i]["color_name"] =  $color_name;

                        $color_code = $all_genes_det["color_code"][$color_name];

                        $dna[$key][$i]["color_code"] =$color_code;

                        /* Count calculate */

                        if(isset($dna[$key][$i]["givenGeneNum"])){
                        $givenGeneNum = $dna[$key][$i]["givenGeneNum"];
                        $genotype_freq_color = $all_genes_det["genotype_freq_color"][$color_name];
                        $dna[$key][$i]["genotype_freq"] = $genotype_freq_color;

                        if( $givenGeneNum > 1 && ( $color_name == "yellow" || $color_name == "green")){
                            $calculate = eval('return '.$givenGeneNum .$genotype_freq_color.';');
                            $dna[$key][$i]["count"] = $calculate ;
                        }
                        else{
                            if($color_name == "yellow" && $givenGeneNum == 1){

                                $dna[$key][$i]["count"] = 1 ;
                            }
                            else{
                                $calculate = eval('return '.$givenGeneNum .$genotype_freq_color.';');
                                $dna[$key][$i]["count"] = $calculate < 0 ? 0 : $calculate ;
                            }
                        }
                        }
                    }
                }
               
                return $dna;
    }
    public function display_table_template(){
        
        $param = array( "data" => $this->generate_data() , "genes_json" => $this->get_alldetailsgenesjson());
       
        $this->set_template_data( $param )->get_template_part( 'template-respond-generated-table' );


    }

    }
   
   
?>