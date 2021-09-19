console.log('hello i am working');

var modal = new tingle.modal({
    // footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2'],
    onOpen: function() {
        console.log('modal open');
    },
    onClose: function() {
        console.log('modal closed');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

// set content
// modal.setContent(document.getElementById('js-navbar-order'));
modal.setContent(document.getElementById('js-navbar-order-modal').innerHTML)


const cB = document.getElementById('call-from-navbar');
const cfB = document.getElementById('call-from-fixed-navbar');

cB.addEventListener('click', function() {
    modal.open()
});
cfB.addEventListener('click', function(event) {
    event.preventDefault();
    modal.open()
})

const modal2 = new tingle.modal({
    // footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2'],
    onOpen: function() {
        console.log('modal open');
    },
    onClose: function() {
        console.log('modal closed');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

modal2.setContent(document.getElementById('js-call-looking-for-modal').innerHTML)

const heroCta = document.getElementById('js-main__cta');

heroCta.addEventListener('click', function() {
    modal2.open();
})