<style>
    .video-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px; /* Adjust the height as needed */
    }

    .video-player {
        max-width: 100%;
        max-height: 100%;
    }

    .no-record {
        text-align: center;
        font-size: 18px;
        color: #555;
    }
</style>

<div class="modal-body">
    <div class="video-container">
        @if($video->video_file)
            <video controls autoplay class="video-player">
                <source src="{{ asset('videos/'.$video->video_file) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @elseif($video->video_link)
            <video controls autoplay class="video-player">
                <source src="{{ $video->video_link }}" controls width="320" height="240" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <p class="no-record">No Record Found</p>
        @endif
    </div>
</div>
