let browseFile = $('#browseFile');
let csrf_token = $('meta[name="csrf-token"]').attr('content');
let resumable = new Resumable({
    target: '/upload',
    query:{_token:csrf_token} ,// CSRF token
    fileType: ['mp4'],
    chunkSize: 10*1024*1024,
    headers: {
        'Accept' : 'application/json'
    },
    testChunks: true,
    throttleProgressCallbacks: 1,
});

resumable.assignBrowse(browseFile[0]);

resumable.on('fileAdded', function (file) { // trigger when file picked
    showProgress();
    resumable.upload() // to actually start uploading.
});

resumable.on('fileProgress', function (file) { // trigger when file progress update
    updateProgress(Math.floor(file.progress() * 100));
});

resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
    response = JSON.parse(response)
    $('#videoPreview').attr('src', response.data.url);
    $('.card-footer').show();
});

resumable.on('fileError', function (file, response) { // trigger when there is any error
    alert('file uploading error.')
});


let progress = $('.progress');
function showProgress() {
    progress.find('.progress-bar').css('width', '0%');
    progress.find('.progress-bar').html('0%');
    progress.find('.progress-bar').removeClass('bg-success');
    progress.show();
}

function updateProgress(value) {
    progress.find('.progress-bar').css('width', `${value}%`)
    progress.find('.progress-bar').html(`${value}%`)
}

function hideProgress() {
    progress.hide();
}
