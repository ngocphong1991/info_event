/*Flip simple hand make Library */
/*-------------GUIDE-------------
1 - HTML CODE:
 <div class="flipper">
 <div class="flipper-1">
 <img alt="" src="../img/category/sport.png" >
 </div>
 <div class="flipper-2">
 <img alt="" src="../img/category/sport_active.png" />
 </div>
 </div><br>
 <div class="flipper">
 <div class="flipper-1">
 <img alt="" src="../img/category/sport.png" >
 </div>
 <div class="flipper-2">
 <img alt="" src="../img/category/sport_active.png" />
 </div>
 </div>

 2 - CSS STYLE:
 .flipper .flipper-1 img{
 position:absolute;
 cursor:pointer;
 width:145px;
 height:145px;
 }
 .flipper .flipper-2 img{
 display:none;
 position:absolute;
 cursor:pointer;
 }
 .flipper{
 width:145px;
 height:145px;
 position: relative;
 }

 3- USED:
 $(element).poFlip({
    effect : 'random'
 });
 --------------END GUIDE-------------*/

( function( $ ) {
    var count = 0;
    var status = {flat: 1, current: 'horizontal'};
    inArray = function isInArray(value, array) {
        return array.indexOf(value) > -1;
    };

    randomInt = function randomIntFromInterval(min,max)
    {
        return Math.floor(Math.random()*(max-min+1)+min);
    };

    $.fn.poFlip = function(options) {

        /*-- define value --*/
        var options = options || {};
        options.timeDuration = options.timeDuration || 200;
        options.height = options.height || 145;
        options.width = options.width || 145;
        options.margin = options.height/2 || 145/2;
        options.flip1 = options.flip1 || '.flipper-1';
        options.flip2 = options.flip2 || '.flipper-2';
        options.effect = options.effect || 'horizontal';

        if(!inArray(options.effect, ['horizontal', 'vertical', 'random'])){
            options.effect = 'horizontal';
        }


        var $img1 = $(this).children(options.flip1).children('a').children('img');
        var $img2 = $(this).children(options.flip2).children('a').children('img');

        if(options.effect == 'random'){
            if(status.flat == 0){
                $img1.attr('style','');
                $img2.attr('style','');
                count = 0;
                if(randomInt(1,2)%2 ==0){
                    options.effect = 'horizontal';
                }else options.effect = 'vertical';
            }
            if(status.flat != 0 && status.current == 'horizontal') options.effect = 'horizontal';
            if(status.flat != 0 && status.current == 'vertical') options.effect = 'vertical';
        }

        if(count == 0){
            if(options.effect == 'vertical') $img2.stop().css({height:'0px',width:''+options.width+'px',marginTop:''+options.margin+'px',opacity:'0.5'});
            if(options.effect == 'horizontal') $img2.stop().css({width:'0px',height:''+options.height+'px',marginLeft:''+options.margin+'px',opacity:'0.5'});
            count++;
        }

        var $h1 = $img1.height();
        var $h2 = $img2.height();
        var $w1 = $img1.width();
        var $w2 = $img2.width();
        /*-- end define value --*/
        if(options.effect == 'vertical'){
            if($h1 > 0){
                $img1.stop().animate({height:'0px',width:''+options.width+'px',marginTop:''+options.margin+'px',opacity:'0.5'},{duration:options.timeDuration});
                window.setTimeout(function() {
                    $img2.stop().animate({height:''+options.height+'px',width:''+options.width+'px',marginTop:'0px',opacity:'1'},{duration:options.timeDuration});
                },options.timeDuration);
                status.flat = 1;
                status.current = 'vertical';
            }

            if($h2 >0){
                $img2.stop().animate({height:'0px',width:''+options.width+'px',marginTop:''+options.margin+'px',opacity:'0.5'},{duration:options.timeDuration});
                window.setTimeout(function() {
                    $img1.stop().animate({height:''+options.height+'px',width:''+options.width+'px',marginTop:'0px',opacity:'1'},{duration:options.timeDuration});
                },options.timeDuration);
                status.flat = 0;
                status.current = 'vertical';
            }
        }
        if(options.effect == 'horizontal'){
            if($w1 > 0){
                $img1.stop().animate({width:'0px',height:''+options.height+'px',marginLeft:''+options.margin+'px',opacity:'0.5'},{duration:options.timeDuration});
                window.setTimeout(function() {
                    $img2.stop().animate({width:''+options.width+'px',height:''+options.height+'px',marginLeft:'0px',opacity:'1'},{duration:options.timeDuration});
                },options.timeDuration);
                status.flat = 1;
                status.current = 'horizontal';
            }

            if($w2 >0){
                $img2.stop().animate({width:'0px',height:''+options.height+'px',marginLeft:''+options.margin+'px',opacity:'0.5'},{duration:options.timeDuration});
                window.setTimeout(function() {
                    $img1.stop().animate({width:''+options.width+'px',height:''+options.height+'px',marginLeft:'0px',opacity:'1'},{duration:options.timeDuration});
                },options.timeDuration);
                status.flat = 0;
                status.current = 'horizontal';
            }
        }
    };
})( jQuery );