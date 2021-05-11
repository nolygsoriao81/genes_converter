<?php


/* echo GENECONVERTER_WPADMIN_PATH;

print_r($_FILES); */
include(GENECONVERTER_WPADMIN_PATH . '/wp-load.php');
//print_r($_FILES) ;
if (isset($_FILES['file']) && !empty($_FILES['file']['name']))
{

  
    $allowedExts = array(
        "txt"
    );
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    if (in_array($extension, $allowedExts))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            $response = array(
                "status" => 'error',
                "message" => 'ERROR Return Code: ' . $_FILES["file"]["error"],
            );
        }
        else
        {
            $uploadedfile = $_FILES['file'];
            $upload_name = $_FILES['file']['name'];
            $uploads = wp_upload_dir();
            $filepath = $uploads['path'] . "/$upload_name";

            if (!function_exists('wp_handle_upload'))
            {
                require_once (ABSPATH . 'wp-admin/includes/file.php');
            }
            require_once (ABSPATH . 'wp-admin/includes/image.php');
            $upload_overrides = array(
                'test_form' => false
            );
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
            if ($movefile && !isset($movefile['error']))
            {

                $file = $movefile['file'];
                $url = $movefile['url'];
                $type = $movefile['type'];

                $attachment = array(
                    'post_mime_type' => $type,
                    'post_title' => $upload_name,
                    'post_content' => 'Image for ' . $upload_name,
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment, $file, 0);
                $attach_data = wp_generate_attachment_metadata($attach_id, $file);
                wp_update_attachment_metadata($attach_id, $attach_data);

            }

            $response = array(
                "status" => 'success',
                "url" => $url . $_FILES["file"]["name"]
            );
            

           
        }
    }
    else
    {
        $response = array(
            "status" => 'error',
            "message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
        );
    }
}
// print json_encode($response);


?>
