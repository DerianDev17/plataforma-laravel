<x-zoom-layout>
  <!-- added on import -->
  <div id="zmmtg-root"></div>
  <div id="aria-notify-area"></div>

  @push('estilos')
  <!-- import #zmmtg-root css -->
  <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.1/css/bootstrap.css" />
  <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.1/css/react-select.css" />

  <!-- estilos para estudiantes -->
  @if($es_estudiante)
  <style>
    /* desaparecer numero en boton participantes */
    #zmmtg-root span.footer-button__number-counter {
      display: none;
    }

    /* ocultar numero en panel participantes */
    .participants-header__title:before {
      content: "Participantes";
    }

    .participants-header__title span {
      display: none;
    }

    ul.participants-ul>li:nth-child(n+3) {
      display: none;
    }
  </style>
  @endif
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

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-latest.min.js"></script>

  <script>
    // ZoomMtg.setZoomJSLib('node_modules/@zoomus/websdk/dist/lib', '/av');
    // ZoomMtg.setZoomJSLib('https://dmogdx0jrul3u.cloudfront.net/1.9.1/lib', '/av'); 
    ZoomMtg.setZoomJSLib('https://source.zoom.us/1.9.1/lib', '/av');
    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareJssdk();


    const zoomMeeting = document.getElementById("zmmtg-root");
    // const langJson = require("{{ Storage::url('zoom/es-ES.json') }}");

    // function exitMeeting() {
    //   document.querySelector('.zmu-btn').click()
    //   setTimeout(function() {
    //     document.querySelector('#wc-footer > div.footer__inner.leave-option-container > div:nth-child(2) > div:nth-child(3) > div > div > button').click()
    //   }, 2000)
    // }

    function iniciarZoom() {
      ZoomMtg.i18n.load("es-ES");
      ZoomMtg.i18n.reload("es-ES");

      ZoomMtg.init({
        leaveUrl: APP_URL,
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
          jQuery.get(APP_URL + '/ajax/zmtg-join-data', function(data) {
            ZoomMtg.join({
              signature: data.signature,
              meetingNumber: '{{$meeting_number}}',
              passWord: '{{$meeting_password}}',
              userName: "{{$nombre[0]}}" + ' ' + "{{$apellido[0]}}",
              apiKey: data.apiKey,
              userEmail: '{{$email}}',
              success: (success) => {
                console.log(success);
                ZoomMtg.getAttendeeslist({
                  success: function(attList) {
                    ZoomMtg.inMeetingServiceListener('onUserJoin', function(data) {
                      console.log('hola', data);

                      ZoomMtg.getAttendeeslist({
                        success: function(attList) {
                          let lista_usuarios = attList.result.attendeesList;
                          lista_usuarios.forEach((user) => {
                            if (user.userName == data.userName) {
                              console.log('entro')
                              ZoomMtg.expel({
                                userId: user.userId
                              });
                            }
                          });
                          // console.log('attList22 :>> ', attList.result.attendeesList);
                        },
                      });
                    });
                    // console.log('attList :>> ', attList);
                  },
                });

                // meeting set to record by default
                ZoomMtg.record({
                  record: true,
                });

                // meeting set to show record button
                // ZoomMtg.showRecordFunction({
                // 	show: true
                // });


                // document.querySelector('#wc-footer > div > div:nth-child(2) > button')
                //   .style.display = 'none';
              },
              error: (error) => {
                console.log(error)
              }
            });
          }).fail(function(e) {
            console.error("error:", e);
          });

        },
        error: function(res) {
          console.log('res :>> ', res);
        },
      });
    }

    iniciarZoom();
  </script>



  @endpush
</x-zoom-layout>