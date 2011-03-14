 $(document).ready(function(){
      $("#jquery_jplayer_1").jPlayer({
        ready: function () {
          $(this).jPlayer("setMedia", {
            /*m4a: "http://www.jplayer.org/audio/m4a/Miaow-07-Bubble.m4a",
            oga: "http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"*/
        	mp3: "/storage/uploads/audio/ogg/armin.mp3"
          });
        },
        swfPath: "/js",
        supplied: "mp3"
        /*swfPath: "/js",
        supplied: "m4a, oga"*/
      });
    });
