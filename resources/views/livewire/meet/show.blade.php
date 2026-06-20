<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Clase
    </h2>
</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <x-ui.alert />

    <!-- added on import -->
    <div id="zmmtg-root"></div>
    <div id="aria-notify-area"></div>

</div>

@push('estilos')
<!-- import #zmmtg-root css -->
<link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.1/css/bootstrap.css" />
<link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.1/css/react-select.css" />
@endpush

@push('javascripts')

<!-- import ZoomMtg dependencies -->
<script src="https://source.zoom.us/1.9.1/lib/vendor/react.min.js"></script>
<script src="https://source.zoom.us/1.9.1/lib/vendor/react-dom.min.js"></script>
<script src="https://source.zoom.us/1.9.1/lib/vendor/redux.min.js"></script>
<script src="https://source.zoom.us/1.9.1/lib/vendor/redux-thunk.min.js"></script>
<script src="https://source.zoom.us/1.9.1/lib/vendor/lodash.min.js"></script>

<!-- import ZoomMtg -->
<script src="https://source.zoom.us/zoom-meeting-1.9.1.min.js"></script>




<script>
    // ZoomMtg.setZoomJSLib('node_modules/@zoomus/websdk/dist/lib', '/av');
    // ZoomMtg.setZoomJSLib('https://dmogdx0jrul3u.cloudfront.net/1.9.1/lib', '/av'); 
    ZoomMtg.setZoomJSLib('https://source.zoom.us/1.9.1/lib', '/av');

    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareJssdk();

    const zoomMeeting = document.getElementById("zmmtg-root");

    jQuery.get(APP_URL + "/ajax/zoom-signature", (signature) => {
        console.log('object :>> ', signature);
        ZoomMtg.init({
            leaveUrl: 'http://www.zoom.us',
            webEndpoint: 'api.zoom.us',
            disableInvite: true,
            videoHeader: false,
            meetingInfo: [
                // 'topic',
                'host',
                // 'mn',
                'participant',
                // 'dc',
            ],
            success: function() {
                ZoomMtg.join({
                    signature: signature,
                    meetingNumber: 97896133682,
                    userName: 'badfdfp',
                    apiKey: 'xxxxxx',
                    userEmail: 'xxxxxs',
                    passWord: 'xxxxx',
                    success: (success) => {
                        console.log(success)
                    },
                    error: (error) => {
                        console.log(error)
                    }
                });
            },
            error: function(res) {
                console.log('res :>> ', res);
            },
        });

    });
</script>

@endpush
