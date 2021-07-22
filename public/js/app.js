$(document).ready(function (e) {

    if($('.verify-page').length){
        //Set display centre
        var id = decodeURI(getUrlVars()["id"]);

        //Set header based on selected region
        // if (active_display=="ordered"){
        //     $('.listed-tab').addClass('disabled');
        //     $('.listed-tab').removeClass('primary');
        //     $('.ordered-tab').removeClass('disabled');
        // }else{
        //     $('.ordered-tab').removeClass('primary');
        // }
    }

    if($('.qr-page').length > 0){
        setTimeout(function (e) {
            $('#gn-btn').click();
        },500)

    }

    $('#gn-btn').click(function(){
        var val = window.location.origin + '/verification/' + '?patient_id=' + $('#gn-val').val();

        if(val){
            $('#qr-example').qrcode(val);
        }else{
            alert("Please provide some text or url !");
        }
    });

});


// Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
