<?php
function geeker_create_folder($folder_name = ''){
    $url = "https://api.vimeo.com/me/projects/";
    $body = [
        // folder name
        'name'  => $folder_name,
    ];
    $body = wp_json_encode( $body );
    $args = array(
        'headers' => geeker_get_api_headers(),
        'body' => $body
    );
    $res = wp_remote_post($url, $args);
    if($res['response']['code']==201){
        $responseBody = wp_remote_retrieve_body( $res );
        $result = json_decode( $responseBody );
        return $result->uri;
    }else{
        return false;
    }
}
function geeker_get_folders(){
    
    $args = array(
        'headers' => geeker_get_api_headers(),
    );
    $url = "https://api.vimeo.com/me/projects/";
    $res = wp_remote_get($url, $args);
    $responseBody = wp_remote_retrieve_body( $res );
    $result = json_decode( $responseBody );
    $folders_arr = array();
    if ( ! is_wp_error( $result ) ) {
        if(!empty($result->data)){
            foreach($result->data as $folders){
                $folder = array();
                $folder['folder_name'] = $folders->name;
                $folder['uri'] = $folders->uri;
                $folders_arr[] = $folder;
            }
        }
    }
    return  $folders_arr;
}
function geeker_move_video_to_folder($folder_id = 0, $video_id = 0){
    /* Code to move video in a specific folder */
	// https://api.vimeo.com/me/projects/{project_id}/videos/{video_id}
    
    $args = array(
        'headers' => geeker_get_api_headers(),
        'method' => 'PUT'
    );
	$video_move_url = 'https://api.vimeo.com/me/projects/'.$folder_id.'/videos/'.$video_id;
    $res = wp_remote_request($video_move_url, $args);
    // it returns 204 success code. 
}
function geeker_get_api_headers(){
    $authorization = 'bearer '.WP_VIMEO_ACCESS_TOKEN;
    return array(
        'Content-Type' => 'application/json',
        'Authorization' => $authorization,
    );
}
function geeker_delete_video($video_id = 0){
    //DELETE https://api.vimeo.com/videos/{video_id}
    $args = array(
        'headers' => geeker_get_api_headers(),
        'method' => 'DELETE'
    );
	$video_delete_url = 'https://api.vimeo.com/videos/'.$video_id;
    $res = wp_remote_request($video_delete_url, $args);
    $response_code = wp_remote_retrieve_response_code($res);
    
    if($response_code==403){
        // forbidden
        return false;
    }elseif($response_code==204){
        // success
        return true;
    }
    return false;
}

function geeker_update_video_data($video_id, $video_title, $video_description){
    //PATCH https://api.vimeo.com/videos/{video_id}
    $body = [
        'description' => $video_description,
        'name' => $video_title,
    ];
    $body = wp_json_encode( $body );
    $args = array(
        'headers' => geeker_get_api_headers(),
        'method' => 'PATCH',
        'body' => $body
    );
    $video_update_url = 'https://api.vimeo.com/videos/'.$video_id;
    $res = wp_remote_request($video_update_url, $args);

    //return $res;
}

function geeker_delete_video_folder(){
    //DELETE https://api.vimeo.com/me/projects/{project_id}
    $args = array(
        'headers' => geeker_get_api_headers(),
        'method' => 'DELETE',
        'should_delete_clips' => true // to delete videos inside folder
    );
    $video_delete_url = 'https://api.vimeo.com/videos/'.$video_id;
}

//add_action('init', 'geekeer_test_api');
function geekeer_test_api(){
    $res = geeker_update_video_data(723643235, 'Updated Title again', 'Updated Description');
    echo "<pre>";
    print_r($res);
    echo "</pre>";
    die;
}

