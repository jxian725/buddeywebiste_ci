//LOGIN VALIDATION
function loginValidate() {
    var username    = $( '#username' ).val();
    var password    = $( '#password' ).val();
    
    var data = { 'username' : username, 'password' : password }
    $.ajax({
        type    : 'POST',
        url     : adminurl + 'login/validate',
        data    : data,
        success : function( msg ) { //alert( msg );
            if( msg == 1 ) {
                window.location.href = adminurl + "dashboard";
            }else{
                window.location.href = adminurl;
            }
        }
    });
}
//FORGOT PASSWORD
function forgotValidate() {
    var useremail    = $( '#useremail' ).val();
    
    var data = { 'useremail' : useremail }
    $.ajax({
        type    : 'POST',
        url     : adminurl + 'forgotpass/validate',
        data    : data,
        success : function( msg ) {
            if( msg == 1 ) {
                alert('Password has been sent to your e-mail address.');
                window.location.href = adminurl + "login";
            } else {
                alert('Operation failed.');
            }
        }
    });
}

function validateNewsletter( type ) {
    tinyMCE.triggerSave();
    var $btn  = $( '#addnewslettersubmit' );
    $btn.button( 'Posting..' );
    var data    = $( "#newsletter_form" ).find( "select, textarea, input" ).serialize();
    $.ajax({
        type    : 'POST',
        url     : adminurl + 'newsletter/newsletterValidate',
        data    : data,
        success : function( msg ) {
            if( msg == 1 ) {
                toastr.success('Newsletter Posted Successfully.');
                location.reload();
            } else {
                toastr.error(msg,'Error');
            }
            $btn.button( 'reset' );
        }
    });
    return false;
}
function checkUrl(url){
    //regular expression for URL
    var pattern = /^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/;
    if(pattern.test(url)){
        return true;
    } else {
        return false;
    }
}