jQuery.ajax({
        type: 'POST',
        url: 'https://api.vimeo.com/me/videos',
        upload: {
                approach: "post",
            },
        headers: {
             'Authorization': 'bearer 7d9c0ba546a97d4f48030ed60d7421fd',
             'Content-Type': 'application/json',
             'Accept': 'application/vnd.vimeo.*+json;version=3.4'
           },
        
        success: function(res){
            console.log(res);
        },
        error: function(err){
            console.log(err);
        }
    });






Reference plugin: https://wordpress.org/plugins/wp-vimeo-videos/



tpm-solutions.ch
ftptpm@tpm-solutions.ch
Ry-=DfxZRz3y=MsN!sSM

https://stackoverflow.com/questions/63967765/how-can-i-upload-a-video-from-my-file-system-to-vimeo-directly-from-the-browser/71688580#71688580

https://stackoverflow.com/questions/63187719/vimeo-api-cant-upload-video-no-user-credentials-were-provided-8003


