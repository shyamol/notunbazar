window.all4coding = window.all4coding || {};
all4coding.core = all4coding.core || {};
all4coding.core.moveItem = function(gridObj, url) {
    new Ajax.Request(url, {
        method: 'get',
        onComplete: function(transport) {
            var response = transport.responseText.evalJSON();
            if(response.error){
                if($('messages')){
                    $('messages').innerHTML = response.message;
                }
            }
            else{
                gridObj.doFilter();
            }
        },
        onFailure: function(transport) {
            location.href = BASE_URL;
        }
    });
    return 0;
};