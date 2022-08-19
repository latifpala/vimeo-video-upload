<?php
global $wpdb;
$user_id = get_current_user_id();
$videos_table = $wpdb->prefix.'geeker_videos';
$qry = "SELECT * FROM {$videos_table} WHERE user_id=".$user_id." AND is_deleted=0 ORDER BY video_id DESC";
$videos = $wpdb->get_results($qry);
?>
<h4>Videos</h4>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Video Title</th>
                <th scope="col">Video Link</th>
                <th scope="col">Price</th>
                <th scope="col">Upload Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            if(!empty($videos)): 
                foreach($videos as $video): 
                    $count++;
                    $product_id = $video->product_id;
                    $video_title = get_the_title($product_id);
                    $video_id = get_post_meta($product_id, 'video_id', true);
                    $video_url = 'https://vimeo.com/'.$video_id;
                    $price = get_post_meta($product_id, 'vimeo_video_price', true);
            ?>
                    <tr>
                        <th scope="row"><?php echo $count; ?></th>
                        <td><?php echo $video_title; ?></td>
                        <td><a href="<?php echo $video_url; ?>" target="_blank"><?php echo $video_url; ?></a></td>
                        <td><?php echo '$'.$price; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($video->date)); ?></td>
                        <td><a href="<?php echo site_url('vimeo'); ?>/?id=<?php echo $video->video_id; ?>" class="btn btn-primary">Edit</a> <a href="javascript:;" data-video="<?php echo $video->video_id; ?>" class="btn btn-danger geeker-delete-video">Delete</a></td>
                    </tr>
            <?php
                endforeach;
            endif; ?>
        </tbody>
    </table>
</div>
