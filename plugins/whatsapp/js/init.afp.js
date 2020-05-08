$(function() {
    var AFWAButton = $('#AF-WAButton');
    AFWAButton.floatingWhatsApp({
        phone: AFWAButton.data('phone'),
        headerTitle: AFWAButton.data('title'), //Popup Title
        popupMessage: AFWAButton.data('message'), //Popup Message
        showPopup: true, //Enables popup display
        buttonImage: '<img src="arethafw/plugins/whatsapp/images/whatsapp.svg" />', //Button Image
        //headerColor: 'crimson', //Custom header color
        //backgroundColor: 'crimson', //Custom background button color
        position: AFWAButton.data('position')    
    });
});