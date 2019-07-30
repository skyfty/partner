
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;

// open hidden layer
function mopen(id)
{
    // cancel close timer
    mcancelclosetime();

    // close old layer
    if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

    // get new layer and show it
    ddmenuitem = document.getElementById(id);
    if (ddmenuitem) {
        ddmenuitem.style.visibility = 'visible';

    }

}
// close showed layer
function mclose()
{
    if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
}

// go close timer
function mclosetime()
{
    closetimer = window.setTimeout(mclose, timeout);
}

// cancel close timer
function mcancelclosetime()
{
    if(closetimer)
    {
        window.clearTimeout(closetimer);
        closetimer = null;
    }
}
document.onclick = mclose;

function FP(p1, p2) {
    var vp1 = {};
    if (p1) {
        var pp1 = p1.split("&");
        for (x in pp1)
        {
            if (x == "remove"){
                continue;
            }
            var pv = pp1[x] + "";
            var ppp1 = pv.split("=");
            vp1[ppp1[0]] = ppp1[1];
        }
    }

    if (p2) {
        var pp2 = p2.split("&");
        for (x in pp2)
        {
            if (x == "remove"){
                continue;
            }
            var pv = pp2[x] + "";
            var ppp2 = pv.split("=");
            vp1[ppp2[0]] = ppp2[1];
        }
    }

    var vp2 = new Array();
    for(var x in vp1) {
        vp2.push(x + "=" + vp1[x]);
    }

    return vp2.join("&");
}