/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showIframePopupRef(ref) {
    var link = $(ref).attr("data-href");
    $(ref).magnificPopup({
        type: 'iframe',
        preloader: true,
        items: {
            src: link
        },
    }).magnificPopup('open');
}

function closeIframePopup(){
    $.magnificPopup.close(); 
}
